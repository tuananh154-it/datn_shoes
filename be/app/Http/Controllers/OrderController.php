<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Voucher;
use App\Models\User;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'voucher');

        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $query->orderBy('created_at', 'desc');

        $orders = $query->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with([
            'order_details.productDetail.product',
            'order_details.productDetail.color',
            'order_details.productDetail.size',
            'user',
            'voucher'
        ])->findOrFail($id);
    
        $total_product_value = $order->order_details->sum(function ($orderDetail) {
            return $orderDetail->quantity * $orderDetail->price;
        });
    
        $shipping_fee = $order->deliver_fee;
        $total_price = $order->total_price;
    
        return view('orders.show', compact('order', 'total_product_value', 'shipping_fee', 'total_price'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
    
        $currentStatus = $order->status;
        $newStatus = $request->status;
    
        $statusLabels = [
            'pending'    => 'Chờ xác nhận',
            'confirmed'  => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipping'   => 'Đang giao hàng',
            'delivered'  => 'Đã giao',
            'completed'  => 'Hoàn tất',
            'cancelled'  => 'Đã hủy',
            'returned'   => 'Trả hàng',
            'refunded'   => 'Hoàn tiền',
        ];
    
        $finalStatuses = ['completed', 'refunded', 'cancelled'];
    
        if (in_array($currentStatus, $finalStatuses)) {
            return redirect()->back()
                ->with('error', 'Đơn hàng đã ở trạng thái "' . ($statusLabels[$currentStatus] ?? $currentStatus) . '". Không thể cập nhật thêm.');
        }
    
        $validTransitions = [
            'pending'    => ['confirmed', 'cancelled'],
            'confirmed'  => ['processing', 'cancelled'],
            'processing' => ['shipping', 'cancelled'],
            'shipping'   => ['delivered'],
            'delivered'  => ['completed', 'returned'],
            'returned'   => ['refunded'],
        ];
    
        // Kiểm tra nếu trạng thái thanh toán là 'paid' và người dùng cố hủy đơn
        if ($newStatus === 'cancelled' && $order->payment_status === 'paid') {
            return redirect()->back()
                ->with('error', 'Không thể hủy đơn hàng vì trạng thái thanh toán đã là "Đã thanh toán".');
        }
    
        if (
            isset($validTransitions[$currentStatus]) &&
            in_array($newStatus, $validTransitions[$currentStatus])
        ) {
            // Ngăn admin chuyển trực tiếp sang "completed"
            if ($newStatus === 'completed') {
                return redirect()->back()
                    ->with('error', 'Chỉ khách hàng mới có thể xác nhận hoàn tất đơn hàng qua email.');
            }
    
            $order->status = $newStatus;
            $order->save();
            broadcast(new OrderPlaced($order))->toOthers();
    
            // Gửi email khi trạng thái chuyển sang "delivered"
            if ($newStatus === 'delivered') {
                try {
                    $order->load('order_details.productDetail.product');
                    Mail::to($order->email)->queue(new \App\Mail\OrderPlacedMail($order));
                    Log::info('Email xác nhận hoàn tất đã được đưa vào hàng đợi cho đơn hàng #' . $order->id);
                } catch (\Exception $e) {
                    Log::error('Lỗi đưa email xác nhận hoàn tất vào hàng đợi cho đơn hàng #' . $order->id . ': ' . $e->getMessage());
                }
            }
    
            return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật thành "' . ($statusLabels[$newStatus] ?? $newStatus) . '".');
        }
    
        return redirect()->back()
            ->with('error', 'Không thể chuyển trạng thái từ "' . ($statusLabels[$currentStatus] ?? $currentStatus) . '" sang "' . ($statusLabels[$newStatus] ?? $newStatus) . '".');
    }
    

    public function bulkUpdateStatus(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        $newStatus = $request->input('bulk_status');

        if (empty($orderIds)) {
            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một đơn hàng.');
        }

        if (empty($newStatus)) {
            return redirect()->back()->with('error', 'Vui lòng chọn trạng thái mới.');
        }

        $orders = Order::whereIn('id', $orderIds)->get();

        $finalStatuses = ['completed', 'refunded', 'cancelled'];

        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['processing', 'cancelled'],
            'processing' => ['shipping', 'cancelled'],
            'shipping' => ['delivered'],
            'delivered' => ['completed', 'returned'],
            'returned' => ['refunded'],
        ];

        $updatedCount = 0;
        $emailedCount = 0;

        foreach ($orders as $order) {
            $currentStatus = $order->status;

            if (in_array($currentStatus, $finalStatuses)) {
                continue;
            }

            // Kiểm tra nếu trạng thái thanh toán là 'paid' và người dùng cố hủy đơn
            if ($newStatus === 'cancelled' && $order->payment_status === 'paid') {
                continue; // Bỏ qua đơn hàng đã thanh toán khi hủy
            }

            if (
                isset($validTransitions[$currentStatus]) &&
                in_array($newStatus, $validTransitions[$currentStatus])
            ) {
                // Ngăn admin chuyển trực tiếp sang "completed"
                if ($newStatus === 'completed') {
                    continue;
                }

                $order->status = $newStatus;
                $order->save();
                $updatedCount++;
                broadcast(new OrderPlaced($order))->toOthers();

                // Gửi email khi trạng thái chuyển sang "delivered"
                if ($newStatus === 'delivered') {
                    try {
                        $order->load('order_details.productDetail.product');
                        Mail::to($order->email)->queue(new \App\Mail\OrderPlacedMail($order));
                        Log::info('Email xác nhận hoàn tất đã được đưa vào hàng đợi cho đơn hàng #' . $order->id);
                        $emailedCount++;
                    } catch (\Exception $e) {
                        Log::error('Lỗi đưa email xác nhận hoàn tất vào hàng đợi cho đơn hàng #' . $order->id . ': ' . $e->getMessage());
                    }
                }
            }
        }

        if ($updatedCount > 0) {
            $message = "Đã cập nhật trạng thái cho $updatedCount đơn hàng.";
            if ($emailedCount > 0) {
                $message .= " Đã gửi email xác nhận hoàn tất cho $emailedCount đơn hàng.";
            }
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'Không có đơn hàng nào được cập nhật. Vui lòng kiểm tra trạng thái hoặc trạng thái thanh toán.');
        }
    }
}