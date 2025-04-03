import { api } from "../config/axios";

export interface Review {
    id: number;
    user_id: number;
    user_name: string;
    rating: number;
    content: string;
    created_at: string;
    number_of_likes: number;  // Thay 'likes' thành 'number_of_likes' để thống nhất với backend
    is_liked: boolean;        // Đây là trường xác định xem người dùng đã thích đánh giá hay chưa
    is_anonymous: boolean;    // Nếu bạn muốn hiển thị thông tin ẩn danh
    reply?: string;           // Phản hồi từ Admin (nếu có)
}

export interface ReviewPayload {
    rating: number;
    content: string;
}

export const getReviewsByProductId = (productId: number) => {
    return api.get(`/product/${productId}/reviews`);
};

export const postReview = async (
    productId: string, 
    orderId: string, 
    reviewData: ReviewPayload
): Promise<Review> => {
    try {
        const response = await api.post(`/product/${productId}/order/${orderId}/review`, reviewData);
        return response.data;
    } catch (error) {
        console.error("Lỗi khi gửi đánh giá:", error);
        throw error;
    }
};

export const updateReview = async (
    reviewId: string, 
    reviewData: ReviewPayload
): Promise<Review> => {
    try {
        const response = await api.put(`/reviews/${reviewId}`, reviewData);
        return response.data;
    } catch (error) {
        console.error("Lỗi khi cập nhật đánh giá:", error);
        throw error;
    }
};

export const likeReview = async (reviewId: string): Promise<{ success: boolean }> => {
    try {
        const response = await api.post(`/reviews/${reviewId}/like`);
        return response.data;
    } catch (error) {
        console.error("Lỗi khi thích đánh giá:", error);
        throw error;
    }
};

export const reportReview = async (reviewId: string): Promise<{ success: boolean }> => {
    try {
        const response = await api.post(`/reviews/${reviewId}/report`);
        return response.data;
    } catch (error) {
        console.error("Lỗi khi báo cáo đánh giá:", error);
        throw error;
    }
};
