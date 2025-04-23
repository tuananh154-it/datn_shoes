<?php

namespace App\Http\Controllers;

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

    // 1. Danh sách yêu cầu (cho CSKH, admin hoặc khách hàng)
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $query = ReturnRequest::with(['user', 'order', 'reviewer', 'admin']);

            $this->applyRoleFilters($query, $user);

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('order_id')) {
                $query->where('order_id', $request->order_id);
            }

            if ($request->has('order_detail_id')) {
                $query->where('order_detail_id', $request->order_detail_id);
            }

            $query->orderByRaw("FIELD(status, 'pending', 'reviewed') DESC");

            $query->latest();

            $returnRequests = $query->paginate(15);

            return view('return_requests.index', compact('returnRequests'));
        } catch (\Exception $e) {
            Log::error('Error fetching return requests: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching return requests', 'error' => $e->getMessage()], 500);
        }
    }

    // Chi tiết yêu cầu hoàn
    public function show($reviewId)
    {
        try {
            $user = Auth::user();

            // Lấy yêu cầu hoàn trả theo review_id thay vì order_id
            $returnRequest = ReturnRequest::with([
                'orderDetail.productDetail.product',
                'orderDetail.productDetail.size',
                'orderDetail.productDetail.color'
            ])
                ->where('id', $reviewId)
                ->first();

            // Kiểm tra nếu không có yêu cầu hoàn trả nào
            if (!$returnRequest) {
                return view('return_requests.show', ['message' => 'Không tìm thấy yêu cầu hoàn trả với review ID này']);
            }

            // Lấy trạng thái của yêu cầu hoàn trả
            $status = $returnRequest->status;

            // Kiểm tra quyền truy cập dựa trên vai trò người dùng và trạng thái yêu cầu
            $canView = match ($user->role) {
                'user' => $returnRequest->user_id === $user->id,
                'staff' => in_array($status, ['pending', 'reviewed', 'rejected']),
                'admin', 'superadmin' => in_array($status, ['reviewed', 'approved', 'rejected']),
                default => false,
            };

            // Kiểm tra quyền truy cập
            if (!$canView) {
                return view('return_requests.show', ['message' => 'Bạn không có quyền xem yêu cầu này']);
            }

            // Lấy thông tin sản phẩm từ yêu cầu hoàn trả
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

            // Trả về thông tin yêu cầu hoàn trả cho view
            return view('return_requests.show', [
                'review_id' => $reviewId,
                'product' => $product,
                'reason' => [
                    'reason' => $returnRequest->reason,
                    'description' => $returnRequest->description,
                    'bank_account' => $returnRequest->bank_account,
                ],
                'all_total' => $returnRequest->orderDetail->total_price,
            ]);

        } catch (\Exception $e) {
            // Xử lý lỗi
            Log::error('Lỗi: ' . $e->getMessage());
            return view('return_requests.show', ['message' => 'Lỗi khi lấy thông tin yêu cầu hoàn']);
        }
    }

    // Hiển thị form tạo mới yêu cầu hoàn hàng
    public function create($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->back()->withErrors('Đơn hàng không tồn tại hoặc không thuộc về bạn');
        }
        return view('return_requests.create', compact('order'));
    }

    // Xử lý lưu yêu cầu hoàn hàng
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
                ->where('user_id', Auth::id())
                ->first();

            if (!$order) {
                return redirect()->back()->withErrors('Đơn hàng không tồn tại hoặc không thuộc về bạn');
            }

            // Check 1: Đơn phải đã giao thành công
            if ($order->status !== 'delivered') {
                return redirect()->back()->withErrors('Chỉ có thể hoàn đơn đã được giao thành công');
            }

            // Check 3: Không được tạo request hoàn lần 2
            $exists = ReturnRequest::where('order_id', $order->id)->exists();
            if ($exists) {
                return redirect()->back()->withErrors('Đơn hàng này đã có yêu cầu hoàn trước đó');
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

            return redirect()->route('return_requests.index')
                ->with('success', 'Bạn đã gửi yêu cầu hoàn hàng thành công');
        } catch (\Exception $e) {
            Log::error('ReturnRequest store error: ' . $e->getMessage());
            return redirect()->back()->withErrors('Lỗi khi gửi yêu cầu hoàn hàng');
        }
    }

    // Các phương thức cho "acceptReview", "rejectReview", "approveReturn", "rejectReturn", "receiveReturn"
    // Bạn có thể chuyển đổi tương tự bằng cách chuyển hướng về view hoặc thông báo session sau khi xử lý.
    // Ví dụ dưới đây là chuyển hướng sau khi duyệt đơn:
    public function approve($id)
    {
        try {
            $user = Auth::user();

            if (!in_array($user->role, ['admin', 'superadmin'])) {
                return redirect()->back()->with('error', 'Bạn không có quyền duyệt yêu cầu này');
            }

            $returnRequest = ReturnRequest::findOrFail($id);

            if ($returnRequest->status !== 'reviewed') {
                return redirect()->back()->with('error', 'Yêu cầu chưa được nhân viên xem xét hoặc đã xử lý xong');
            }

            $returnRequest->update([
                'status' => 'approved',
                'admin_id' => $user->id,
                'admin_approved_at' => now(),
            ]);

            $returnRequest->order->update([
                'status' => 'returned',
            ]);

            return redirect()->back()->with('success', 'Yêu cầu đã được duyệt');
        } catch (\Exception $e) {
            Log::error('Lỗi duyệt yêu cầu hoàn: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi duyệt yêu cầu hoàn');
        }
    }


    // Phương thức rejectReview: xử lý khi nhân viên từ chối yêu cầu
    public function rejectReview($id)
    {
        try {
            $user = Auth::user();

            if ($user->role !== 'staff') {
                return redirect()->back()->withErrors('Bạn không có quyền từ chối yêu cầu này');
            }

            $returnRequest = ReturnRequest::findOrFail($id);

            if ($returnRequest->status !== 'pending') {
                return redirect()->back()->withErrors('Yêu cầu không thể từ chối');
            }

            $returnRequest->update([
                'status' => 'rejected',
                'admin_id' => $user->id,
                'admin_rejected_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Yêu cầu đã bị từ chối');
        } catch (\Exception $e) {
            Log::error('Lỗi từ chối yêu cầu hoàn: ' . $e->getMessage());
            return redirect()->back()->withErrors('Lỗi khi từ chối yêu cầu hoàn');
        }
    }
    // Các phương thức rejectReview, rejectReturn, receiveReturn cũng chuyển sang dạng xử lý tương tự,
    // chuyển hướng về trang thích hợp và hiển thị thông báo thông qua session.
}
