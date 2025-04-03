<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

        if (!$request->user()) {
            return response()->json(['message' => 'Bạn cần đăng nhập để đặt hàng'], 401);
        }

        $request->validate([
            'username' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'payment_method' => 'required|in:credit_card,cash_on_delivery,paypal',
            'voucher_id' => 'nullable|exists:vouchers,id',
            'note' => 'nullable|string',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'integer|exists:cart_items,id',
        ]);

        $user = $request->user();
        $selectedItemIds = $request->selected_items;

        $validItemCount = CartItem::whereIn('id', $selectedItemIds)
            ->whereHas('cart', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();

        if ($validItemCount !== count($selectedItemIds)) {
            return response()->json(['message' => 'Có sản phẩm không hợp lệ hoặc không thuộc quyền sở hữu'], 403);
        }

        $cart = Cart::with(['items' => function ($query) use ($selectedItemIds) {
            $query->whereIn('id', $selectedItemIds);
        }, 'items.productDetail.product'])->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Không có sản phẩm nào được chọn để đặt hàng'], 400);
        }

        DB::beginTransaction();

        try {
            $total = 0;
            $deliverFee = 30000;
            $discount = 0;

            foreach ($cart->items as $item) {
                $productDetail = $item->productDetail;
                $price = $productDetail->discount_price ?? $productDetail->default_price;

                if ($productDetail->quantity < $item->quantity) {
                    return response()->json([
                        'message' => 'Sản phẩm "' . $productDetail->product->name . '" không đủ số lượng'
                    ], 400);
                }
                // if ($productDetail->quantity < $item->quantity) {
                //     throw new \Exception('Sản phẩm "' . $productDetail->product->name . '" không đủ số lượng');
                // }

                $total += $price * $item->quantity;
            }

            $voucher = null;

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

            $order = Order::create([
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'user_id' => $user->id,
                'voucher_id' => $voucher->id ?? null,
                'status' => 'waiting_for_confirmation',
                'payment_status' => 'unpaid',
                'payment_method' => $request->payment_method,
                'note' => $request->note,
                'deliver_fee' => $deliverFee,
                'total_price' => $total_price,
            ]);

            foreach ($cart->items as $item) {
                $productDetail = $item->productDetail;
                $price = $productDetail->discount_price ?? $productDetail->default_price;
                $quantity = $item->quantity;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_detail_id' => $item->product_detail_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $price * $quantity,
                ]);

                $productDetail->decrement('quantity', $quantity);
            }

            $cart->items()->whereIn('id', $selectedItemIds)->delete();

            DB::commit();
    try {
        Mail::to($request->email)->send(new \App\Mail\OrderPlacedMail(  $order->load('order_details.productDetail.product')));
    } catch (\Exception $e) {
        Log::error('Lỗi gửi email xác nhận đơn hàng: ' . $e->getMessage());
        // Không cần return lỗi, vẫn tiếp tục gửi response thành công
    }
            return response()->json([
                'message' => 'Đặt hàng thành công',
                'order_id' => $order->id,
                'total' => $total_price,
                'discount' => $discount
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Lỗi đặt hàng',
                'error' => $e->getMessage()
            ], 500);
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
}public function orderDetail($id, Request $request)
{
    $user = $request->user();

    $order = Order::with([
            'order_details.productDetail',
            'order_details.productDetail.color', // Lấy thông tin màu sắc
            'order_details.productDetail.size',  // Lấy thông tin kích thước
            'voucher'
        ])
        ->where('user_id', $user->id)
        ->find($id);

    if (!$order) {
        return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
    }

    // Thêm thông tin về màu sắc và kích thước vào dữ liệu trả về
    foreach ($order->order_details as $orderDetail) {
        $productDetail = $orderDetail->productDetail;
        $orderDetail->color = $productDetail->color ? $productDetail->color->name : null;
        $orderDetail->size = $productDetail->size ? $productDetail->size->name : null;
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



}public function getCart(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json(['message' => 'Bạn cần đăng nhập để xem giỏ hàng'], 401);
    }

    // Lấy giỏ hàng cùng các item và thông tin liên quan
    $cart = Cart::with([
        'items.productDetail.product',
        'items.productDetail.size',
        'items.productDetail.color'
    ])->where('user_id', $user->id)->first();

    if (!$cart || $cart->items->isEmpty()) {
        return response()->json(['message' => 'Giỏ hàng trống'], 400);
    }

    // Tính tổng tiền hàng
    $subtotal = 0;
    $items = $cart->items->map(function ($item) use (&$subtotal) {
        $productDetail = $item->productDetail;
        $price = $productDetail->discount_price ?? $productDetail->default_price;
        $lineTotal = $price * $item->quantity;
        $subtotal += $lineTotal;

        return [
            'id' => $item->id, // ✅ Quan trọng cho FE & đặt hàng
            'product_name' => $productDetail->product->name,
            'image' => $productDetail->image,
            'size' => $productDetail->size->name ?? null,
            'color' => $productDetail->color->name ?? null,
            'price' => $price,
            'quantity' => $item->quantity,
            'line_total' => $lineTotal,
        ];
    });

    // Xử lý mã giảm giá (nếu có)
    $voucherCode = $request->query('voucher'); // /checkout/init?voucher=ABC123
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

    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'gender' => $user->gender,
            'date_of_birth' => $user->date_of_birth,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
        ],
        'cart_items' => $items,
        'subtotal' => $subtotal,
        'discount' => $discount,
        'voucher' => $voucherInfo,
        'deliver_fee' => $deliverFee,
        'total' => $total
    ]);
}
public function previewCheckout(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json(['message' => 'Bạn cần đăng nhập để tiếp tục'], 401);
    }

    $selectedItemIds = $request->input('item_ids');

    if (!is_array($selectedItemIds) || empty($selectedItemIds)) {
        return response()->json(['message' => 'Vui lòng chọn sản phẩm để thanh toán'], 400);
    }

    // Lấy các item được chọn
    $items = CartItem::with(['productDetail.product', 'productDetail.size', 'productDetail.color'])
        ->whereIn('id', $selectedItemIds)
        ->whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->get();

    if ($items->isEmpty()) {
        return response()->json(['message' => 'Không tìm thấy sản phẩm phù hợp'], 400);
    }

    $subtotal = 0;
    $processedItems = $items->map(function ($item) use (&$subtotal) {
        $productDetail = $item->productDetail;
        $price = $productDetail->discount_price ?? $productDetail->default_price;
        $lineTotal = $price * $item->quantity;
        $subtotal += $lineTotal;

        return [
            'id' => $item->id,
            'product_name' => $productDetail->product->name,
            'image' => $productDetail->image,
            'size' => $productDetail->size->name ?? null,
            'color' => $productDetail->color->name ?? null,
            'price' => $price,
            'quantity' => $item->quantity,
            'line_total' => $lineTotal,
        ];
    });

    // Voucher (nếu có)
    $voucherCode = $request->input('voucher');
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

    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'gender' => $user->gender,
            'date_of_birth' => $user->date_of_birth,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
        ],
        'selected_items' => $processedItems,
        'subtotal' => $subtotal,
        'discount' => $discount,
        'voucher' => $voucherInfo,
        'deliver_fee' => $deliverFee,
        'total' => $total,
    ]);
}

}