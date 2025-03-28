import React from "react";


interface OrderItem {
    id: number;
    name: string;
    price: number;
    quantity: number;
    image: string;
}

interface Order {
    id: string;
    date: string;
    status: string;
    total: number;
    items: OrderItem[];
    shipping: {
        name: string;
        address: string;
        phone: string;
    };
}

const orderData: Order = {
    id: "ORD123456",
    date: "2025-03-27",
    status: "Đang xử lý", // Thay đổi trạng thái để kiểm tra
    total: 650000,
    items: [
        {
            id: 1,
            name: "Áo thun nam",
            price: 200000,
            quantity: 2,
            image: "../src/images/shoes_product6.png",
        },
        {
            id: 2,
            name: "Quần jeans nữ",
            price: 250000,
            quantity: 1,
            image: "../src/images/shoes_product5.png",
        },
    ],
    shipping: {
        name: "Nguyễn Văn A",
        address: "123 Đường ABC, TP.HCM",
        phone: "0987654321",
    },
};

const OrderDetail: React.FC = () => {
    const handleCancelOrder = () => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn hủy đơn hàng này không?");
        if (isConfirmed) {
            // Thực hiện hành động hủy đơn, ví dụ:
            // - Gửi yêu cầu đến API để cập nhật trạng thái đơn hàng
            // - Cập nhật state của component
            console.log("Đơn hàng đã được hủy.");
        }
    };

    // Kiểm tra nếu trạng thái đơn hàng cho phép hủy
    const canCancel = orderData.status === "Đang xử lý";

    return (
        <div className="order-detail nav">
            <h2>Chi tiết đơn hàng</h2>
            <table className="order-table">
                <tbody>
                    <tr>
                        <td><strong>Mã đơn hàng:</strong></td>
                        <td>{orderData.id}</td>
                    </tr>
                    <tr>
                        <td><strong>Ngày đặt hàng:</strong></td>
                        <td>{orderData.date}</td>
                    </tr>
                    <tr>
                        <td><strong>Trạng thái:</strong></td>
                        <td>{orderData.status}</td>
                    </tr>
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
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    {orderData.items.map((item) => (
                        <tr key={item.id}>
                            <td><img src={item.image} alt={item.name} /></td>
                            <td>{item.name}</td>
                            <td>{item.price.toLocaleString()}đ</td>
                            <td>{item.quantity}</td>
                            <td>{(item.price * item.quantity).toLocaleString()}đ</td>
                        </tr>
                    ))}
                </tbody>
            </table>

            <h3>Thông tin giao hàng</h3>
            <table className="order-table">
                <tbody>
                    <tr>
                        <td><strong>Người nhận:</strong></td>
                        <td>{orderData.shipping.name}</td>
                    </tr>
                    <tr>
                        <td><strong>Địa chỉ:</strong></td>
                        <td>{orderData.shipping.address}</td>
                    </tr>
                    <tr>
                        <td><strong>Điện thoại:</strong></td>
                        <td>{orderData.shipping.phone}</td>
                    </tr>
                </tbody>
            </table>

            <div className="order-total">
                <span className="total-label">Tổng cộng:</span>
                <span className="total-price">{orderData.total.toLocaleString()}đ</span>
                 {canCancel && (
                <button className="cancel-button" onClick={handleCancelOrder}>
                    Hủy đơn
                </button>
            )}
            </div>
        </div>
    );
};

export default OrderDetail;
