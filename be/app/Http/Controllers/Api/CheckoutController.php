<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Order, Payment, ProductDetail};
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.product_detail_id' => 'required|exists:product_details,id',
            'products.*.quantity' => 'required|integer|min:1',
            'voucher_id' => 'nullable|exists:vouchers,id',
            'payment_method' => 'required|in:COD,VnPAY,zaloPay',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email',
            'note' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $user_id = $request->user_id;
            $voucher = null;
            $discount = 0;
            $subtotal = 0;

            // Tính tổng tiền và kiểm tra tồn kho
            $orderItems = collect();
            foreach ($request->products as $item) {
                $productDetail = ProductDetail::with('product')->findOrFail($item['product_detail_id']);

                if ($productDetail->quantity < $item['quantity']) {
                    return response()->json([
                        'message' => 'Not enough stock for product: ' . $productDetail->product->name,
                    ], 400);
                }

                $price = $productDetail->discount_price ?? $productDetail->default_price;
                $total_price = $price * $item['quantity'];
                $subtotal += $total_price;

                $orderItems->push([
                    'product_detail_id' => $productDetail->id,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'total_price' => $total_price,
                ]);
            }

            // Tính giảm giá nếu có voucher
           
$discount = 0;

if ($request->voucher_id) {
    // Dùng Eloquent thay vì DB::table để có support quan hệ và tự động ánh xạ object
    $voucher = Voucher::find($request->voucher_id);

    if (
        $voucher &&
        $voucher->status === 'active' &&
        $subtotal >= $voucher->min_purchase_amount
    ) {
        if (!is_null($voucher->discount_percent)) {
            $discount = ($subtotal * $voucher->discount_percent) / 100;
            $discount = min($discount, $voucher->max_discount_amount);
        } elseif (!is_null($voucher->discount_amount)) {
            $discount = min($voucher->discount_amount, $voucher->max_discount_amount);
        }
    }
}

            $deliver_fee = 20000; // Phí giao hàng cố định, có thể thay đổi
            $total_price = $subtotal + $deliver_fee - $discount;

            // Tạo đơn hàng
            $order = Order::create([
                'username' => 'User_' . $user_id,
                'voucher_id' => $request->voucher_id,
                'status' => 'waiting_for_confirmation',
                'deliver_fee' => $deliver_fee,
                'user_id' => $user_id,
                'payment_status' => 'unpaid',
                'payment_method' => $request->payment_method,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'total_price' => $total_price,
                'note' => $request->note,
            ]);

            // Lưu chi tiết đơn hàng và trừ kho
            foreach ($orderItems as $item) {
                $order->orderDetails()->create($item);
                ProductDetail::where('id', $item['product_detail_id'])
                    ->decrement('quantity', $item['quantity']);
            }

            // Cập nhật trạng thái đơn hàng nếu thanh toán ngay (COD = unpaid)
            if ($request->payment_method !== 'COD') {
                $order->payment_status = 'paid';
                $order->save();
            }

            // Tạo thanh toán
            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => $user_id,
                'payment_method' => $request->payment_method,
                'amount' => $total_price,
                'status' => $request->payment_method === 'COD' ? 'pending' : 'completed',
                'payment_date' => Carbon::now(),
                'note' => $request->note,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Checkout successful',
                'order' => [
                    'id' => $order->id,
                    'total_price' => $order->total_price,
                    'payment_status' => $order->payment_status,
                    'products' => $order->orderDetails->map(function ($item) {
                        return [
                            'name' => $item->productDetail->product->name,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                        ];
                    })
                ]
            ], 201);
            

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Checkout failed', 'error' => $e->getMessage()], 500);
        }
    }
}
