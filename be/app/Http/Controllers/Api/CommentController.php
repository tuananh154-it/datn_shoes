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
    public function index($productId)
    {
        try {

            $allComments = Comment::where('product_id', $productId)->get();

            $commentsWithoutParent = Comment::where('product_id', $productId)
                ->whereNull('parent_id') // Lấy bình luận gốc (không phải bình luận con)
                ->withCount('children') // Đếm số lượng bình luận con (phản hồi)
                ->withCount('reports') // Đếm số lượng báo cáo
                ->with('user') // Tải thông tin người dùng
                ->get();

            // Duyệt qua từng bình luận và trả về thông tin cần thiết
            $commentsData = $commentsWithoutParent->map(function ($comment) {
                return [
                    'user_name' => $comment->user->name,
                    'user_role' => $comment->user->role,
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'number_of_likes' => $comment->number_of_likes,
                    'created_at' => $comment->created_at->diffForHumans(), // Thời gian tạo bình luận
                    'is_anonymous' => $comment->is_anonymous,
                    'is_edited' => $comment->is_edited,
                    'total_reports' => $comment->reports_count, // Số lượng báo cáo
                    'total_replies' => $comment->children_count, // Số lượng phản hồi (đã đếm sẵn)
                ];
            });

            return response()->json([
                'comments' => $commentsData,
                'total_comments' => $allComments->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể truy vấn tới bảng Comments', 'message' => $e->getMessage()], 500);
        }
    }

    // Lấy chi tiết một bình luận (bao gồm bình luận con)
    // Show các bình luận con phía dưới giống như Facebook
    public function show($commentId)
    {
        try {
            // Tải bình luận cùng với người dùng và các bình luận con
            $comment = Comment::with('user', 'children.user') // Tải người dùng và bình luận con cùng người dùng của chúng
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
                'created_at' => $comment->created_at->diffForHumans(), // Thời gian tạo bình luận
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

            // Trả về bình luận vừa tạo
            return response()->json(['data' => $comment, 'message' => 'Bình luận thành công'], 201);
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return response()->json(['error' => 'Bình luận thất bại', 'message' => $e->getMessage()], 500);
        }
    }

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

            // Trả về bình luận vừa tạo
            return response()->json(['data' => $comment, 'message' => 'Trả lời bình luận thành công'], 201);
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


    // Xóa bình luận (soft delete)
    public function destroy($commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['message' => 'Bình luận không tồn tại'], 404);
        }

        // Chỉ cho phép người dùng xóa bình luận của chính họ
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Bạn không có quyền xóa bình luận này'], 403);
        }

        // Xóa mềm bình luận
        $comment->delete();

        return response()->json(['message' => 'Bình luận đã được xóa (soft delete)']);
    }

    // Xóa vĩnh viễn bình luận (hard delete)
    // Chỉ Admin mới có quyền xóa vĩnh viễn
    public function forceDelete($commentId)
    {
        $comment = Comment::withTrashed()->find($commentId);

        if (!$comment) {
            return response()->json(['message' => 'Bình luận không tồn tại'], 404);
        }

        // Kiểm tra quyền (Chỉ Admin mới có quyền xóa vĩnh viễn)
        if (!Auth::user()->role == 'user') {
            return response()->json(['message' => 'Bạn không có quyền xóa vĩnh viễn bình luận này'], 403);
        }

        // Xóa vĩnh viễn bình luận
        $comment->forceDelete();

        return response()->json(['message' => 'Bình luận đã được xóa vĩnh viễn']);
    }
}
