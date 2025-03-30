<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Voucher;
use Illuminate\Http\Request;

class OnlineCheckOutController extends Controller
{
    public function momo_payment(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Chưa đăng nhập'], 401);
        }

        // Lấy giỏ hàng của user
        $cart = Cart::with(['items.productDetail.product', 'items.productDetail.size', 'items.productDetail.color'])
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['error' => 'Giỏ hàng trống'], 400);
        }

        // Tính tổng tiền từ giỏ hàng
        $subtotal = 0;
        foreach ($cart->items as $item) {
            $price = $item->productDetail->discount_price ?? $item->productDetail->default_price;
            $subtotal += $price * $item->quantity;
        }

        // Kiểm tra voucher (nếu có)
        $voucherCode = $request->input('voucher');
        $discount = 0;

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
            }
        }

        $deliverFee = 30000;
        $total = $subtotal - $discount + $deliverFee;

        // Gọi API MoMo
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán giỏ hàng qua MoMo";
        $redirectUrl = "http://localhost:5173/checkout";
        $ipnUrl = "http://localhost:5173/checkout";
        $extraData = "";

        $orderId = time(); // Tạo order ID ngẫu nhiên
        $requestId = time() . "";
        $requestType = "payWithATM";

        // Tạo chữ ký HMAC SHA256
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $total . "&extraData=" . $extraData .
                   "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo .
                   "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl .
                   "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $total,
            'orderId' => $orderId,
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

        return response()->json(['payUrl' => $jsonResult['payUrl'] ?? null]);
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
