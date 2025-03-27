<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
   
    public function index()
    {
        // Lấy tất cả thông tin thanh toán
        $payments = Payment::with(['order', 'order.user'])->get();
    
        return response()->json($payments);
    }
    /**
     * Store a newly created resource in storage.
     */
   
     public function store(Request $request)
     {
         // Validate input data
         $request->validate([
             'order_id' => 'required|exists:orders,id',
             'user_id' => 'required|exists:users,id',
             'payment_method' => 'required|in:COD,VnPAY,zaloPay',
             'amount' => 'required|numeric',
             'status' => 'required|in:pending,completed,failed',
             'payment_date' => 'required|date',
         ]);
     
         // Lấy thông tin order
         $order = Order::find($request->order_id);
         $voucher = $order->voucher;  // Nếu có voucher
     
         // Tính toán giảm giá từ voucher nếu có
         $discount = 0;
         if ($voucher && $voucher->status == 'active' && $order->total_price >= $voucher->min_purchase_amount) {
             if ($voucher->discount_percent) {
                 $discount = ($order->total_price * $voucher->discount_percent) / 100;
                 $discount = min($discount, $voucher->max_discount_amount);
             } elseif ($voucher->discount_amount) {
                 $discount = min($voucher->discount_amount, $voucher->max_discount_amount);
             }
         }
     
         // Nếu đơn hàng chưa thanh toán, cập nhật trạng thái thanh toán
         if ($order->payment_status == 'unpaid') {
             $order->payment_status = 'paid';
             $order->save();
         }
     
         // Tạo payment mới
         $payment = Payment::create([
             'order_id' => $order->id,
             'user_id' => $request->user_id,
             'payment_method' => $request->payment_method,
             'amount' => $order->total_price - $discount, // Thanh toán sau giảm giá
             'status' => $request->status,
             'payment_date' => $request->payment_date,
             'note' => $order->note, // Lưu ghi chú nếu có
         ]);
     
         // Trả về thông tin thanh toán đã lưu
         return response()->json(['message' => 'Payment created successfully', 'payment' => $payment], 201);
     }
     
    /**
     * Display the specified resource.
     */
    // public function show($id)
    // {
    //     $payment = Payment::with([
    //         'order',
    //         'order.user',
    //         'order.orderDetails.productDetail.product',
    //         'order.orderDetails.productDetail.size',
    //         'order.orderDetails.productDetail.color',
    //         'order.voucher'
    //     ])->find($id);
    
    //     if (!$payment) {
    //         return response()->json(['message' => 'Payment not found'], 404);
    //     }
    
    //     // Kiểm tra trạng thái thanh toán
    //     if ($payment->status != 'completed') {
    //         return response()->json(['message' => 'Payment is not completed yet.'], 400);
    //     }
    
    //     $customer = $payment->order->user;
    //     $voucher = $payment->order->voucher;
    
    //     // Tính toán giảm giá từ voucher
    //     $discount = 0;
    //     if ($voucher && $voucher->status == 'active' && $payment->order->total_price >= $voucher->min_purchase_amount) {
    //         if ($voucher->discount_percent) {
    //             $discount = ($payment->order->total_price * $voucher->discount_percent) / 100;
    //             $discount = min($discount, $voucher->max_discount_amount);
    //         } elseif ($voucher->discount_amount) {
    //             $discount = min($voucher->discount_amount, $voucher->max_discount_amount);
    //         }
    //     }
    
    //     $orderItems = $payment->order->orderDetails->map(function ($item) {
    //         return [
    //             'product_name' => $item->productDetail->product->name,
    //             'product_image' => $item->productDetail->product->image,
    //             'size' => $item->productDetail->size->name,
    //             'color' => $item->productDetail->color->name,
    //             'price' => $item->productDetail->discount_price ?? $item->productDetail->default_price,
    //             'quantity' => $item->quantity,
    //             'total_price' => $item->total_price,
    //         ];
    //     });
    
    //     $subtotal = $orderItems->sum('total_price');
    //     $shippingFee = $payment->order->deliver_fee;
    //     $totalBeforeDiscount = $subtotal + $shippingFee;
    //     $total = $totalBeforeDiscount - $discount;
    
    //     return response()->json([
    //         'billing_shipping' => [
    //             'name' => $customer->name,
    //             'phone_number' => $customer->phone_number,
    //             'email' => $customer->email,
    //             'address' => $customer->address,
    //             'note' => $payment->order->note ?? 'No note',
    //             'city' => $payment->order->city ?? 'Unknown',
    //             'district' => $payment->order->district ?? 'Unknown',
    //             'ward' => $payment->order->ward ?? 'Unknown',
    //             'detailed_address' => $payment->order->address ?? 'Unknown',
    //         ],
    //         'order_details' => $orderItems,
    //         'subtotal' => $subtotal,
    //         'shipping_fee' => $shippingFee,
    //         'total_before_discount' => $totalBeforeDiscount,
    //         'voucher_discount' => $discount,
    //         'total' => $total,
    //         'payment_method' => $payment->payment_method,
    //         'payment_status' => $payment->status,
    //         'payment_date' => $payment->payment_date,
    //         'voucher' => $voucher ? [
    //             'name' => $voucher->name,
    //             'discount_percent' => $voucher->discount_percent,
    //             'discount_amount' => $voucher->discount_amount,
    //             'expiration_date' => $voucher->expiration_date,
    //         ] : null
    //     ]);
    // }
    // public function show($id)
    // {
    //     // Eager load tất cả các mối quan hệ cần thiết
    //     $payment = Payment::with([
    //         'order',
    //         'order.user',  // Eager load quan hệ giữa Order và User
    //         'order.orderDetails.productDetail.product',
    //         'order.orderDetails.productDetail.size',
    //         'order.orderDetails.productDetail.color',
    //         'order.voucher'
    //     ])->find($id);
    
    //     // Kiểm tra nếu không tìm thấy payment
    //     if (!$payment) {
    //         return response()->json(['message' => 'Payment not found'], 404);
    //     }
    
    //     // Kiểm tra trạng thái thanh toán
    //     if ($payment->status != 'completed') {
    //         return response()->json(['message' => 'Payment is not completed yet.'], 400);
    //     }
    
    //     // Lấy thông tin người dùng (user) từ đơn hàng, kiểm tra nếu không có user
    //     $user = $payment->order->user;
    //     if (!$user) {
    //         return response()->json(['message' => 'User not found'], 404);
    //     }
    
    //     $voucher = $payment->order->voucher;
    
    //     // Tính toán giảm giá từ voucher
    //     $discount = 0;
    //     if ($voucher && $voucher->status == 'active' && $payment->order->total_price >= $voucher->min_purchase_amount) {
    //         if ($voucher->discount_percent) {
    //             $discount = ($payment->order->total_price * $voucher->discount_percent) / 100;
    //             $discount = min($discount, $voucher->max_discount_amount);
    //         } elseif ($voucher->discount_amount) {
    //             $discount = min($voucher->discount_amount, $voucher->max_discount_amount);
    //         }
    //     }
    
    //     // Lấy thông tin chi tiết đơn hàng
    //     $orderItems = $payment->order->orderDetails->map(function ($item) {
    //         return [
    //             'product_name' => $item->productDetail->product->name,
    //             'product_image' => $item->productDetail->product->image,
    //             'size' => $item->productDetail->size->name,
    //             'color' => $item->productDetail->color->name,
    //             'price' => $item->productDetail->discount_price ?? $item->productDetail->default_price,
    //             'quantity' => $item->quantity,
    //             'total_price' => $item->total_price,
    //         ];
    //     });
    
    //     // Tính toán tổng tiền
    //     $subtotal = $orderItems->sum('total_price');
    //     $shippingFee = $payment->order->deliver_fee;
    //     $totalBeforeDiscount = $subtotal + $shippingFee;
    //     $total = $totalBeforeDiscount - $discount;
    
    //     return response()->json([
    //         'billing_shipping' => [
    //             'name' => $user->name ?? 'No name available',  // Tránh lỗi nếu user không có tên
    //             'phone_number' => $user->phone_number ?? 'No phone number',  // Tương tự với phone_number
    //             'email' => $user->email ?? 'No email',  // Tương tự với email
    //             'address' => $user->address ?? 'No address',  // Tương tự với address
    //             'note' => $payment->order->note ?? 'No note',
    //             'city' => $payment->order->city ?? 'Unknown',
    //             'district' => $payment->order->district ?? 'Unknown',
    //             'ward' => $payment->order->ward ?? 'Unknown',
    //             'detailed_address' => $payment->order->address ?? 'Unknown',
    //         ],
    //         'order_details' => $orderItems,
    //         'subtotal' => $subtotal,
    //         'shipping_fee' => $shippingFee,
    //         'total_before_discount' => $totalBeforeDiscount,
    //         'voucher_discount' => $discount,
    //         'total' => $total,
    //         'payment_method' => $payment->payment_method,
    //         'payment_status' => $payment->status,
    //         'payment_date' => $payment->payment_date,
    //         'voucher' => $voucher ? [
    //             'name' => $voucher->name,
    //             'discount_percent' => $voucher->discount_percent,
    //             'discount_amount' => $voucher->discount_amount,
    //             'expiration_date' => $voucher->expiration_date,
    //         ] : null
    //     ]);
    // }

    public function show($id)
{
    // Eager load các mối quan hệ cần thiết
    $payment = Payment::with([
        'order',
        'order.user',  // Eager load quan hệ giữa Order và User
        'order.orderDetails.productDetail.product',
        'order.orderDetails.productDetail.size',
        'order.orderDetails.productDetail.color',
        'order.voucher'
    ])->find($id);

    // Kiểm tra nếu không tìm thấy payment
    if (!$payment) {
        return response()->json(['message' => 'Payment not found'], 404);
    }

    // Lấy thông tin người dùng (user) từ đơn hàng, kiểm tra nếu không có user
    $user = $payment->order->user;
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Lấy thông tin voucher
    $voucher = $payment->order->voucher;

    // Tính toán giảm giá từ voucher
    $discount = 0;
    if ($voucher && $voucher->status == 'active' && $payment->order->total_price >= $voucher->min_purchase_amount) {
        if ($voucher->discount_percent) {
            $discount = ($payment->order->total_price * $voucher->discount_percent) / 100;
            $discount = min($discount, $voucher->max_discount_amount);  // Giới hạn theo max_discount_amount
        } elseif ($voucher->discount_amount) {
            $discount = min($voucher->discount_amount, $voucher->max_discount_amount);  // Giới hạn theo max_discount_amount
        }
    }

    // Lấy thông tin chi tiết đơn hàng
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

    // Tính toán tổng tiền
    $subtotal = $orderItems->sum('total_price');
    $shippingFee = $payment->order->deliver_fee;
    $totalBeforeDiscount = $subtotal + $shippingFee;
    $total = $totalBeforeDiscount - $discount;  // Tổng tiền sau khi giảm giá

    // Trả về dữ liệu các trường mà bạn yêu cầu, bao gồm cả tính toán giảm giá
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

    

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
