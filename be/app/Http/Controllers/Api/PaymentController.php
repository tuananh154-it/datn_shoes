<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Hiển thị danh sách tất cả thanh toán
     */
    public function index()
    {
        $payments = Payment::with([
            'order', 
            'order.user', 
            'order.cart', 
            'order.cart.cartItems', 
            'order.cart.cartItems.product', 
            'order.cart.cartItems.product.productDetail'
        ])->get();
    
        return response()->json($payments);
    }

    /**
     * Tạo bản ghi thanh toán mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|in:COD,VnPAY,zaloPay',
            'amount' => 'required|numeric',
            'status' => 'required|in:pending,completed,failed',
            'payment_date' => 'required|date',
        ]);

        $order = Order::with('voucher', 'payments')->findOrFail($request->order_id);

        // Kiểm tra nếu đơn hàng đã thanh toán
        if ($order->payment_status === 'paid' || $order->payments()->exists()) {
            return response()->json(['message' => 'This order has already been paid.'], 400);
        }

        // Tính giảm giá từ voucher nếu có
        $discount = 0;
        $voucher = $order->voucher;
        if ($voucher && $voucher->status == 'active' && $order->total_price >= $voucher->min_purchase_amount) {
            if (!is_null($voucher->discount_percent)) {
                $discount = ($order->total_price * $voucher->discount_percent) / 100;
                $discount = min($discount, $voucher->max_discount_amount);
            } elseif (!is_null($voucher->discount_amount)) {
                $discount = min($voucher->discount_amount, $voucher->max_discount_amount);
            }
        }

        // Cập nhật trạng thái thanh toán của đơn hàng
        $order->payment_status = 'paid';
        $order->save();

        // Tạo bản ghi thanh toán mới
        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => $request->user_id,
            'payment_method' => $request->payment_method,
            'amount' => $order->total_price - $discount,
            'status' => $request->status,
            'payment_date' => $request->payment_date,
            'note' => $order->note,
        ]);

        return response()->json(['message' => 'Payment created successfully', 'payment' => $payment], 201);
    }

    /**
     * Hiển thị thông tin thanh toán cụ thể
     */
    public function show($id)
    {
        $payment = Payment::with([
            'order',
            'order.user',
            'order.orderDetails.productDetail.product',
            'order.orderDetails.productDetail.size',
            'order.orderDetails.productDetail.color',
            'order.voucher'
        ])->find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
}

        $user = $payment->order->user;
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $voucher = $payment->order->voucher;

        $discount = 0;
        if ($voucher && $voucher->status == 'active' && $payment->order->total_price >= $voucher->min_purchase_amount) {
            if (!is_null($voucher->discount_percent)) {
                $discount = ($payment->order->total_price * $voucher->discount_percent) / 100;
                $discount = min($discount, $voucher->max_discount_amount);
            } elseif (!is_null($voucher->discount_amount)) {
                $discount = min($voucher->discount_amount, $voucher->max_discount_amount);
            }
        }

        $orderItems = $payment->order->orderDetails->map(function ($item) {
            return [
                'product_name' => $item->productDetail->product->name,
                'product_image' => $item->productDetail->product->image,
                'size' => $item->productDetail->size->name,
                'color' => $item->productDetail->color->name,
                'price' => $item->productDetail->discount_price ?? $item->productDetail->default_price,
                'quantity' => $item->quantity,
                'total_price' => $item->total_price,
            ];
        });

        $subtotal = $orderItems->sum('total_price');
        $shippingFee = $payment->order->deliver_fee;
        $totalBeforeDiscount = $subtotal + $shippingFee;
        $total = $totalBeforeDiscount - $discount;

        return response()->json([
            'billing_shipping' => [
                'name' => $user->name ?? 'No name available',
                'phone_number' => $user->phone_number ?? 'No phone number',
                'email' => $user->email ?? 'No email',
                'address' => $user->address ?? 'No address',
                'note' => $payment->order->note ?? 'No note',
                'city' => $payment->order->city ?? 'Unknown',
                'district' => $payment->order->district ?? 'Unknown',
                'ward' => $payment->order->ward ?? 'Unknown',
                'detailed_address' => $payment->order->address ?? 'Unknown',
            ],
            'order_details' => $orderItems,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'total_before_discount' => $totalBeforeDiscount,
            'voucher_discount' => $discount,
            'total' => $total,
            'payment_method' => $payment->payment_method,
            'payment_status' => $payment->status,
            'payment_date' => $payment->payment_date,
            'voucher' => $voucher ? [
                'name' => $voucher->name,
                'discount_percent' => $voucher->discount_percent,
                'discount_amount' => $voucher->discount_amount,
                'expiration_date' => $voucher->expiration_date,
            ] : null
        ]);
    }
}
