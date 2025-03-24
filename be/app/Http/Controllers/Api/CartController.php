<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    // Lấy giỏ hàng của user cùng với các sản phẩm trong giỏ hàng
    $cart = Cart::where('user_id', $user->id)->with('items.productDetail.product')->first();

    if (!$cart || $cart->items->isEmpty()) {
        return response()->json(['message' => 'Giỏ hàng trống'], 200);
    }

    return response()->json([
        'cart' => $cart->items->map(function ($item) {
            return [
                'id_cart_item' => $item->id,
                'product_name' => $item->productDetail->product->name ?? 'N/A',
                'color' => $item->productDetail->color->name ?? 'N/A',
                'size' => $item->productDetail->size->name ?? 'N/A',
                'quantity' => $item->quantity,
                'default_price' => $item->productDetail->default_price, // Giá gốc
                'discount_price' => $item->productDetail->discount_price ?? $item->productDetail->default_price, // Giá khuyến mãi (nếu có)
                'final_price' => ($item->productDetail->discount_price ?? $item->productDetail->default_price) *$item->quantity, // Giá thực tế áp dụng
                'image' => $item->productDetail->image ?? null,
            ];
        }),
        'total_price' => $cart->items->sum(fn($item) => $item->quantity * ($item->productDetail->discount_price ?? $item->productDetail->default_price)),
    ]);
}


    // Thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $productDetailId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        if (!$productDetailId) {
            return response()->json(['message' => 'Thiếu product_id'], 400);
        }

        $productDetail = ProductDetail::find($productDetailId);
        if (!$productDetail) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 400);
        }

        if ($productDetail->quantity < $quantity) {
            return response()->json([
                'message' => 'Sản phẩm không đủ số lượng',
                'product_quantity' => $productDetail->quantity,
                'requested_quantity' => $quantity
            ], 400);
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_detail_id', $productDetailId)
                            ->first();

        if ($cartItem) {
            if ($productDetail->quantity < ($cartItem->quantity + $quantity)) {
                return response()->json(['message' => 'Sản phẩm không đủ số lượng'], 400);
            }
            $cartItem->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_detail_id' => $productDetailId,
                'quantity' => $quantity,
                'price' => $productDetail->price,
            ]);
        }

        return response()->json(['message' => 'Sản phẩm đã thêm vào giỏ hàng'], 200);
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateCart(Request $request, $cartItemId)
    {
        $cartItem = CartItem::find($cartItemId);
        if (!$cartItem) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
        }

        $newQuantity = $request->input('quantity');
        error_log('Giá trị nhận được: ' . json_encode($newQuantity)); // Ghi log để kiểm tra

        if ($newQuantity <= 0) {
            return response()->json(['message' => 'Số lượng không hợp lệ', 'debug' => $newQuantity], 400);
        }

        $productDetail = $cartItem->productDetail;
        if (!$productDetail || $productDetail->quantity < $newQuantity) {
            return response()->json(['message' => 'Sản phẩm không đủ hàng'], 400);
        }

        $cartItem->update(['quantity' => $newQuantity]);

        return response()->json(['message' => 'Cập nhật giỏ hàng thành công'], 200);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeCartItem($cartItemId)
    {
        $cartItem = CartItem::find($cartItemId);
        if (!$cartItem) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
        }

        $cartItem->delete();
        return response()->json(['message' => 'Xóa sản phẩm thành công'], 200);
    }
    public function syncCart(Request $request)
{
    $user = $request->user();
    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    $cartItems = $request->input('cart_items'); // Nhận danh sách sản phẩm từ frontend

    if (!$cartItems || !is_array($cartItems)) {
        return response()->json(['message' => 'Dữ liệu giỏ hàng không hợp lệ'], 400);
    }

    // Lấy giỏ hàng hiện tại của user
    $cart = Cart::firstOrCreate(['user_id' => $user->id]);

    // Xóa giỏ hàng cũ để đồng bộ dữ liệu mới
    CartItem::where('cart_id', $cart->id)->delete();

    // Thêm sản phẩm mới từ request vào database
    foreach ($cartItems as $item) {
        CartItem::create([
            'cart_id' => $cart->id,
            'product_detail_id' => $item['product_detail_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    return response()->json(['message' => 'Giỏ hàng đã được đồng bộ'], 200);
}

}
