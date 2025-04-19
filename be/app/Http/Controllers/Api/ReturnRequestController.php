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

class ReturnRequestController extends Controller
{
    // 1. Danh sách yêu cầu (cho CSKH hoặc admin hoặc khách hàng)
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            $query = ReturnRequest::with(['user', 'order', 'reviewer', 'admin']);

            // Nếu là nhân viên CSKH: chỉ thấy đơn 'pending'
            if ($user->role === 'staff') {
                $query->whereIn('status', ['pending', 'reviewed', 'rejected']);
            }

            // Nếu là admin: thấy đơn đã được review (để approve)
            if ($user->role === 'admin') {
                $query->whereIn('status', ['reviewed', 'approved', 'rejected']);
            }

            // Nếu là khách hàng: chỉ thấy đơn của chính họ
            if ($user->role === 'user') {
                $query->where('user_id', $user->id);
            }

            // Nếu truyền filter status thì ưu tiên cái này
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            return ReturnRequestResource::collection($query->latest()->paginate(15));
        } catch (\Exception $e) {
            Log::error('Lỗi lấy danh sách yêu cầu hoàn: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi khi lấy danh sách yêu cầu hoàn'], 500);
        }
    }

    // Chi tiết yêu cầu hoàn
    public function show($id)
    {
        try {
            $user = Auth::user();

            $returnRequest = ReturnRequest::with(['user', 'order', 'reviewer', 'admin'])->findOrFail($id);

            $orderDetails = OrderDetail::where('order_id', $returnRequest->order_id)
                ->with([
                    'productDetail.product',
                    'productDetail.size',
                    'productDetail.color',
                ])
                ->get();


            $role = $user->role;
            $status = $returnRequest->status;

            $canView = match ($role) {
                'user' => $returnRequest->user_id === $user->id,
                'staff' => in_array($status, ['pending', 'reviewed', 'rejected']),
                'admin' => in_array($status, ['reviewed', 'approved', 'rejected']),
                default => false,
            };

            if (!$canView) {
                return response()->json(['message' => 'Bạn không có quyền xem yêu cầu này'], 403);
            }

            return new ReturnRequestDetailResource($returnRequest, $orderDetails);

        } catch (\Exception $e) {
            Log::error('Lỗi xem yêu cầu hoàn: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi khi lấy thông tin yêu cầu hoàn'], 500);
        }
    }


    // 2. Tạo mới yêu cầu hoàn hàng (cho khách hàng)
    public function store(Request $request, $orderId)
    {
        try {
            $request->validate([
                'reason' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
                'bank_account' => 'required|string|max:255',
            ]);

            $order = Order::where('id', $orderId)
                ->where('user_id', Auth::id()) // Chỉ cho hoàn đơn của chính mình
                ->first();

            if (!$order) {
                return response()->json(['message' => 'Đơn hàng không tồn tại hoặc không thuộc về bạn'], 403);
            }

            // Check 1: Đơn phải đã giao thành công
            if ($order->status !== 'delivered') {
                return response()->json(['message' => 'Chỉ có thể hoàn đơn đã được giao thành công'], 422);
            }

            // Check 2: Trong vòng 7 ngày từ khi giao
            // if (!$order->delivered_at || now()->diffInDays($order->delivered_at) > 7) {
            //     return response()->json(['message' => 'Chỉ được hoàn trong vòng 7 ngày kể từ khi giao hàng'], 422);
            // }

            // làm thêm 1 trường delivered_at trong bảng orders thì mở cái này ra nhé.

            // Check 3: Không được tạo request hoàn lần 2
            $exists = ReturnRequest::where('order_id', $order->id)->exists();
            if ($exists) {
                return response()->json(['message' => 'Đơn hàng này đã có yêu cầu hoàn trước đó'], 422);
            }

            // Upload ảnh nếu có
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('return_images', 'public');
            }

            // Tạo request mới
            $returnRequest = ReturnRequest::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'reason' => $request->reason,
                'description' => $request->description,
                'image' => $imagePath,
                'bank_account' => $request->bank_account,
                'status' => 'pending',
                'requested_at' => now(),
            ]);

            return response()->json([$returnRequest, 'message' => 'Bạn đã gửi yêu cầu hoàn hàng thành công'], 201);

        } catch (\Exception $e) {
            Log::error('ReturnRequest store error: ' . $e->getMessage());
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

            if ($user->role !== 'admin') {
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

            if ($user->role !== 'admin') {
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
                $productDetail = ProductDetail::find($detail->product_detail_id);
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
