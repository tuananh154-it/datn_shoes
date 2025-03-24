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
    // Lấy giỏ hàng của user
    public function index(Request $request)
    {
        $user = $request->user(); // Middleware auth đảm bảo user luôn hợp lệ

        // Lấy giỏ hàng của người dùng cùng với các item và sản phẩm liên quan
        $cart = Cart::where('user_id', $user->id)->with('items.productDetail.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Giỏ hàng trống'], 200);
        }

        return response()->json([
            'cart' => $cart->items->map(function ($item) {
                return [
                    'id_cart_item' => $item->id,
                    'product_detail_id' => $item->productDetail->id ?? null, // Kiểm tra nếu null
                    'product_name' => $item->productDetail->product->name ?? 'N/A',
                    'color' => $item->productDetail->color ?? 'N/A',
                    'size' => $item->productDetail->size ?? 'N/A',
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'image' => $item->productDetail->image ?? null,
                ];
            }),
            'total_price' => $cart->total_price, // Thêm tổng tiền của giỏ hàng
        ]);
    }


    // Thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $productDetailId = $request->input('product_detail_id');
        $quantity = $request->input('quantity', 1);

        $productDetail = ProductDetail::find($productDetailId);
        if (!$productDetail || $productDetail->quantity < $quantity) {
            return response()->json(['message' => 'Sản phẩm không đủ số lượng'], 400);
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_detail_id', $productDetailId)
                            ->first();

        if ($cartItem) {
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
        if ($newQuantity <= 0) {
            return response()->json(['message' => 'Số lượng không hợp lệ'], 400);
        }

        $productDetail = $cartItem->productDetail;
        if ($productDetail->quantity < $newQuantity) {
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
}
