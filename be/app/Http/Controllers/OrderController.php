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
    // Hiển thị danh sách đơn hàng
    public function index(Request $request)
    {
        // Khởi tạo query cơ bản để lấy tất cả đơn hàng với quan hệ 'user' và 'voucher'
        $query = Order::with('user', 'voucher');
    
        // Tìm kiếm theo tên người dùng
        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }
    
        // Tìm kiếm theo trạng thái đơn hàng
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
    
        // 👉 Sắp xếp theo thời gian tạo mới nhất
        $query->orderBy('created_at', 'desc');
    
        // Phân trang
        $orders = $query->paginate(5);
    
        return view('orders.index', compact('orders'));
    }
    
    

    public function show($id)
    {
        // Eager load đầy đủ các quan hệ: sản phẩm, biến thể, người dùng, voucher
        $order = Order::with([
            'order_details.productDetail.product',
            'order_details.productDetail.color',
            'order_details.productDetail.size',
            'user',
            'voucher'
        ])->findOrFail($id);
    
        // Tính tổng giá trị sản phẩm (không bao gồm phí ship và giảm giá)
        $total_product_value = $order->order_details->sum(function ($orderDetail) {
            return $orderDetail->quantity * $orderDetail->price;
        });
    
        // Các thông tin khác
        $shipping_fee = $order->deliver_fee;
        $total_price = $order->total_price;
    
        return view('orders.show', compact(
            'order',
            'total_product_value',
            'shipping_fee',
            'total_price'
        ));
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
        if ($currentStatus === 'pending' && $newStatus === 'confirmed') {
            return redirect()->back()
                ->with('error', 'Chỉ người dùng mới có thể xác nhận đơn hàng qua email.');
        }
    
        // Không cho phép admin chuyển từ delivered → completed
        if ($currentStatus === 'delivered' && $newStatus === 'completed') {
            return redirect()->back()
                ->with('error', 'Chỉ người dùng mới có thể xác nhận đã nhận hàng để hoàn tất đơn.');
        }
    
        $validTransitions = [
            'pending'    => ['cancelled'],
            'confirmed'  => ['processing', 'cancelled'],
            'processing' => ['shipping', 'cancelled'],
            'shipping'   => ['delivered'],
            'delivered'  => ['returned'],
            'returned'   => ['refunded'],
        ];
    
        if (
            isset($validTransitions[$currentStatus]) &&
            in_array($newStatus, $validTransitions[$currentStatus])
        ) {
            $order->status = $newStatus;
    
            if ($newStatus === 'delivered') {
                $order->payment_status = 'paid';
    
                // Gửi email xác nhận đã giao hàng
                try {
                    Mail::to($order->email)->send(new \App\Mail\OrderDeliveredMail($order));
                } catch (\Exception $e) {
                    Log::error('Lỗi gửi mail xác nhận giao hàng: ' . $e->getMessage());
                }
            }
    
            $order->save();
    
            return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật.');
        }
    
        return redirect()->back()
            ->with('error', 'Không thể chuyển trạng thái từ "' . $currentStatus . '" sang "' . $newStatus . '".');
    }
    
   
}
