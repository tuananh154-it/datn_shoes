import React, { useEffect, useState } from "react";
import { CancellOrder, getDetailOrder, getPaymentStatusInVietnamese, getStatusLabel, Order } from "../services/orders"; // Import API
import { getProductDetail } from "../services/product";

interface OrderDetailProps {
    orderId: number; // Nhận ID của đơn hàng
}

const OrderDetail: React.FC<OrderDetailProps> = ({ orderId }) => {
    const [order, setOrder] = useState<Order | null>(null);
    const [loading, setLoading] = useState(true);

    // useEffect(() => {
    //     const fetchOrderDetail = async () => {
    //         try {
    //             const response = await getDetailOrder(orderId);
    //             setOrder(response.data); // Lưu đơn hàng vào state
    //         } catch (error) {
    //             console.error("Lỗi khi lấy chi tiết đơn hàng:", error);
    //         } finally {
    //             setLoading(false);
    //         }
    //     };

    //     fetchOrderDetail();
    // }, [orderId]); // Gọi lại khi orderId thay đổi
    useEffect(() => {
        const fetchOrderDetail = async () => {
            try {
                setLoading(true);

                // 1️⃣ Lấy chi tiết đơn hàng
                const response = await getDetailOrder(orderId);
                const orderData = response.data;
                if (!orderData) throw new Error("API không trả về dữ liệu hợp lệ");

                // 2️⃣ Lọc danh sách `product_id`
                const productIds = [...new Set(
                    orderData.order_details.map(detail => detail.product_detail?.product_id)
                )];

                // 3️⃣ Gọi API lấy thông tin sản phẩm
                const productResponses = await Promise.all(
                    productIds.map(id => getProductDetail(id))
                );

                // 4️⃣ Lưu thông tin sản phẩm vào `Map`
                const productMap = new Map();
                productResponses.forEach(response => {
                    if (response?.data?.data) {
                        productMap.set(response.data.data.id, response.data.data);
                    }
                });

                // 5️⃣ Cập nhật lại `order_details` với thông tin sản phẩm
                const updatedOrderDetails = orderData.order_details.map(detail => {
                    const productInfo = productMap.get(detail.product_detail?.product_id) || {};
                    return {
                        ...detail,
                        product_detail: {
                            ...detail.product_detail,
                            product_name: productInfo.name || "Không xác định",
                            color: detail.product_detail?.color || "Không xác định",
                            size: detail.product_detail?.size || "Không xác định"
                        }
                    };
                });

                // 6️⃣ Cập nhật state
                setOrder({
                    ...orderData,
                    order_details: updatedOrderDetails
                });

            } catch (error) {
                console.error("Lỗi khi lấy chi tiết đơn hàng:", error);
            } finally {
                setLoading(false);
            }
        };

        fetchOrderDetail();
    }, [orderId]); // Gọi lại khi orderId thay đổi


    if (loading) return <p>Đang tải chi tiết đơn hàng...</p>;
    if (!order) return <p>Không tìm thấy đơn hàng.</p>;

    const canCancel = order.status === "waiting_for_confirmation";

    const handleCancelOrder = async () => {
        const reason = prompt("Vui lòng nhập lý do hủy đơn hàng:");
        if (!reason) {
            alert("Bạn phải nhập lý do hủy đơn!");
            return;
        }

        const isConfirmed = window.confirm("Bạn có chắc chắn muốn hủy đơn hàng này không?");
        if (!isConfirmed) return;


        try {
            await CancellOrder(order.id);
            alert("Đơn hàng đã được hủy thành công!");
            setOrder({ ...order, status: "cancelled" }); // Cập nhật UI
        } catch (error) {
            alert("Lỗi khi hủy đơn hàng, vui lòng thử lại!");
            console.error("Lỗi khi hủy đơn:", error);
        }
    };

    return (
        <div className="order-detail">
            <h2>Chi tiết đơn hàng</h2>
            <table className="order-table">
                <tbody>
                    <tr><td><strong>Mã đơn hàng:</strong></td><td>{order.id}</td></tr>
                    <tr><td><strong>Khách hàng:</strong></td><td>{order.username}</td></tr>
                    <tr><td><strong>Ngày đặt hàng:</strong></td><td>{new Date(order.created_at).toLocaleDateString()}</td></tr>
                    <tr>
                        <td><strong>Trạng thái:</strong></td>
                        <td style={{ color: order.status === "cancelled" ? "red" : "black" }}>
                            {getStatusLabel(order.status)}
                        </td>
                    </tr>
                    <tr><td><strong>Thanh toán:</strong></td><td>{getPaymentStatusInVietnamese(order.payment_status)}</td></tr>
                </tbody>
            </table>

            <h3>Sản phẩm</h3>
            <table className="order-table">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Màu</th>
                        <th>Kích cỡ</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    {order.order_details.map((item) => (
                        <tr key={item.id}>
                            <td><img src={JSON.parse(item.product_detail.image)[0]} alt="Sản phẩm" width="50" /></td>
                            <td>{item.product_detail.product_name}</td>
                            <td>{parseFloat(item.price).toLocaleString()}đ</td>
                            <td>{item.quantity}</td>
                            <td>{item.product_detail.color_id}</td>
                            <td>{item.product_detail.size_id}</td>
                            <td>{parseFloat(item.total_price).toLocaleString()}đ</td>
                        </tr>
                    ))}
                </tbody>
            </table>

            <h3>Thông tin giao hàng</h3>
            <table className="order-table">
                <tbody>
                    <tr><td><strong>Địa chỉ:</strong></td><td>{order.address}</td></tr>
                    <tr><td><strong>Điện thoại:</strong></td><td>{order.phone_number}</td></tr>
                    <tr><td><strong>Email:</strong></td><td>{order.email}</td></tr>
                </tbody>
            </table>

            <div className="order-total">
                <span className="total-label">Tổng cộng:</span>
                <span className="total-price">{parseFloat(order.total_price).toLocaleString()}đ</span>
                <button
                    className="cancel-button"
                    onClick={handleCancelOrder}
                    disabled={!canCancel}
                >
                    Hủy đơn
                </button>
            </div>
        </div>
    );
};

export default OrderDetail;
