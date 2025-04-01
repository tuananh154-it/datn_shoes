import { api } from "../config/axios";

export type Comment = {
    id: number;
    user_id: number;
    user_name: string;
    product_id: number;
    parent_id: number | null;
    content: string;
    number_of_likes: number;
    is_anonymous: boolean;
    is_edited: boolean;
    deleted_at: string | null;
    created_at: string;
    updated_at: string | null;
    replies?: Comment[];
    is_liked?: boolean;
  };
  
  // Lấy danh sách bình luận theo sản phẩm
  export const getCommentsByProductId = (productId: number) => {
    return api.get<Comment[]>(`/product/${productId}/comments`); // Sửa đường dẫn
  };
  
  // Đăng bình luận mới
  export const postComment = (productId: number, content: string, token: string) => {
    return api.post(
      `/product/${productId}/post`,
      { content },
      { headers: { Authorization: `Bearer ${token}` } }
    );
  };
  
  // Trả lời bình luận
  export const replyComment = (parentId: number, content: string, token: string) => {
    return api.post(
      `/comment/${parentId}/reply`,
      { content },
      { headers: { Authorization: `Bearer ${token}` } }
    );
  };
  
  // Sửa bình luận
  export const updateComment = (commentId: number, content: string, token: string) => {
    return api.put(
      `/comment/${commentId}/edit`,
      { content },
      { headers: { Authorization: `Bearer ${token}` } }
    );
  };
  
  // Xóa bình luận (xóa mềm)
  export const deleteComment = (commentId: number, token: string) => {
    return api.delete(`/comment/${commentId}`, {
      headers: { Authorization: `Bearer ${token}` },
    });
  };
  
  // Thích bình luận
  export const likeComment = (commentId: number, token: string) => {
    return api.put(
      `/comment/${commentId}/like`,
      {},
      { headers: { Authorization: `Bearer ${token}` } }
    );
  };
  
  // Báo cáo bình luận
  export const reportComment = (commentId: number, reason: string, token: string) => {
    return api.put(
      `/comment/${commentId}/report`,
      { reason },
      { headers: { Authorization: `Bearer ${token}` } }
    );
  };