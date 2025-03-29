<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'payment_method' => 'required|in:credit_card,cash_on_delivery,paypal',
            'voucher_id' => 'nullable|exists:vouchers,id',
            'note' => 'nullable|string',
        ]);
    
        $user = $request->user(); // có thể là null nếu cho phép khách
        $cart = Cart::with('items.productDetail')->where('user_id', $user->id ?? null)->first();
    
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Giỏ hàng trống'], 400);
        }
    
        DB::beginTransaction();
    
        try {
            $total = 0;
            $deliverFee = 30000; // ví dụ phí giao hàng cố định
    
            foreach ($cart->items as $item) {
                $price = $item->productDetail->discount_price ?? $item->productDetail->default_price;
                $total += $price * $item->quantity;
                if ($item->productDetail->quantity < $item->quantity) {
                    return response()->json(['message' => 'Sản phẩm "' . $item->productDetail->product->name . '" không đủ số lượng'], 400);
                }
                
            }
    
            // Xử lý voucher nếu có
            $voucher = null;
            $discount = 0;
    
            if ($request->voucher_id) {
                $voucher = Voucher::where('id', $request->voucher_id)
                    ->where('status', 'active')
                    ->where('expiration_date', '>=', now())
                    ->first();
    
                if (!$voucher) {
                    return response()->json(['message' => 'Voucher không hợp lệ'], 400);
                }
    
                if ($total < $voucher->min_purchase_amount) {
                    return response()->json(['message' => 'Không đủ điều kiện áp dụng voucher'], 400);
                }
    
                if ($voucher->discount_percent) {
                    $discount = $total * ($voucher->discount_percent / 100);
                } elseif ($voucher->discount_amount) {
                    $discount = $voucher->discount_amount;
                }
    
                if ($discount > $voucher->max_discount_amount) {
                    $discount = $voucher->max_discount_amount;
                }
    
                $total -= $discount;
            }
    
            $total_price = $total + $deliverFee;
    
            // Tạo đơn hàng
            $order = Order::create([
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'user_id' => $user?->id ?? 1, // fallback nếu khách (bạn có thể cần user guest mặc định)
                'voucher_id' => $voucher->id ?? null,
                'status' => 'waiting_for_confirmation',
                'payment_status' => 'unpaid',
                'payment_method' => $request->payment_method,
                'note' => $request->note,
                'deliver_fee' => $deliverFee,
                'total_price' => $total_price,
            ]);
    
            // Chi tiết đơn hàng
            foreach ($cart->items as $item) {
                $price = $item->productDetail->discount_price ?? $item->productDetail->default_price;
                $quantity = $item->quantity;
                $lineTotal = $price * $quantity;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_detail_id' => $item->product_detail_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'total_price' => $lineTotal,
                ]);
                $item->productDetail->decrement('quantity', $quantity);
            }
    
            $cart->items()->delete();
    
            DB::commit();
    
            return response()->json([
                'message' => 'Đặt hàng thành công',
                'order_id' => $order->id,
                'total' => $total_price,
                'discount' => $discount
            ], 201);
    
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Lỗi đặt hàng', 'error' => $e->getMessage()], 500);
        }
    }
    public function listOrders(Request $request)
{
    $user = $request->user();

    $orders = Order::with('voucher')
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($orders);
}
public function orderDetail($id, Request $request)
{
    $user = $request->user();

    $order = Order::with(['order_details.productDetail', 'voucher'])
        ->where('user_id', $user->id)
        ->find($id);

    if (!$order) {
        return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
    }

    return response()->json($order);
}
public function cancelOrder($id, Request $request)
{
    $user = $request->user();

    $order = Order::where('user_id', $user->id)
        ->where('id', $id)
        ->where('status', 'waiting_for_confirmation')
        ->first();

    if (!$order) {
        return response()->json(['message' => 'Không thể hủy đơn hàng này'], 400);
    }

    $order->update(['status' => 'cancelled']);

    return response()->json(['message' => 'Đơn hàng đã được hủy']);
}
public function getCart(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json(['message' => 'Chưa đăng nhập'], 401);
    }

    // Lấy giỏ hàng
    $cart = Cart::with(['items.productDetail.product', 'items.productDetail.size', 'items.productDetail.color'])
        ->where('user_id', $user->id)
        ->first();

    if (!$cart || $cart->items->isEmpty()) {
        return response()->json(['message' => 'Giỏ hàng trống'], 400);
    }

    // Tính tiền
    $subtotal = 0;
    $items = $cart->items->map(function ($item) use (&$subtotal) {
        $price = $item->productDetail->discount_price ?? $item->productDetail->default_price;
        $lineTotal = $price * $item->quantity;
        $subtotal += $lineTotal;

        return [
            'product_name' => $item->productDetail->product->name,
            'image' => $item->productDetail->image,
            'size' => $item->productDetail->size->name,
            'color' => $item->productDetail->color->name,
            'price' => $price,
            'quantity' => $item->quantity,
            'line_total' => $lineTotal,
        ];
    });

    // Xử lý mã giảm giá (nếu có)
    $voucherCode = $request->query('voucher');
    $discount = 0;
    $voucherInfo = null;

    if ($voucherCode) {
        $voucher = Voucher::where('name', $voucherCode)
            ->where('status', 'active')
            ->where('expiration_date', '>=', now())
            ->first();

        if ($voucher && $subtotal >= $voucher->min_purchase_amount) {
            $discount = $voucher->discount_percent
                ? $subtotal * ($voucher->discount_percent / 100)
                : $voucher->discount_amount;

            $discount = min($discount, $voucher->max_discount_amount);

            $voucherInfo = [
                'id' => $voucher->id,
                'name' => $voucher->name,
                'discount' => $discount,
            ];
        }
    }

    $deliverFee = 30000;
    $total = $subtotal - $discount + $deliverFee;

    // ✅ Trả **toàn bộ thông tin user**
    $userInfo = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'gender' => $user->gender,
        'date_of_birth' => $user->date_of_birth,
        'phone_number' => $user->phone_number,
        'address' => $user->address,
    ];

    return response()->json([
        'user' => $userInfo,
        'cart_items' => $items,
        'subtotal' => $subtotal,
        'discount' => $discount,
        'voucher' => $voucherInfo,
        'deliver_fee' => $deliverFee,
        'total' => $total
    ]);
}

}
