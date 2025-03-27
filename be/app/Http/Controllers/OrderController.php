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
            $query->whereHas('user', function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }
    
        // Tìm kiếm theo trạng thái đơn hàng
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
    
        // Phân trang kết quả tìm kiếm
        $orders = $query->paginate(5);
    
        // Trả về view với các đơn hàng tìm được
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
        // Tìm đơn hàng theo id
        $order = Order::findOrFail($id);

        // Định nghĩa các trạng thái hợp lệ và chuyển tiếp trạng thái
        $validStatusTransitions = [
            'waiting_for_confirmation' => 'waiting_for_pickup',
            'waiting_for_pickup' => 'waiting_for_delivery',
            'waiting_for_delivery' => 'delivered',
            'delivered' => 'returned',
            'returned' => 'cancelled',
        ];

        // Kiểm tra nếu trạng thái mới hợp lệ
        if (isset($validStatusTransitions[$order->status]) && $validStatusTransitions[$order->status] === $request->status) {
            // Cập nhật trạng thái mới
            $order->status = $request->status;
            $order->save();

            // Thông báo thành công
            return redirect()->route('orders.index')->with('success', 'Trạng thái đơn hàng đã được cập nhật.');
        }

        // Thông báo lỗi nếu trạng thái không hợp lệ
        return redirect()->route('orders.index')->with('error', 'Trạng thái không hợp lệ.');
    }
}
