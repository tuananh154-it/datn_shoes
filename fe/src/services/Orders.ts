
import { api } from "../config/axios";

export interface ProductDetail {
    id: number;
    image: string; // Chuỗi JSON, cần parse thành mảng khi dùng
    product_id: number;
    size_id: number;
    color_id: number;
    quantity: number;
    default_price: string;
    discount_price: string;
    status: string;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
}

export interface OrdersDetail {
    id: number;
    order_id: number;
    product_detail_id: number;
    price: string; // API trả về dạng string
    quantity: number;
    total_price: string; // API dùng `total_price`, không phải `total`
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    product_detail: ProductDetail; // API trả về object `product_detail`
}

export interface Order {
    id: number;
    username: string;
    voucher_id: number | null;
    status: string;
    deliver_fee: string; // API trả về kiểu string
    user_id: number;
    payment_status: string;
    payment_method: string;
    address: string;
    phone_number: string;
    email: string;
    total_price: string;
    note: string | null;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    voucher: any | null;
    order_details: OrdersDetail[]; // Danh sách chi tiết đơn hàng
}
export const getAllOrders = () => {
    return api.get("/orders");
};

export const getDetailOrder = (id: number) => {
    return api.get(`/orders/${id}`);
};
export const CancellOrder=(orderId:number)=>{
    return api.post(`/orders/${orderId}/cancel`)
}
export const getStatusLabel = (status: string) => {
    const statusMapping: { [key: string]: string } = {
        "waiting_for_confirmation": "Chờ xác nhận",
        "waiting_for_pickup": "Chờ lấy hàng",
        "waiting_for_delivery": "Đang giao hàng",
        "delivered": "Đã giao hàng",
        "returned": "Đã trả hàng",
        "cancelled": "Đã hủy"
    };

    return statusMapping[status] || "Không xác định";
};

export const getPaymentStatusInVietnamese = (status: string) => {
    const statusMapping: { [key: string]: string } = {
        "unpaid": "Chưa thanh toán",
        "paid": "Đã thanh toán",
        "failed": "Thanh toán thất bại",
        "refunded": "Đã hoàn tiền",
    };

    return statusMapping[status] || "Không xác định";
};