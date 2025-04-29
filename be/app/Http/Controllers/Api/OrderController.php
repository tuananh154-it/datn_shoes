<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderPlaced;
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
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Bạn cần đăng nhập để đặt hàng'], 401);
        }

        Log::info('Dữ liệu request trong placeOrder: ' . json_encode($request->all()));

        $request->validate([
            'username' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'payment_method' => 'required|in:cash_on_delivery,momo,zalopay',
            'voucher_id' => 'nullable|exists:vouchers,id',
            'note' => 'nullable|string',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'integer|exists:cart_items,id',
        ]);

        $user = $request->user();
        $selectedItemIds = $request->selected_items;

        $validItemCount = CartItem::whereIn('id', $selectedItemIds)
            ->whereHas('cart', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();

        if ($validItemCount !== count($selectedItemIds)) {
            return response()->json(['message' => 'Có sản phẩm không hợp lệ hoặc không thuộc quyền sở hữu'], 403);
        }

        $cart = Cart::with([
            'items' => function ($query) use ($selectedItemIds) {
                $query->whereIn('id', $selectedItemIds);
            },
            'items.productDetail.product'
        ])->where('user_id', $user->id)->first();

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

            $total_price = $total + $deliverFee;

            $order = Order::create([
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'user_id' => $user->id,
                'voucher_id' => $voucher->id ?? null,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $request->payment_method,
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

            // try {
            //     Log::info('Chuẩn bị gửi email cho đơn hàng #' . $order->id . ', trạng thái: ' . $order->status . ', email: ' . $request->email);
            //     $order->load('order_details.productDetail.product');
            //     Log::info('Dữ liệu đơn hàng sau load: ' . json_encode($order->toArray()));
            //     Mail::to($request->email)->send(new \App\Mail\OrderPlacedMail($order));
            //     Log::info('Email gửi thành công cho đơn hàng #' . $order->id);
            // } catch (\Exception $e) {
            //     Log::error('Lỗi gửi email xác nhận đơn hàng #' . $order->id . ': ' . $e->getMessage() . ' - Stack trace: ' . $e->getTraceAsString());
            // }

            DB::commit();

            broadcast(new OrderPlaced($order))->toOthers();

            return response()->json([
                'message' => 'Đặt hàng thành công',
                'order_id' => $order->id,
                'total' => $total_price,
                'discount' => $discount
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Lỗi đặt hàng: ' . $e->getMessage() . ' - Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Lỗi đặt hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listOrders(Request $request)
    {
        $user = $request->user();

        $orders = Order::with([
            'voucher',
            'order_details.productDetail.product',
            'order_details.productDetail.color',
            'order_details.productDetail.size'
        ])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedOrders = $orders->map(function ($order) {
            $orderDetails = $order->order_details->map(function ($orderDetail) {
                $productDetail = $orderDetail->productDetail;
                $imageBase64 = null;

                if ($productDetail->image) {
                    try {
                        $imagePath = json_decode($productDetail->image, true)[0] ?? null;
                        if ($imagePath) {
                            $absolutePath = storage_path('app/public/' . $imagePath);
                            Log::info('Đường dẫn ảnh tuyệt đối: ' . $absolutePath);
                            if (file_exists($absolutePath)) {
                                $imageContent = file_get_contents($absolutePath);
                                $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);
                                $mimeType = $extension === 'png' ? 'image/png' : 'image/jpeg';
                                $imageBase64 = "data:$mimeType;base64," . base64_encode($imageContent);
                            } else {
                                Log::error('File ảnh không tồn tại: ' . $absolutePath);
                            }
                        } else {
                            Log::error('Không tìm thấy đường dẫn ảnh trong product_detail.image: ' . $productDetail->image);
                        }
                    } catch (\Exception $e) {
                        Log::error('Lỗi chuyển ảnh thành Base64: ' . $e->getMessage());
                    }
                } else {
                    Log::warning('product_detail.image là null cho product_detail_id: ' . $productDetail->id);
                }

                return [
                    'id' => $orderDetail->id, // Thêm order_detail_id
                    'order_id' => $orderDetail->order_id, // Thêm order_id
                    'product_id' => $productDetail->product->id,
                    'product_detail_id' => $productDetail->id,
                    'product_name' => $productDetail->product->name,
                    'quantity' => $orderDetail->quantity,
                    'image' => $imageBase64,
                    'price' => $orderDetail->price,
                    'color' => $productDetail->color->name ?? null,
                    'size' => $productDetail->size->name ?? null,
                    'total_price' => $orderDetail->total_price,
                ];
            });

            $subtotal = $order->order_details->sum('total_price');
            $discount = 0;
            if ($order->voucher) {
                if ($order->voucher->discount_percent) {
                    $discount = $subtotal * ($order->voucher->discount_percent / 100);
                } elseif ($order->voucher->discount_amount) {
                    $discount = $order->voucher->discount_amount;
                }
                if ($order->voucher->max_discount_amount && $discount > $order->voucher->max_discount_amount) {
                    $discount = $order->voucher->max_discount_amount;
                }
            }

            return [
                'id' => $order->id,
                'username' => $order->username,
                'email' => $order->email,
                'phone_number' => $order->phone_number,
                'address' => $order->address,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'note' => $order->note,
                'deliver_fee' => $order->deliver_fee,
                'total_price' => $order->total_price,
                'discount' => $discount,
                'created_at' => $order->created_at,
                'voucher' => $order->voucher ? [
                    'id' => $order->voucher->id,
                    'name' => $order->voucher->name,
                    'discount_percent' => $order->voucher->discount_percent,
                    'discount_amount' => $order->voucher->discount_amount,
                ] : null,
                'order_details' => $orderDetails,
            ];
        });

        return response()->json($formattedOrders);
    }

    public function orderDetail($id, Request $request)
    {
        $user = $request->user();

        $order = Order::with([
            'order_details.productDetail',
            'order_details.productDetail.color',
            'order_details.productDetail.size',
            'voucher'
        ])
            ->where('user_id', $user->id)
            ->find($id);

        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }

        $formattedOrderDetails = $order->order_details->map(function ($orderDetail) {
            $productDetail = $orderDetail->productDetail;
            $imageBase64 = null;

            if ($productDetail->image) {
                try {
                    $imagePath = json_decode($productDetail->image, true)[0] ?? null;
                    if ($imagePath) {
                        $absolutePath = storage_path('app/public/' . $imagePath);
                        Log::info('Đường dẫn ảnh tuyệt đối: ' . $absolutePath);
                        if (file_exists($absolutePath)) {
                            $imageContent = file_get_contents($absolutePath);
                            $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);
                            $mimeType = $extension === 'png' ? 'image/png' : 'image/jpeg';
                            $imageBase64 = "data:$mimeType;base64," . base64_encode($imageContent);
                        } else {
                            Log::error('File ảnh không tồn tại: ' . $absolutePath);
                        }
                    } else {
                        Log::error('Không tìm thấy đường dẫn ảnh trong product_detail.image: ' . $productDetail->image);
                    }
                } catch (\Exception $e) {
                    Log::error('Lỗi chuyển ảnh thành Base64: ' . $e->getMessage());
                }
            } else {
                Log::warning('product_detail.image là null cho product_detail_id: ' . $productDetail->id);
            }

            return [
                'id' => $orderDetail->id,
                'order_id' => $orderDetail->order_id,
                'product_detail_id' => $productDetail->id,
                'product_id' => $productDetail->product->id,
                'product_name' => $productDetail->product->name,
                'quantity' => $orderDetail->quantity,
                'price' => $orderDetail->price,
                'total_price' => $orderDetail->total_price,
                'image' => $imageBase64,
                'color' => $productDetail->color ? $productDetail->color->name : null,
                'size' => $productDetail->size ? $productDetail->size->name : null,
            ];
        });

        $subtotal = $order->order_details->sum('total_price');
        $discount = 0;
        if ($order->voucher) {
            if ($order->voucher->discount_percent) {
                $discount = $subtotal * ($order->voucher->discount_percent / 100);
            } elseif ($order->voucher->discount_amount) {
                $discount = $order->voucher->discount_amount;
            }
            if ($order->voucher->max_discount_amount && $discount > $order->voucher->max_discount_amount) {
                $discount = $order->voucher->max_discount_amount;
            }
        }

        $formattedOrder = [
            'id' => $order->id,
            'username' => $order->username,
            'email' => $order->email,
            'phone_number' => $order->phone_number,
            'address' => $order->address,
            'user_id' => $order->user_id,
            'voucher_id' => $order->voucher_id,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'note' => $order->note,
            'deliver_fee' => $order->deliver_fee,
            'total_price' => $order->total_price,
            'discount' => $discount,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'deleted_at' => $order->deleted_at,
            'voucher' => $order->voucher ? [
                'id' => $order->voucher->id,
                'name' => $order->voucher->name,
                'discount_percent' => $order->voucher->discount_percent,
                'discount_amount' => $order->voucher->discount_amount,
            ] : null,
            'order_details' => $formattedOrderDetails,
        ];

        return response()->json($formattedOrder);
    }

    public function cancelOrder($id, Request $request)
    {
        $user = $request->user();

        $order = Order::where('user_id', $user->id)
            ->where('id', $id)
            ->whereIn('status', ['pending', 'confirmed', 'processing'])
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
            return response()->json(['message' => 'Bạn cần đăng nhập để xem giỏ hàng'], 401);
        }

        $cart = Cart::with([
            'items.productDetail.product',
            'items.productDetail.size',
            'items.productDetail.color'
        ])->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Giỏ hàng trống'], 400);
        }

        $subtotal = 0;
        $items = $cart->items->map(function ($item) use (&$subtotal) {
            $productDetail = $item->productDetail;
            $price = $productDetail->discount_price ?? $productDetail->default_price;
            $lineTotal = $price * $item->quantity;
            $subtotal += $lineTotal;

            $imageBase64 = null;
            if ($productDetail->image) {
                try {
                    $imagePath = json_decode($productDetail->image, true)[0] ?? null;
                    if ($imagePath) {
                        $absolutePath = storage_path('app/public/' . $imagePath);
                        Log::info('Đường dẫn ảnh tuyệt đối: ' . $absolutePath);
                        if (file_exists($absolutePath)) {
                            $imageContent = file_get_contents($absolutePath);
                            $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);
                            $mimeType = $extension === 'png' ? 'image/png' : 'image/jpeg';
                            $imageBase64 = "data:$mimeType;base64," . base64_encode($imageContent);
                        } else {
                            Log::error('File ảnh không tồn tại: ' . $absolutePath);
                        }
                    } else {
                        Log::error('Không tìm thấy đường dẫn ảnh trong product_detail.image: ' . $productDetail->image);
                    }
                } catch (\Exception $e) {
                    Log::error('Lỗi chuyển ảnh thành Base64: ' . $e->getMessage());
                }
            } else {
                Log::warning('product_detail.image là null cho product_detail_id: ' . $productDetail->id);
            }

            return [
                'id' => $item->id,
                'product_name' => $productDetail->product->name,
                'image' => $imageBase64,
                'size' => $productDetail->size->name ?? null,
                'color' => $productDetail->color->name ?? null,
                'price' => $price,
                'quantity' => $item->quantity,
                'line_total' => $lineTotal,
            ];
        });

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

            $imageBase64 = null;
            if ($productDetail->image) {
                try {
                    $imagePath = json_decode($productDetail->image, true)[0] ?? null;
                    if ($imagePath) {
                        $absolutePath = storage_path('app/public/' . $imagePath);
                        Log::info('Đường dẫn ảnh tuyệt đối: ' . $absolutePath);
                        if (file_exists($absolutePath)) {
                            $imageContent = file_get_contents($absolutePath);
                            $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);
                            $mimeType = $extension === 'png' ? 'image/png' : 'image/jpeg';
                            $imageBase64 = "data:$mimeType;base64," . base64_encode($imageContent);
                        } else {
                            Log::error('File ảnh không tồn tại: ' . $absolutePath);
                        }
                    } else {
                        Log::error('Không tìm thấy đường dẫn ảnh trong product_detail.image: ' . $productDetail->image);
                    }
                } catch (\Exception $e) {
                    Log::error('Lỗi chuyển ảnh thành Base64: ' . $e->getMessage());
                }
            } else {
                Log::warning('product_detail.image là null cho product_detail_id: ' . $productDetail->id);
            }

            return [
                'id' => $item->id,
                'product_name' => $productDetail->product->name,
                'image' => $imageBase64,
                'size' => $productDetail->size->name ?? null,
                'color' => $productDetail->color->name ?? null,
                'price' => $price,
                'quantity' => $item->quantity,
                'line_total' => $lineTotal,
            ];
        });

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