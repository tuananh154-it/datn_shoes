<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderPlaced;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Voucher;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OnlineCheckOutController extends Controller
{
    // public function momo_payment(Request $request)
    // {
    //     $user = $request->user();

    //     if (!$user) {
    //         return response()->json(['error' => 'Bạn cần đăng nhập để tiếp tục'], 401);
    //     }

    //     $request->validate([
    //         'username' => 'required|string',
    //         'email' => 'required|email',
    //         'phone_number' => 'required|string',
    //         'address' => 'required|string',
    //         'voucher_code' => 'nullable|string|exists:vouchers,name',
    //         'note' => 'nullable|string',
    //         'selected_items' => 'required|array|min:1',
    //         'selected_items.*' => 'integer|exists:cart_items,id',
    //     ]);

    //     $selectedItemIds = $request->selected_items;

    //     $validItemCount = CartItem::whereIn('id', $selectedItemIds)
    //         ->whereHas('cart', fn($q) => $q->where('user_id', $user->id))
    //         ->count();

    //     if ($validItemCount !== count($selectedItemIds)) {
    //         return response()->json(['message' => 'Có sản phẩm không hợp lệ hoặc không thuộc quyền sở hữu'], 403);
    //     }

    //     $cart = Cart::with(['items' => fn($q) => $q->whereIn('id', $selectedItemIds), 'items.productDetail.product'])
    //         ->where('user_id', $user->id)
    //         ->first();

    //     if (!$cart || $cart->items->isEmpty()) {
    //         return response()->json(['message' => 'Không có sản phẩm nào được chọn để thanh toán'], 400);
    //     }

    //     try {
    //         $total = 0;
    //         $deliverFee = 30000;
    //         $discount = 0;
    //         $orderItems = [];

    //         // Kiểm tra số lượng sản phẩm và tính tổng giá
    //         foreach ($cart->items as $item) {
    //             $productDetail = $item->productDetail;
    //             $price = $productDetail->discount_price ?? $productDetail->default_price;

    //             if ($productDetail->quantity < $item->quantity) {
    //                 return response()->json([
    //                     'message' => 'Sản phẩm "' . $productDetail->product->name . '" không đủ số lượng'
    //                 ], 400);
    //             }

    //             $total += $price * $item->quantity;
    //             $orderItems[] = [
    //                 'product_detail_id' => $item->product_detail_id,
    //                 'quantity' => $item->quantity,
    //                 'price' => $price,
    //                 'total_price' => $price * $item->quantity,
    //             ];
    //         }

    //         $voucher = null;
    //         $voucher_id = null;

    //         // Xử lý voucher
    //         if ($request->voucher_code) {
    //             Log::info('Kiểm tra mã giảm giá: ' . $request->voucher_code);
    //             $voucher = Voucher::where('name', $request->voucher_code)
    //                 ->where('status', 'active')
    //                 ->where('expiration_date', '>=', now())
    //                 ->where('quantity', '>', 0)
    //                 ->first();

    //             if (!$voucher) {
    //                 return response()->json(['message' => 'Mã giảm giá không hợp lệ hoặc đã hết lượt sử dụng'], 400);
    //             }

    //             $usedVoucher = Order::where('user_id', $user->id)
    //                 ->where('voucher_id', $voucher->id)
    //                 ->exists();

    //             if ($usedVoucher) {
    //                 return response()->json(['message' => 'Bạn đã sử dụng mã giảm giá này rồi'], 400);
    //             }

    //             if ($total < $voucher->min_purchase_amount) {
    //                 return response()->json(['message' => 'Không đủ điều kiện áp dụng mã giảm giá'], 400);
    //             }

    //             if ($voucher->discount_percent) {
    //                 $discount = $total * ($voucher->discount_percent / 100);
    //             } elseif ($voucher->discount_amount) {
    //                 $discount = $voucher->discount_amount;
    //             }

    //             if ($discount > $voucher->max_discount_amount) {
    //                 $discount = $voucher->max_discount_amount;
    //             }

    //             $total -= $discount;
    //             $voucher_id = $voucher->id;
    //         }

    //         $total_price = (int) ($total + $deliverFee);

    //         // Lưu thông tin đơn hàng tạm thời vào cache
    //         $tempOrderId = Str::uuid()->toString();
    //         $tempOrderData = [
    //             'username' => $request->username,
    //             'email' => $request->email,
    //             'phone_number' => $request->phone_number,
    //             'address' => $request->address,
    //             'user_id' => $user->id,
    //             'voucher_id' => $voucher_id,
    //             'note' => $request->note,
    //             'deliver_fee' => $deliverFee,
    //             'total_price' => $total_price,
    //             'items' => $orderItems,
    //             'cart_item_ids' => $selectedItemIds,
    //         ];

    //         // Lưu vào cache với thời hạn 15 phút
    //         Cache::put("temp_order_{$tempOrderId}", $tempOrderData, now()->addMinutes(15));

    //         // Gọi API MoMo
    //         $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    //         $partnerCode = 'MOMOBKUN20180529';
    //         $accessKey = 'klm05TvNBzhg7h7j';
    //         $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
    //         $orderInfo = "Thanh toán đơn hàng tạm #{$tempOrderId} qua MoMo";
    //         $redirectUrl = "http://localhost:5173/myaccout?tab=orders";
    //         $ipnUrl = "https://02a6-2402-800-61ae-bde6-bc82-2750-204f-a028.ngrok-free.app/api/momo/ipn";
    //         $extraData = "tempOrderId={$tempOrderId}";
    //         $requestId = Str::uuid()->toString();
    //         $requestType = "payWithATM";

    //         $orderIdMomo = $tempOrderId . '-' . Str::uuid();

    //         $rawHash = "accessKey={$accessKey}"
    //             . "&amount={$total_price}"
    //             . "&extraData={$extraData}"
    //             . "&ipnUrl={$ipnUrl}"
    //             . "&orderId={$orderIdMomo}"
    //             . "&orderInfo={$orderInfo}"
    //             . "&partnerCode={$partnerCode}"
    //             . "&redirectUrl={$redirectUrl}"
    //             . "&requestId={$requestId}"
    //             . "&requestType={$requestType}";

    //         $signature = hash_hmac("sha256", $rawHash, $secretKey);

    //         $data = [
    //             'partnerCode' => $partnerCode,
    //             'partnerName' => "MoMoTest",
    //             'storeId' => "MomoTestStore",
    //             'requestId' => $requestId,
    //             'amount' => $total_price,
    //             'orderId' => $orderIdMomo,
    //             'orderInfo' => $orderInfo,
    //             'redirectUrl' => $redirectUrl,
    //             'ipnUrl' => $ipnUrl,
    //             'lang' => 'vi',
    //             'extraData' => $extraData,
    //             'requestType' => $requestType,
    //             'signature' => $signature
    //         ];

    //         $result = $this->execPostRequest($endpoint, json_encode($data));
    //         $jsonResult = json_decode($result, true);

    //         if (!isset($jsonResult['payUrl'])) {
    //             Cache::forget("temp_order_{$tempOrderId}");
    //             return response()->json([
    //                 'message' => 'MoMo không trả về liên kết thanh toán',
    //                 'momo_response' => $jsonResult,
    //             ], 500);
    //         }

    //         return response()->json([
    //             'payUrl' => $jsonResult['payUrl'],
    //             'temp_order_id' => $tempOrderId,
    //             'total' => $total_price,
    //             'discount' => $discount
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Đã xảy ra lỗi khi xử lý thanh toán MoMo',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

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

  try {
    $total = 0;
    $deliverFee = 30000;
    $discount = 0;
    $orderItems = [];

    foreach ($cart->items as $item) {
      $productDetail = $item->productDetail;
      $price = $productDetail->discount_price ?? $productDetail->default_price;

      if ($productDetail->quantity < $item->quantity) {
        return response()->json([
          'message' => 'Sản phẩm "' . $productDetail->product->name . '" không đủ số lượng'
        ], 400);
      }

      $total += $price * $item->quantity;
      $orderItems[] = [
        'product_detail_id' => $item->product_detail_id,
        'quantity' => $item->quantity,
        'price' => $price,
        'total_price' => $price * $item->quantity,
      ];
    }

    $voucher = null;
    $voucher_id = null;

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
      $voucher_id = $voucher->id;
    }

    $total_price = (int) ($total + $deliverFee);

    // Lưu thông tin đơn hàng tạm thời vào cache
    $tempOrderId = Str::uuid()->toString();
    $tempOrderData = [
      'username' => $request->username,
      'email' => $request->email,
      'phone_number' => $request->phone_number,
      'address' => $request->address,
      'user_id' => $user->id,
      'voucher_id' => $voucher_id,
      'note' => $request->note,
      'deliver_fee' => $deliverFee,
      'total_price' => $total_price,
      'items' => $orderItems,
      'cart_item_ids' => $selectedItemIds,
      'cancel_redirect_url' => 'http://localhost:5173/checkout', // Thêm URL checkout
    ];

    Cache::put("temp_order_{$tempOrderId}", $tempOrderData, now()->addMinutes(15));

    // Lấy URL ngrok động
    $ngrokApiUrl = 'http://localhost:4040/api/tunnels';
    $response = file_get_contents($ngrokApiUrl);
    $data = json_decode($response, true);
    $ngrokUrl = $data['tunnels'][0]['public_url'] ?? null;

    if (!$ngrokUrl) {
      Cache::forget("temp_order_{$tempOrderId}");
      return response()->json(['message' => 'Không thể lấy URL ngrok'], 500);
    }

    // Gọi API MoMo
    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    $partnerCode = 'MOMOBKUN20180529';
    $accessKey = 'klm05TvNBzhg7h7j';
    $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
    $orderInfo = "Thanh toán đơn hàng tạm #{$tempOrderId} qua MoMo";
    $redirectUrl = "http://localhost:5173/myaccout?tab=orders";
    $ipnUrl = "$ngrokUrl/api/momo/ipn"; // Sử dụng URL ngrok động
    $extraData = "tempOrderId={$tempOrderId}";
    $requestId = Str::uuid()->toString();
    $requestType = "payWithATM";

    $orderIdMomo = $tempOrderId . '-' . Str::uuid();

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
      Cache::forget("temp_order_{$tempOrderId}");
      return response()->json([
        'message' => 'MoMo không trả về liên kết thanh toán',
        'momo_response' => $jsonResult,
      ], 500);
    }

    return response()->json([
      'payUrl' => $jsonResult['payUrl'],
      'temp_order_id' => $tempOrderId,
      'total' => $total_price,
      'discount' => $discount
    ]);
  } catch (\Exception $e) {
    return response()->json([
      'message' => 'Đã xảy ra lỗi khi xử lý thanh toán MoMo',
      'error' => $e->getMessage()
    ], 500);
  }
}
    public function momoIpn(Request $request)
    {
        $data = $request->all();
        Log::info('MoMo IPN received: ' . json_encode($data));

        // Xác thực chữ ký từ MoMo
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $rawHash = "accessKey=klm05TvNBzhg7h7j"
            . "&amount={$data['amount']}"
            . "&extraData={$data['extraData']}"
            . "&message={$data['message']}"
            . "&orderId={$data['orderId']}"
            . "&orderInfo={$data['orderInfo']}"
            . "&orderType={$data['orderType']}"
            . "&partnerCode={$data['partnerCode']}"
            . "&payType={$data['payType']}"
            . "&requestId={$data['requestId']}"
            . "&responseTime={$data['responseTime']}"
            . "&resultCode={$data['resultCode']}"
            . "&transId={$data['transId']}";

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        if ($signature !== $data['signature']) {
            Log::error('MoMo IPN: Invalid signature');
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        // Lấy tempOrderId từ extraData
        parse_str($data['extraData'], $extraData);
        $tempOrderId = $extraData['tempOrderId'] ?? null;

        if (!$tempOrderId) {
            Log::error('MoMo IPN: tempOrderId not found in extraData');
            return response()->json(['message' => 'tempOrderId not found'], 400);
        }

        // Lấy dữ liệu tạm từ cache
        $tempOrderData = Cache::get("temp_order_{$tempOrderId}");

        if (!$tempOrderData) {
            Log::error('MoMo IPN: Temporary order data not found, tempOrderId: ' . $tempOrderId);
            return response()->json(['message' => 'Temporary order data not found'], 404);
        }

        // Kiểm tra kết quả thanh toán
        if ($data['resultCode'] == 0) {
            DB::beginTransaction();
            try {
                // Kiểm tra lại số lượng sản phẩm
                foreach ($tempOrderData['items'] as $item) {
                    $productDetail = ProductDetail::find($item['product_detail_id']);
                    if (!$productDetail || $productDetail->quantity < $item['quantity']) {
                        Cache::forget("temp_order_{$tempOrderId}");
                        DB::rollBack();
                        Log::error('MoMo IPN: Insufficient product quantity for product_detail_id: ' . $item['product_detail_id']);
                        return response()->json(['message' => 'Insufficient product quantity'], 400);
                    }
                }

                // Kiểm tra lại voucher
                $voucher = null;
                if ($tempOrderData['voucher_id']) {
                    $voucher = Voucher::where('id', $tempOrderData['voucher_id'])
                        ->where('status', 'active')
                        ->where('expiration_date', '>=', now())
                        ->where('quantity', '>', 0)
                        ->first();

                    if (!$voucher) {
                        Cache::forget("temp_order_{$tempOrderId}");
                        DB::rollBack();
                        Log::error('MoMo IPN: Invalid or expired voucher, voucher_id: ' . $tempOrderData['voucher_id']);
                        return response()->json(['message' => 'Invalid or expired voucher'], 400);
                    }
                }

                // Tạo đơn hàng
                $order = Order::create([
                    'username' => $tempOrderData['username'],
                    'email' => $tempOrderData['email'],
                    'phone_number' => $tempOrderData['phone_number'],
                    'address' => $tempOrderData['address'],
                    'user_id' => $tempOrderData['user_id'],
                    'voucher_id' => $tempOrderData['voucher_id'],
                    'status' => 'pending',
                    'payment_status' => 'paid',
                    'payment_method' => 'momo',
                    'note' => $tempOrderData['note'],
                    'deliver_fee' => $tempOrderData['deliver_fee'],
                    'total_price' => $tempOrderData['total_price'],
                ]);

                // Tạo chi tiết đơn hàng và cập nhật số lượng
                foreach ($tempOrderData['items'] as $item) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_detail_id' => $item['product_detail_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total_price' => $item['total_price'],
                    ]);
                    ProductDetail::where('id', $item['product_detail_id'])
                        ->decrement('quantity', $item['quantity']);
                }

                // Cập nhật số lượng voucher
                if ($voucher) {
                    $voucher->decrement('quantity', 1);
                }

                // Xóa cart_items
                CartItem::whereIn('id', $tempOrderData['cart_item_ids'])->delete();

                // Lưu order_id vào cache để sử dụng trong confirmOrder
                Cache::put("order_confirm_{$tempOrderId}", ['order_id' => $order->id], now()->addMinutes(15));

                // Xóa dữ liệu tạm
                Cache::forget("temp_order_{$tempOrderId}");

                DB::commit();
                // broadcast(new OrderPlaced($order));
                // Phát sự kiện OrderPlaced
                // try {
                //         broadcast(new OrderPlaced($order));
                // } catch (\Exception $e) {
                //     Log::error('Failed to broadcast OrderPlaced event: ' . $e->getMessage());
                // }

                return response()->json(['message' => 'IPN processed successfully'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('MoMo IPN: Error processing order, tempOrderId: ' . $tempOrderId . ', error: ' . $e->getMessage());
                return response()->json(['message' => 'Error processing order'], 500);
            }
        } else {
            // Thanh toán thất bại, xóa dữ liệu tạm
            Cache::forget("temp_order_{$tempOrderId}");
            Log::warning('MoMo IPN: Payment failed for tempOrderId #' . $tempOrderId . ', resultCode: ' . $data['resultCode']);
            return response()->json(['message' => 'Payment failed'], 200);
        }
    }

    public function cancelTempOrder(Request $request)
    {
        $request->validate([
            'temp_order_id' => 'required|string',
        ]);

        $tempOrderId = $request->temp_order_id;

        if (Cache::has("temp_order_{$tempOrderId}")) {
            Cache::forget("temp_order_{$tempOrderId}");
            return response()->json(['message' => 'Đã hủy đơn hàng tạm thời'], 200);
        }

        return response()->json(['message' => 'Đơn hàng tạm thời không tồn tại'], 404);
    }

    public function confirmOrder(Request $request)
{
    Log::info('confirmOrder called with temp_order_id: ' . $request->temp_order_id);
    $request->validate([
        'temp_order_id' => 'required|string',
    ]);

    $tempOrderId = $request->temp_order_id;

    // Lấy order_id từ cache
    $orderData = Cache::get("order_confirm_{$tempOrderId}");

    if (!$orderData || !isset($orderData['order_id'])) {
        return response()->json(['message' => 'Đơn hàng không tồn tại hoặc đã hết hạn'], 404);
    }

    $order = Order::find($orderData['order_id']);

    if (!$order) {
        Cache::forget("order_confirm_{$tempOrderId}");
        return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
    }

    try {
        // Phát sự kiện OrderPlaced
        broadcast(new OrderPlaced($order));
        // Xóa cache sau khi phát sự kiện
        Cache::forget("order_confirm_{$tempOrderId}");
        return response()->json(['message' => 'Thông báo đơn hàng đã được gửi'], 200);
    } catch (\Exception $e) {
        Log::error('Failed to broadcast OrderPlaced event: ' . $e->getMessage());
        return response()->json(['message' => 'Lỗi khi gửi thông báo đơn hàng'], 500);
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