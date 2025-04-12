<?php
// app/Http/Controllers/ReturnController.php

// app/Http/Controllers/ReturnController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ReturnOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function processReturn(Request $request)
    {
        DB::beginTransaction();

        try {
            $order = Order::find($request->order_id);
            $product = Product::find($request->product_id);

            // Kiểm tra sản phẩm và đơn hàng
            if (!$order || !$product) {
                return response()->json(['message' => 'Order or Product not found'], 404);
            }

            // Tạo trả hàng
            $return = ReturnOrder::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity_returned' => $request->quantity,
                'status' => 'completed',
            ]);

            // Cập nhật kho
            $product->stock += $request->quantity;
            $product->save();

            // Commit transaction
            DB::commit();

            return response()->json(['message' => 'Return processed successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}