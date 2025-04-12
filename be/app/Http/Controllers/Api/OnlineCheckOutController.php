<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderPlaced;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OnlineCheckOutController extends Controller
{
    public function momo_payment(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Bạn cần đăng nhập để tiếp tục'], 401);
        }

        $request->validate([
            'username' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'voucher_code' => 'nullable|string|exists:vouchers,name',
            'note' => 'nullable|string',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'integer|exists:cart_items,id',
        ]);

        $selectedItemIds = $request->selected_items;

        $validItemCount = CartItem::whereIn('id', $selectedItemIds)
            ->whereHas('cart', fn($q) => $q->where('user_id', $user->id))
            ->count();

        if ($validItemCount !== count($selectedItemIds)) {
            return response()->json(['message' => 'Có sản phẩm không hợp lệ hoặc không thuộc quyền sở hữu'], 403);
        }

        $cart = Cart::with(['items' => fn($q) => $q->whereIn('id', $selectedItemIds), 'items.productDetail.product'])
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Không có sản phẩm nào được chọn để thanh toán'], 400);
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

                $total += $price * $item->quantity;
            }

            $voucher = null;

            if ($request->voucher_code) {
                Log::info('Kiểm tra mã giảm giá: ' . $request->voucher_code);
                $voucher = Voucher::where('name', $request->voucher_code)
                    ->where('status', 'active')
                    ->where('expiration_date', '>=', now())
                    ->where('quantity', '>', 0)
                    ->first();

                if (!$voucher) {
                    return response()->json(['message' => 'Mã giảm giá không hợp lệ hoặc đã hết lượt sử dụng'], 400);
                }

                $usedVoucher = Order::where('user_id', $user->id)
                    ->where('voucher_id', $voucher->id)
                    ->exists();

                if ($usedVoucher) {
                    return response()->json(['message' => 'Bạn đã sử dụng mã giảm giá này rồi'], 400);
                }

                if ($total < $voucher->min_purchase_amount) {
                    return response()->json(['message' => 'Không đủ điều kiện áp dụng mã giảm giá'], 400);
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

            $total_price = (int) ($total + $deliverFee);

            $order = Order::create([
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'user_id' => $user->id,
                'voucher_id' => $voucher->id ?? null,
                'status' => 'pending',
                'payment_status' => 'paid',
                'payment_method' => 'momo',
                'note' => $request->note,
                'deliver_fee' => $deliverFee,
                'total_price' => $total_price,
            ]);

            if ($voucher) {
                $voucher->decrement('quantity', 1);
            }

            foreach ($cart->items as $item) {
                $productDetail = $item->productDetail;
                $price = $productDetail->discount_price ?? $productDetail->default_price;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_detail_id' => $item->product_detail_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'total_price' => $price * $item->quantity,
                ]);
                $productDetail->decrement('quantity', $item->quantity);
            }

            $cart->items()->whereIn('id', $selectedItemIds)->delete();

            DB::commit();

            try {
                Mail::to($request->email)->send(new \App\Mail\OrderPlacedMail($order->load('order_details.productDetail.product')));
            } catch (\Exception $e) {
                Log::error('Lỗi gửi email xác nhận đơn hàng: ' . $e->getMessage());
            }

            // Gọi API MoMo
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $partnerCode = 'MOMOBKUN20180529';
            $accessKey = 'klm05TvNBzhg7h7j';
            $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
            $orderInfo = "Thanh toán đơn hàng #{$order->id} qua MoMo";
            $redirectUrl = "http://localhost:5173/myaccout?tab=orders";
            $ipnUrl = "https://your-ngrok-url.ngrok.io/api/momo/ipn";
            $extraData = "orderId={$order->id}";
            $requestId = Str::uuid()->toString();
            $requestType = "payWithATM";

            $orderIdMomo = $order->id . '-' . Str::uuid();

            $rawHash = "accessKey={$accessKey}"
                . "&amount={$total_price}"
                . "&extraData={$extraData}"
                . "&ipnUrl={$ipnUrl}"
                . "&orderId={$orderIdMomo}"
                . "&orderInfo={$orderInfo}"
                . "&partnerCode={$partnerCode}"
                . "&redirectUrl={$redirectUrl}"
                . "&requestId={$requestId}"
                . "&requestType={$requestType}";

            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            $data = [
                'partnerCode' => $partnerCode,
                'partnerName' => "MoMoTest",
                'storeId' => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $total_price,
                'orderId' => $orderIdMomo,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            ];

            $result = $this->execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

            if (!isset($jsonResult['payUrl'])) {
                return response()->json([
                    'message' => 'MoMo không trả về liên kết thanh toán',
                    'momo_response' => $jsonResult,
                ], 500);
            }

            return response()->json([
                'payUrl' => $jsonResult['payUrl'],
                'order_id' => $order->id,
                'total' => $total_price,
                'discount' => $discount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi xử lý thanh toán MoMo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}