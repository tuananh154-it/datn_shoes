import { api } from "../config/axios";

export type Comment = {
    id: number;
    user_name: string;
    user_role?: string; // Thêm trường user_role
    content: string;
    number_of_likes: number;
    created_at: string;
    is_anonymous: boolean;
    is_edited: boolean;
    total_reports?: number; // Thêm trường total_reports
    total_replies?: number; // Thêm trường total_replies
    replies?: Comment[]; // Thêm trường replies
    // total_comments:string;
  };
  
  // Lấy danh sách bình luận theo sản phẩm
  export const getCommentsByProductId = (productId: number) => {
    return api.get(`/product/${productId}/comments`);
  };
  
  // Đăng bình luận mới
  export const postComment = (productId: number, content: string) => {
    return api.post(`/product/${productId}/post`, { content });
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