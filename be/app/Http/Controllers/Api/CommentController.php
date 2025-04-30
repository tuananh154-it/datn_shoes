<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

// Đặt locale thành tiếng Việt
Carbon::setLocale('vi');

class CommentController extends Controller
{
    // Lấy danh sách bình luận cho sản phẩm
    public function index(Request $request, $productId)
    {
        try {
            $perPage = $request->input('per_page', 10);

            $allComments = Comment::where('product_id', $productId)
                ->get();

            $commentsWithoutParent = Comment::where('product_id', $productId)
                ->where('is_hidden', false)
                ->whereNull('parent_id') // Lấy bình luận gốc (không phải bình luận con)
                ->withCount('children') // Đếm số lượng bình luận con (phản hồi)
                ->withCount('reports') // Đếm số lượng báo cáo
                ->with('user') // Tải thông tin người dùng
                ->paginate($perPage); // Phân trang

            // Kiểm tra nếu không có bình luận nào
            if ($commentsWithoutParent->isEmpty()) {
                return response()->json(['message' => 'Không có bình luận nào'], 404);
            }

            // Duyệt qua từng bình luận và trả về thông tin cần thiết
            $commentsData = $commentsWithoutParent->map(function ($comment) {
                return [
                    'user_name' => $comment->user->name,
                    'user_role' => $comment->user->role,
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'number_of_likes' => $comment->number_of_likes,
                    // 'created_at' => $comment->created_at->diffForHumans(),
                    'created_at' => $comment->created_at ? $comment->created_at->format('d-m-Y H:i') : 'N/A',
                    'is_anonymous' => $comment->is_anonymous,
                    'is_edited' => $comment->is_edited,
                    'total_reports' => $comment->reports_count, // Số lượng báo cáo
                    'total_replies' => $comment->children_count, // Số lượng phản hồi (đã đếm sẵn)
                ];
            });

            return response()->json([
                'comments' => $commentsData,
                'total_comments' => $allComments->count(),
                'pagination' => [
                    'total' => $commentsWithoutParent->total(),
                    'per_page' => $commentsWithoutParent->perPage(),
                    'current_page' => $commentsWithoutParent->currentPage(),
                    'last_page' => $commentsWithoutParent->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể truy vấn tới bảng Comments', 'message' => $e->getMessage()], 500);
        }
    }

    // Lấy chi tiết một bình luận (bao gồm bình luận con)
    public function show($commentId)
    {
        try {
            // Tải bình luận cùng với người dùng và các bình luận con
            $comment = Comment::with('user', 'children.user') // Tải người dùng và bình luận con cùng người dùng của chúng
                ->where('is_hidden', false)
                ->find($commentId);

            // Kiểm tra xem bình luận có tồn tại không
            if (!$comment) {
                return response()->json(['message' => 'Bình luận không tồn tại'], 404);
            }

            // Dữ liệu của bình luận cha
            $commentData = [
                'user_name' => $comment->user->name,
                'user_role' => $comment->user->role,
                'content' => $comment->content,
                'number_of_likes' => $comment->number_of_likes,
                'created_at' => $comment->created_at ? $comment->created_at->format('d-m-Y H:i') : 'N/A',
                'is_anonymous' => $comment->is_anonymous,
                'is_edited' => $comment->is_edited,
                'total_replies' => $comment->children_count, // Số lượng phản hồi (đã đếm sẵn)
            ];

            // Dữ liệu bình luận con
            $replies = $comment->children->map(function ($child) {
                return [
                    'user_name' => $child->user->name,
                    'user_role' => $child->user->role,
                    'content' => $child->content,
                    'number_of_likes' => $child->number_of_likes,
                    'created_at' => $child->created_at->diffForHumans(),
                    'is_anonymous' => $child->is_anonymous,
                    'is_edited' => $child->is_edited,
                ];
            });

            // Trả về bình luận cha và các bình luận con
            return response()->json([
                'comment' => $commentData,
                'replies' => $replies, // Các bình luận con
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể truy vấn tới bảng Comments', 'message' => $e->getMessage()], 500);
        }
    }

    // Tạo bình luận mới (cha)
    public function store(Request $request, $productId)
    {
        // Xác thực dữ liệu đầu vào cho bình luận gốc
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:500',
            'is_anonymous' => 'nullable|boolean',
        ]);

        // Kiểm tra nếu có lỗi xác thực
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Tạo bình luận mới trong cơ sở dữ liệu
        try {
            $data = $validator->validated();  // Lấy dữ liệu đã được xác thực

            // Tạo bình luận gốc mới
            $comment = Comment::create([
                'user_id' => Auth::id(), // Lấy ID của người dùng đã đăng nhập
                'product_id' => $productId,
                'content' => $data['content'],
                'parent_id' => null, // Bình luận gốc không có parent_id
                'is_anonymous' => $data['is_anonymous'] ?? false, // Mặc định là false nếu không có
            ]);

            // Lấy thông tin người dùng liên quan đến bình luận
            $comment->load('user'); // Tải quan hệ user

            // Chuẩn bị dữ liệu trả về
            $commentData = [
                'id' => $comment->id,
                'user_name' => $comment->is_anonymous ? 'Ẩn danh' : $comment->user->name, // Nếu ẩn danh thì hiển thị "Ẩn danh"
                'user_role' => $comment->user->role,
                'content' => $comment->content,
                'number_of_likes' => $comment->number_of_likes,
                'created_at' => $comment->created_at->diffForHumans(),
                'is_anonymous' => $comment->is_anonymous,
                'is_edited' => $comment->is_edited,
            ];

            // Trả về bình luận vừa tạo với thông tin đầy đủ
            return response()->json(['data' => $commentData, 'message' => 'Bình luận thành công'], 201);
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return response()->json(['error' => 'Bình luận thất bại', 'message' => $e->getMessage()], 500);
        }
    }

    // Tạo bình luận trả lời
    public function reply(Request $request, $parentId)
    {
        // Xác thực dữ liệu đầu vào cho bình luận trả lời
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:500',
            'is_anonymous' => 'nullable|boolean',
        ]);

        // Kiểm tra nếu có lỗi xác thực
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Kiểm tra nếu bình luận cha có tồn tại
        $parentComment = Comment::find($parentId);

        if (!$parentComment) {
            return response()->json(['message' => 'Bình luận cha không tồn tại'], 404);
        }

        // Tạo bình luận trả lời trong cơ sở dữ liệu
        try {
            $data = $validator->validated();  // Lấy dữ liệu đã được xác thực

            // Tạo bình luận trả lời mới
            $comment = Comment::create([
                'user_id' => Auth::id(), // Lấy ID của người dùng đã đăng nhập
                'product_id' => $parentComment->product_id, // Lấy product_id từ bình luận cha
                'content' => $data['content'],
                'parent_id' => $parentComment->id, // Bình luận trả lời sẽ có parent_id là ID của bình luận cha
                'is_anonymous' => $data['is_anonymous'] ?? false, // Mặc định là false nếu không có
            ]);

            // Lấy thông tin người dùng liên quan đến bình luận
            $comment->load('user'); // Tải quan hệ user

            // Chuẩn bị dữ liệu trả về
            $commentData = [
                'id' => $comment->id,
                'user_name' => $comment->is_anonymous ? 'Ẩn danh' : $comment->user->name, // Nếu ẩn danh thì hiển thị "Ẩn danh"
                'user_role' => $comment->user->role,
                'content' => $comment->content,
                'number_of_likes' => $comment->number_of_likes,
                'created_at' => $comment->created_at->diffForHumans(),
                'is_anonymous' => $comment->is_anonymous,
                'is_edited' => $comment->is_edited,
            ];

            // Trả về bình luận vừa tạo với thông tin đầy đủ
            return response()->json(['data' => $commentData, 'message' => 'Trả lời bình luận thành công'], 201);
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return response()->json(['error' => 'Trả lời bình luận thất bại', 'message' => $e->getMessage()], 500);
        }
    }

    // Cập nhật bình luận
    public function update(Request $request, $commentId)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:500', // Nội dung phải có và tối đa 500 ký tự
            'is_anonymous' => 'sometimes|boolean', // Kiểm tra trường is_anonymous nếu có
            'is_edited' => 'sometimes|boolean',  // Kiểm tra trường is_edited nếu có
        ]);

        // Kiểm tra nếu có lỗi xác thực
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Tìm kiếm bình luận cần cập nhật
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['error' => 'Bình luận không tồn tại'], 404);
        }

        // Kiểm tra xem người dùng có quyền sửa bình luận này không (chỉ cho phép người tạo bình luận sửa)
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Bạn không có quyền sửa bình luận này'], 403);
        }

        // Cập nhật bình luận
        try {
            $data = $validator->validated();  // Lấy dữ liệu đã được xác thực

            // Cập nhật thông tin bình luận
            $comment->update([
                'content' => $data['content'],
                'is_anonymous' => $data['is_anonymous'] ?? $comment->is_anonymous, // Nếu không có thì giữ nguyên
                'is_edited' => 1,   // Đánh dấu bình luận đã được chỉnh sửa
            ]);

            // Trả về bình luận đã cập nhật
            return response()->json(['data' => $comment, 'message' => 'Bình luận đã được cập nhật'], 200);
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return response()->json(['error' => 'Cập nhật bình luận thất bại', 'message' => $e->getMessage()], 500);
        }
    }

    public function like($commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['message' => 'Bình luận không tồn tại'], 404);
        }

        $userId = Auth::id();

        // Kiểm tra xem đã like chưa
        if ($comment->likes()->where('user_id', $userId)->exists()) {
            $comment->likes()->where('user_id', $userId)->delete(); // Xóa like
            $comment->decrement('number_of_likes'); // Giảm số lượng likes của bình luận
            return response()->json(['message' => 'Đã hủy like thành công'], 200);
        } else {
            $comment->increment('number_of_likes'); // Tăng số lượng likes của bình luận
            $comment->save();

            // Thêm like
            CommentInteraction::create([
                'comment_id' => $commentId,
                'user_id' => $userId,
                'type' => 1
            ]);

            return response()->json(['message' => 'Đã thích bình luận']);
        }
    }

    public function report($commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['message' => 'Bình luận không tồn tại'], 404);
        }

        $userId = Auth::id();

        // Kiểm tra xem người dùng có quyền báo cáo bình luận này không
        if ($comment->user_id === $userId) {
            return response()->json(['message' => 'Bạn không thể báo cáo bình luận của chính mình'], 403);
        }

        // Kiểm tra xem đã báo cáo chưa
        if ($comment->reports()->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Bạn đã báo cáo bình luận này rồi'], 400);
        }

        // Thêm report
        CommentInteraction::create([
            'comment_id' => $commentId,
            'user_id' => $userId,
            'type' => 2
        ]);

        return response()->json(['message' => 'Bình luận đã được báo cáo']);
    }

    // Xóa bình luận 
    public function destroy($commentId)
    {
        try {
            $user = Auth::user();
            $comment = Comment::withTrashed()->find($commentId);

            if (!$comment) {
                return response()->json(['message' => 'Bình luận không tồn tại'], 404);
            }

            $isOwner = $comment->user_id === $user->id;
            $targetUser = $comment->user;

            // Superadmin: có thể xóa tất cả
            if ($user->role === 'superadmin') {
                $comment->forceDelete();
                return response()->json(['message' => 'Bình luận đã được xóa vĩnh viễn']);
            }

            // Admin: được xóa của mình + của user
            if ($user->role === 'admin') {
                if ($isOwner || $targetUser->role === 'user') {
                    $comment->forceDelete();
                    return response()->json(['message' => 'Bình luận đã được xóa vĩnh viễn']);
                } else {
                    return response()->json(['message' => 'Bạn không có quyền xóa bình luận này'], 403);
                }
            }

            // User thường: chỉ xóa được của mình
            if ($user->role === 'user' && $isOwner) {
                $comment->forceDelete();
                return response()->json(['message' => 'Bình luận đã được xóa vĩnh viễn']);
            }

            // Tất cả các trường hợp không hợp lệ
            return response()->json(['message' => 'Bạn không có quyền xóa bình luận này'], 403);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi trong quá trình xóa bình luận',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Ẩn bình luận
    public function toggleHidden($commentId)
    {
        try {
            $user = Auth::user();
            $comment = Comment::find($commentId);

            if (!$comment) {
                return response()->json(['message' => 'Bình luận không tồn tại'], 404);
            }

            $isOwner = $comment->user_id === $user->id;
            $targetUser = $comment->user;

            // superadmin: có thể ẩn hoặc hiện bất kỳ bình luận nào
            if ($user->role === 'superadmin') {
                $comment->is_hidden = !$comment->is_hidden;
                $comment->save();
                return response()->json(['message' => $comment->is_hidden ? 'Bình luận đã được ẩn' : 'Bình luận đã được hiện lại']);
            }

            // admin: có thể ẩn hoặc hiện bình luận của chính mình và của user
            if ($user->role === 'admin') {
                if ($isOwner || $targetUser->role === 'user') {
                    $comment->is_hidden = !$comment->is_hidden;
                    $comment->save();
                    return response()->json(['message' => $comment->is_hidden ? 'Bình luận đã được ẩn' : 'Bình luận đã được hiện lại']);
                } else {
                    return response()->json(['message' => 'Bạn không có quyền thay đổi trạng thái ẩn/hiện bình luận này'], 403);
                }
            }

            // user: chỉ có thể ẩn hoặc hiện bình luận của chính mình
            if ($user->role === 'user' && $isOwner) {
                $comment->is_hidden = !$comment->is_hidden;
                $comment->save();
                return response()->json(['message' => $comment->is_hidden ? 'Bình luận đã được ẩn' : 'Bình luận đã được hiện lại']);
            }

            return response()->json(['message' => 'Bạn không có quyền thay đổi trạng thái ẩn/hiện bình luận này'], 403);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi thay đổi trạng thái ẩn/hiện bình luận',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Tất cả bình luận của người dùng
    // Chỉ admin và superadmin mới có thể xem bình luận của người khác
    // User thường chỉ có thể xem bình luận của chính mình
    public function myComments()
    {
        try {
            $user = Auth::user();

            $comments = Comment::where('user_id', $user->id)
                ->withCount('reports') // Đếm số lượng báo cáo
                ->with('user')
                ->with('parent')
                ->orderByDesc('created_at')
                ->get();

            $commentsData = $comments->map(function ($comment) {
                $parentComment = $comment->parent->content ?? null; // Lấy nội dung bình luận cha nếu có
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'parent_comment' => $parentComment, // Bình luận cha (nếu có)
                    'number_of_likes' => $comment->number_of_likes,
                    'created_at' => $comment->created_at->diffForHumans(), // Thời gian tạo bình luận
                    'is_hidden' => $comment->is_hidden,
                    'is_anonymous' => $comment->is_anonymous,
                    'is_edited' => $comment->is_edited,
                    'total_reports' => $comment->reports_count, // Số lượng báo cáo
                ];
            });

            return response()->json([
                'message' => 'Danh sách bình luận của bạn',
                'total_comments' => $comments->count(),
                'data' => $commentsData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi lấy danh sách bình luận',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
