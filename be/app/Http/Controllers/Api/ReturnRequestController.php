<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReturnRequestDetailResource;
use App\Http\Resources\ReturnRequestResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductDetail;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReturnRequestController extends Controller
{
    private function applyRoleFilters($query, $user)
    {
        if ($user->role === 'staff') {
            // Nhân viên CSKH chỉ thấy yêu cầu với trạng thái 'pending', 'reviewed', 'rejected'
            $query->whereIn('status', ['pending', 'reviewed', 'rejected']);
        }

        if ($user->role === 'admin' || $user->role === 'superadmin') {
            // Admin có thể xem các yêu cầu đã được review, approve hoặc rejected
            $query->whereIn('status', ['reviewed', 'approved', 'rejected']);
        }

        if ($user->role === 'user') {
            // Người dùng chỉ có thể xem yêu cầu của chính mình
            $query->where('user_id', $user->id);
        }
    }

    // 1. Danh sách yêu cầu (cho CSKH hoặc admin hoặc khách hàng)
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $query = ReturnRequest::with([
                'user',
                'order',
                'reviewer',
                'admin',
            ]);

            // Lọc theo quyền truy cập người dùng
            $this->applyRoleFilters($query, $user);

            // Lọc theo trạng thái nếu có
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Lọc theo order_id nếu có
            if ($request->has('order_id')) {
                $query->where('order_id', $request->order_id);
            }

            // Lọc theo order_detail_id nếu có
            if ($request->has('order_detail_id')) {
                $query->where('order_detail_id', $request->order_detail_id);
            }

            $query->orderByRaw("FIELD(status, 'pending', 'reviewed') DESC");

            $query->latest();

            return ReturnRequestResource::collection($query->paginate(15));
        } catch (\Exception $e) {
            Log::error('Lỗi lấy danh sách yêu cầu hoàn: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi khi lấy danh sách yêu cầu hoàn', 'error' => $e->getMessage()], 500);
        }
    }


    // Chi tiết yêu cầu hoàn
    public function show($id)
    {
        try {
            $user = Auth::user();

            $returnRequest = ReturnRequest::with([
                'orderDetail.productDetail.product',
                'orderDetail.productDetail.size',
                'orderDetail.productDetail.color'
            ])
                ->where('id', $id)
                ->first();

            if (!$returnRequest) {
                return response()->json(['message' => 'Không tìm thấy yêu cầu hoàn trả với review ID này'], 404);
            }

            $status = $returnRequest->status;

            $canView = match ($user->role) {
                'user' => $returnRequest->user_id === $user->id,
                'staff' => in_array($status, ['pending', 'reviewed', 'rejected']),
                'admin', 'superadmin' => in_array($status, ['reviewed', 'approved', 'rejected']),
                default => false,
            };

            if (!$canView) {
                return response()->json(['message' => 'Bạn không có quyền xem yêu cầu này'], 403);
            }

            $product = [
                'product_id' => $returnRequest->orderDetail->productDetail->product->id ?? null,
                'product_name' => $returnRequest->orderDetail->productDetail->product->name ?? 'N/A',
                'product_image' => $returnRequest->orderDetail->productDetail->product->image ?? 'null',
                'size' => $returnRequest->orderDetail->productDetail->size->name ?? 'N/A',
                'color' => $returnRequest->orderDetail->productDetail->color->name ?? 'N/A',
                'product_price' => $returnRequest->orderDetail->price,
                'quantity' => $returnRequest->orderDetail->quantity,
                'total_price' => $returnRequest->orderDetail->total_price,
            ];

            return response()->json([
                'id' => $id,
                'product' => $product,
                'reason' => [
                    'reason' => $returnRequest->reason,
                    'description' => $returnRequest->description,
                    'bank_account' => $returnRequest->bank_account,
                ],
                'all_total' => $returnRequest->orderDetail->total_price,
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi khi lấy thông tin yêu cầu hoàn'], 500);
        }
    }

    // 2. Tạo mới yêu cầu hoàn hàng (cho khách hàng)
    public function store(Request $request, $orderId)
    {
        try {
            $request->validate([
                'order_detail' => 'required|array',
                'order_detail.*.id' => 'required|exists:order_details,id',
                'order_detail.*.reason' => 'required|string|max:255',
                'order_detail.*.image' => 'nullable|string|max:2048',
                'order_detail.*.bank_account' => 'required|string|max:255',
                'order_detail.*.description' => 'nullable|string',
            ]);

            // Kiểm tra đơn hàng
            $order = Order::find($orderId);
            if (!$order || $order->user_id !== Auth::id()) {
                return response()->json(['message' => 'Đơn hàng không tồn tại hoặc không thuộc về bạn'], 403);
            }

            $orderDetails = $order->orderDetails;
            $delivered = $orderDetails->every(function ($detail) {
                return $detail->order->status === 'delivered';
            });

            if (!$delivered) {
                return response()->json(['message' => 'Đơn hàng phải được giao thành công để yêu cầu hoàn trả'], 422);
            }

            // Kiểm tra yêu cầu hoàn trả đã tồn tại
            $existingReturnRequest = ReturnRequest::where('order_id', $orderId)
                ->where(function ($query) {
                    $query->whereNotNull('status')
                        ->where('status', '!=', 'rejected');
                })
                ->exists();

            if ($existingReturnRequest) {
                return response()->json(['message' => 'Đơn hàng này đang chờ được xem xét hoặc đã hoàn thành công'], 422);
            }

            // Tiến hành tạo yêu cầu hoàn trả cho từng sản phẩm
            $returnRequests = collect($request->order_detail)->map(function ($product) use ($order) {
                $orderDetail = $order->orderDetails->firstWhere('id', $product['id']);

                $imagePath = null;
                if (isset($product['image'])) {
                    $imageData = base64_decode($product['image']);
                    $imageName = 'return_' . uniqid() . '.jpg';
                    $imagePath = 'return_images/' . $imageName;

                    Storage::disk('public')->put($imagePath, $imageData);
                }

                // Tạo yêu cầu hoàn trả
                return ReturnRequest::create([
                    'order_id' => $order->id,
                    'order_detail_id' => $orderDetail->id,
                    'user_id' => Auth::id(),
                    'reason' => $product['reason'],
                    'description' => $product['description'] ?? null,
                    'image' => $imagePath,
                    'bank_account' => $product['bank_account'],
                    'status' => 'pending',
                    'requested_at' => now(),
                ]);
            });

            return response()->json(['return_requests' => $returnRequests, 'message' => 'Yêu cầu hoàn trả đã được gửi thành công'], 201);

        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo yêu cầu hoàn: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi khi gửi yêu cầu hoàn hàng'], 500);
        }
    }


    public function acceptReview(Request $request, $id)
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'staff') {
                return response()->json(['message' => 'Bạn không có quyền xử lý yêu cầu này'], 403);
            }

            $returnRequest = ReturnRequest::findOrFail($id);

            if (in_array($returnRequest->status, ['approved', 'rejected'])) {
                return response()->json(['message' => 'Yêu cầu này đã được xử lý hoàn tất'], 422);
            }

            $returnRequest->update([
                'status' => 'reviewed',
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
                'staff_notes' => $request->input('staff_notes'),
            ]);

            return response()->json([
                'message' => 'Đã tiếp nhận yêu cầu hoàn',
                'data' => $returnRequest
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi tiếp nhận xử lý hoàn: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi khi tiếp nhận xử lý yêu cầu hoàn'], 500);
        }
    }

    public function rejectReview(Request $request, $id)
    {
        try {
            $user = Auth::user();
            if ($user->role !== 'staff') {
                return response()->json(['message' => 'Bạn không có quyền từ chối yêu cầu này'], 403);
            }

            $request->validate([
                'reason' => 'nullable|string|max:255',
            ]);

            $returnRequest = ReturnRequest::findOrFail($id);

            if (in_array($returnRequest->status, ['approved', 'rejected'])) {
                return response()->json(['message' => 'Yêu cầu này đã được xử lý hoàn tất'], 422);
            }

            $returnRequest->update([
                'status' => 'rejected',
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
                'staff_notes' => "[Từ chối xử lý]: " . $request->reason,
            ]);

            return response()->json([
                'message' => 'Đã từ chối xử lý yêu cầu hoàn',
                'data' => $returnRequest
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi từ chối xử lý hoàn: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi khi từ chối xử lý yêu cầu hoàn'], 500);
        }
    }

    public function approveReturn($id)
    {
        try {
            $user = Auth::user();

            if ($user->role !== 'admin' || $user->role !== 'superadmin') {
                return response()->json(['message' => 'Bạn không có quyền duyệt yêu cầu này'], 403);
            }

            $returnRequest = ReturnRequest::findOrFail($id);

            if ($returnRequest->status !== 'reviewed') {
                return response()->json(['message' => 'Yêu cầu chưa được nhân viên xem xét hoặc đã xử lý xong'], 422);
            }

            $returnRequest->update([
                'status' => 'approved',
                'admin_id' => $user->id,
                'admin_approved_at' => now(),
            ]);

            $returnRequest->order->update([
                'status' => 'returned',
            ]);

            return response()->json([
                'message' => 'Yêu cầu đã được duyệt',
                'data' => $returnRequest
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi duyệt yêu cầu hoàn: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi khi duyệt yêu cầu hoàn'], 500);
        }
    }

    public function rejectReturn(Request $request, $id)
    {
        try {
            $user = Auth::user();

            if ($user->role !== 'admin' || $user->role !== 'superadmin') {
                return response()->json(['message' => 'Bạn không có quyền từ chối yêu cầu này'], 403);
            }

            $request->validate([
                'reason' => 'nullable|string|max:255',
            ]);

            $returnRequest = ReturnRequest::findOrFail($id);

            if ($returnRequest->status !== 'reviewed') {
                return response()->json(['message' => 'Yêu cầu chưa được nhân viên xem xét hoặc đã xử lý xong'], 422);
            }

            $returnRequest->update([
                'status' => 'rejected',
                'admin_id' => $user->id,
                'admin_approved_at' => now(),
                'staff_notes' => $returnRequest->staff_notes . "\n[Admin từ chối]: " . $request->reason,
            ]);

            return response()->json([
                'message' => 'Yêu cầu đã bị từ chối',
                'data' => $returnRequest
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi từ chối yêu cầu hoàn: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi khi từ chối yêu cầu hoàn'], 500);
        }
    }

    public function receiveReturn($id)
    {
        try {
            $user = Auth::user();

            if (!in_array($user->role, ['staff', 'admin'])) {
                return response()->json(['message' => 'Không có quyền nhận hàng hoàn'], 403);
            }

            $returnRequest = ReturnRequest::with('order.orderDetails')->findOrFail($id);

            if ($returnRequest->return_received_at) {
                return response()->json(['message' => 'Đơn hoàn này đã được xác nhận nhận hàng trước đó'], 422);
            }

            if ($returnRequest->status !== 'approved') {
                return response()->json(['message' => 'Chỉ được nhận hàng hoàn sau khi admin đã duyệt'], 422);
            }

            foreach ($returnRequest->order->orderDetails as $detail) {
                // Lấy product detail
                $productDetail = ProductDetail::find($detail->order_detail_id);
                if ($productDetail) {
                    $productDetail->increment('quantity', $detail->quantity);
                }
            }

            $returnRequest->update([
                'return_received_at' => now(),
            ]);

            return response()->json(['message' => 'Đã nhận hàng hoàn và cập nhật kho']);
        } catch (\Exception $e) {
            Log::error('Nhận hàng hoàn lỗi: ' . $e->getMessage());
            return response()->json(['message' => 'Có lỗi khi xử lý hàng hoàn'], 500);
        }
    }
}
