<?php

namespace App\Http\Controllers;

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

    // public function show($id)
    // {
    //     $order = Order::with(['order_details.productDetail.product', 'user'])->findOrFail($id);

    //     $total_product_value = $order->order_details->sum(function ($orderDetail) {
    //         return $orderDetail->quantity * $orderDetail->price;
    //     });

    //     $shipping_fee = $order->deliver_fee;
    //     $total_price = $order->total_price;

    //     return view('orders.show', compact('order', 'total_product_value', 'shipping_fee', 'total_price'));
    // }
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

        $finalStatuses = ['completed', 'refunded', 'cancelled'];

        if (in_array($currentStatus, $finalStatuses)) {
            return redirect()->back()
                ->with('error', 'Đơn hàng đã hoàn tất, hoàn tiền hoặc bị hủy. Không thể cập nhật thêm.');
        }
          // Không cho admin tự chuyển sang confirmed
        //   if ($currentStatus === 'pending' && $newStatus === 'confirmed') {
        //     return redirect()->back()
        //         ->with('error', 'Chỉ người dùng mới có thể xác nhận đơn hàng qua email.');
        // }
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['processing', 'cancelled'],
            'processing' => ['shipping', 'cancelled'],
            'shipping' => ['delivered'],
            'delivered' => ['completed', 'returned'],
            'returned' => ['refunded'],
        ];

        if (
            isset($validTransitions[$currentStatus]) &&
            in_array($newStatus, $validTransitions[$currentStatus])
        ) {
            $order->status = $newStatus;

            if ($newStatus === 'delivered') {
                $order->payment_status = 'paid';
            }

            $order->save();

            // Gửi email nếu trạng thái chuyển từ "pending" sang "confirmed"
            // if ($currentStatus === 'pending' && $newStatus === 'confirmed') {
            //     try {
            //         // Tải dữ liệu cần thiết cho email
            //         $order->load('order_details.productDetail.product');
            //         // Gửi email bất đồng bộ qua hàng đợi
            //         Mail::to($order->email)->queue(new \App\Mail\OrderPlacedMail($order));
            //         Log::info('Email đã được đưa vào hàng đợi cho đơn hàng #' . $order->id);
            //     } catch (\Exception $e) {
            //         Log::error('Lỗi đưa email vào hàng đợi cho đơn hàng #' . $order->id . ': ' . $e->getMessage() . ' - Stack trace: ' . $e->getTraceAsString());
            //     }
            // }

            return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật.');
        }

        return redirect()->back()
            ->with('error', 'Không thể chuyển trạng thái từ "' . $currentStatus . '" sang "' . $newStatus . '".');
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
        $emailedCount = 0; // Đếm số đơn hàng được gửi email

        foreach ($orders as $order) {
            $currentStatus = $order->status;

            if (in_array($currentStatus, $finalStatuses)) {
                continue;
            }

            if (
                isset($validTransitions[$currentStatus]) &&
                in_array($newStatus, $validTransitions[$currentStatus])
            ) {
                $order->status = $newStatus;

                if ($newStatus === 'delivered') {
                    $order->payment_status = 'paid';
                }

                $order->save();
                $updatedCount++;

                // Gửi email nếu trạng thái chuyển từ "pending" sang "confirmed"
                // if ($currentStatus === 'pending' && $newStatus === 'confirmed') {
                //     try {
                //         // Tải dữ liệu cần thiết cho email
                //         $order->load('order_details.productDetail.product');
                //         // Gửi email bất đồng bộ qua hàng đợi
                //         Mail::to($order->email)->queue(new \App\Mail\OrderPlacedMail($order));
                //         Log::info('Email đã được đưa vào hàng đợi cho đơn hàng #' . $order->id);
                //         $emailedCount++;
                //     } catch (\Exception $e) {
                //         Log::error('Lỗi đưa email vào hàng đợi cho đơn hàng #' . $order->id . ': ' . $e->getMessage() . ' - Stack trace: ' . $e->getTraceAsString());
                //     }
                // }
            }
        }

        if ($updatedCount > 0) {
            $message = "Đã cập nhật trạng thái cho $updatedCount đơn hàng.";
            if ($emailedCount > 0) {
                $message .= " Đã gửi email xác nhận cho $emailedCount đơn hàng.";
            }
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'Không có đơn hàng nào được cập nhật. Vui lòng kiểm tra trạng thái.');
        }
    }
}