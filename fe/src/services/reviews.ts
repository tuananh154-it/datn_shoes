import { api } from "../config/axios";

export interface Review {
    id: number;
    user_id: number;
    user_name: string;
    user_role: string;
    rating: number;
    content: string;
    created_at: string;
    number_of_likes: number;
    is_anonymous: boolean;
    reply?: string;
    order_id?: number;
    size?: string;
    color?: string;
    product_name: string;
    image?: string; // Thêm trường image (URL của ảnh)
}

export interface ReviewPayload {
    order_detail_id: number;
    product_id: number; // Thêm product_id
    rating: number;
    content: string;
    service?: number;
    packaging?: number;
    shipping?: number;
    customer_service?: number;
    is_anonymous?: boolean;
    image?: File; // Thêm trường image (dạng File để upload)
}

export const getReviewsByProductId = (productId: number) => {
    return api.get(`/product/${productId}/reviews`);
};

export const getMyReviews = () => {
    return api.get(`/reviews`);
};

// export const postReview = async (
//     orderId: string,
//     reviews: ReviewPayload[]
// ): Promise<{ message: string; reviews: Review[] }> => {
//     try {
//         const response = await api.post(`/order/${orderId}/review`, { reviews });
//         return response.data;
//     } catch (error: any) {
//         console.error("Lỗi khi gửi đánh giá:", error.response?.data || error.message);
//         throw error;
//     }
// };
export const postReview = async (
    orderId: string,
    reviews: ReviewPayload[]
): Promise<{ message: string; reviews: Review[] }> => {
    try {
        const formData = new FormData();

        reviews.forEach((review, index) => {
            formData.append(`reviews[${index}][order_detail_id]`, review.order_detail_id.toString());
            // formData.append(`reviews[${index}][product_id]`, review.product_id.toString());
            formData.append(`reviews[${index}][rating]`, review.rating.toString());
            formData.append(`reviews[${index}][content]`, review.content || '');
            if (review.image) {
                formData.append(`reviews[${index}][image]`, review.image);
            }
            formData.append(`reviews[${index}][is_anonymous]`, review.is_anonymous ? '1' : '0');
            if (review.service) {
                formData.append(`reviews[${index}][service]`, review.service.toString());
            }
            if (review.packaging) {
                formData.append(`reviews[${index}][packaging]`, review.packaging.toString());
            }
            if (review.shipping) {
                formData.append(`reviews[${index}][shipping]`, review.shipping.toString());
            }
            if (review.customer_service) {
                formData.append(`reviews[${index}][customer_service]`, review.customer_service.toString());
            }
        });

        const response = await api.post(`/order/${orderId}/review`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        return response.data;
    } catch (error: any) {
        console.error("Lỗi khi gửi đánh giá:", error.response?.data || error.message);
        throw error;
    }
};

export const updateReview = async (
    reviewId: string,
    reviewData: Omit<ReviewPayload, 'order_detail_id'>
): Promise<Review> => {
    try {
        const response = await api.put(`/reviews/${reviewId}`, reviewData);
        return response.data;
    } catch (error: any) {
        console.error("Lỗi khi cập nhật đánh giá:", error.response?.data || error.message);
        throw error;
    }
};

export const likeReview = async (reviewId: string): Promise<{ message: string }> => {
    try {
        const response = await api.post(`/reviews/${reviewId}/like`);
        return response.data;
    } catch (error: any) {
        console.error("Lỗi khi thích đánh giá:", error.response?.data || error.message);
        throw error;
    }
};

export const reportReview = async (reviewId: string): Promise<{ message: string }> => {
    try {
        const response = await api.post(`/reviews/${reviewId}/report`);
        return response.data;
    } catch (error: any) {
        console.error("Lỗi khi báo cáo đánh giá:", error.response?.data || error.message);
        throw error;
    }
};