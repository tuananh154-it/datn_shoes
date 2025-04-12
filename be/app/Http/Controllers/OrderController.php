<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Voucher;
use App\Models\User;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

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
        // Eager load order_details, product_detail, và product
        $order = Order::with(['order_details.productDetail.product', 'user'])->findOrFail($id);

        // Tính tổng giá trị các sản phẩm trong đơn hàng
        $total_product_value = $order->order_details->sum(function ($orderDetail) {
            return $orderDetail->quantity * $orderDetail->price;
        });

        // Lấy các thông tin khác của đơn hàng
        $shipping_fee = $order->deliver_fee; // Phí vận chuyển
        $total_price = $order->total_price; // Tổng tiền

        // Trả về view với các dữ liệu
        return view('orders.show', compact('order', 'total_product_value', 'shipping_fee', 'total_price'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
    
        $currentStatus = $order->status;
        $newStatus = $request->status;
    
        // Trạng thái không thể chuyển tiếp nữa
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
    
        $validTransitions = [
            'pending'    => ['cancelled'],
            'confirmed'  => ['processing', 'cancelled'],
            'processing' => ['shipping', 'cancelled'],
            'shipping'   => ['delivered'],
            'delivered'  => ['completed', 'returned'],
            'returned'   => ['refunded'],
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
    
            return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật.');
        }
    
        return redirect()->back()
            ->with('error', 'Không thể chuyển trạng thái từ "' . $currentStatus . '" sang "' . $newStatus . '".');
    }
    
    

   
}
