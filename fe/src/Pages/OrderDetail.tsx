import React, { useState, useEffect } from "react";
import { CancellOrder, getPaymentStatusInVietnamese, getStatusLabel, getStatusColor, Order, OrdersDetail } from "../services/orders";
import { Link } from "react-router-dom";
import Modal from 'react-modal';
import { postReview, ReviewPayload, getMyReviews } from "../services/reviews";
import toast from "react-hot-toast";

interface OrderDetailProps {
    order: Order;
}

const OrderDetail: React.FC<OrderDetailProps> = ({ order }) => {
    const [currentOrder, setCurrentOrder] = useState<Order>(order);
    const [isReviewModalOpen, setIsReviewModalOpen] = useState(false);
    const [isCancelModalOpen, setIsCancelModalOpen] = useState(false);
    const [selectedProductId, setSelectedProductId] = useState<number | null>(null);
    const [selectedOrderDetailId, setSelectedOrderDetailId] = useState<number | null>(null);
    const [rating, setRating] = useState<number>(0);
    const [newReview, setNewReview] = useState<string>('');
    const [cancelReason, setCancelReason] = useState<string>('');
    const [hasReviewed, setHasReviewed] = useState<{ [key: number]: boolean }>({});
    const [reviewUpdated, setReviewUpdated] = useState<number>(0);

    const canCancel = currentOrder.status.toLowerCase() === "pending" && currentOrder.payment_status.toLowerCase() !== "paid";


    Modal.setAppElement('#root');

    const renderStars = (rating: number, editable: boolean = false) => {
        return Array.from({ length: 5 }, (_, index) => (
            <span
                key={index}
                style={{
                    color: index < rating ? 'gold' : 'gray',
                    cursor: editable ? 'pointer' : 'default',
                    fontSize: '24px',
                }}
                onClick={editable ? () => setRating(index + 1) : undefined}
            >
                ★
            </span>
        ));
    };

    useEffect(() => {
        const checkReviews = async () => {
            try {
                const response = await getMyReviews();
                const reviews = response?.data?.my_reviews || [];

                console.log("📦 Reviews API data:", reviews);
                console.log("🧾 Current order object:", currentOrder);
                console.log("🔍 Mapping through order_details:", currentOrder.order_details);

                const reviewedOrderDetails: { [key: number]: boolean } = {};

                currentOrder.order_details.forEach((item) => {
                    if (item.order_id !== currentOrder.id) {
                        console.warn(`⚠️ Order detail order_id (${item.order_id}) không khớp với order_id (${currentOrder.id})`);
                        return;
                    }

                    const orderDetailId = item.id;
                    if (orderDetailId !== undefined && Number.isInteger(orderDetailId)) {
                        const hasReviewed = reviews.some((review: any) =>
                            review.order_detail_id === orderDetailId &&
                            review.order_id === currentOrder.id
                        );
                        reviewedOrderDetails[orderDetailId] = hasReviewed;
                    } else {
                        console.warn("⚠️ Order detail ID không hợp lệ:", item);
                    }
                });

                console.log("✅ Final reviewedOrderDetails:", reviewedOrderDetails);
                setHasReviewed(reviewedOrderDetails);
            } catch (error) {
                console.error("❌ Lỗi khi kiểm tra đánh giá:", error);
                const fallbackReviewed: { [key: number]: boolean } = {};
                currentOrder.order_details.forEach((item) => {
                    if (item.id !== undefined && Number.isInteger(item.id)) {
                        fallbackReviewed[item.id] = false;
                    }
                });
                setHasReviewed(fallbackReviewed);
                toast.error("Lỗi khi kiểm tra trạng thái đánh giá!");
            }
        };

        if (currentOrder.status.toLowerCase() === "completed") {
            checkReviews();
        }
    }, [currentOrder, reviewUpdated]);

    console.log("Current Order:", currentOrder);

    const handleOpenReviewModal = (orderDetailId: number, productId: number | undefined) => {
        if (currentOrder.status.toLowerCase() !== "completed") {
            toast.error("Chỉ có thể đánh giá khi đơn hàng đã hoàn tất!");
            return;
        }
        if (!Number.isInteger(orderDetailId)) {
            toast.error("Không thể mở đánh giá: ID chi tiết đơn hàng không hợp lệ!");
            return;
        }
        if (hasReviewed[orderDetailId]) {
            toast.error("Bạn đã đánh giá mục này rồi.");
            return;
        }
        if (!productId || !Number.isInteger(productId)) {
            toast.error("Không thể mở đánh giá: Thiếu thông tin sản phẩm hoặc product_id không hợp lệ!");
            return;
        }

        setSelectedProductId(productId);
        setSelectedOrderDetailId(orderDetailId);
        setRating(0);
        setNewReview('');
        setIsReviewModalOpen(true);
    };

    const handlePostReview = async () => {
        if (!newReview.trim()) {
            toast.error('Vui lòng nhập nội dung đánh giá!');
            return;
        }
        if (!selectedProductId || !Number.isInteger(selectedProductId)) {
            toast.error('Thiếu thông tin sản phẩm hoặc product_id không hợp lệ!');
            return;
        }
        if (selectedOrderDetailId === null || !Number.isInteger(selectedOrderDetailId)) {
            toast.error('Chi tiết đơn hàng không hợp lệ!');
            return;
        }
        if (rating === 0) {
            toast.error('Vui lòng chọn số sao!');
            return;
        }

        const reviewData: ReviewPayload = {
            order_detail_id: selectedOrderDetailId,
            product_id: selectedProductId,
            rating,
            content: newReview,
            is_anonymous: false,
            service: 3,
            packaging: 3,
            shipping: 3,
            customer_service: 3,
        };

        console.log("Review data being sent:", reviewData);

        try {
            console.log(`Sending review for order_id: ${currentOrder.id}, order_detail_id: ${selectedOrderDetailId}`, reviewData);
            await postReview(currentOrder.id.toString(), [reviewData]);
            toast.success("Đánh giá của bạn đã được đăng thành công!");
            setNewReview('');
            setRating(0);
            setSelectedOrderDetailId(null);
            setSelectedProductId(null);
            setIsReviewModalOpen(false);
            setReviewUpdated((prev) => prev + 1);
        } catch (error: any) {
            console.error(`Error posting review for order_id: ${currentOrder.id}, order_detail_id: ${selectedOrderDetailId}`, error.response?.data);
            const errorDetails = error.response?.data?.details || error.response?.data?.message;
            let errorMessage = error.response?.data?.errors || error.response?.data?.message || 'Lỗi khi đăng đánh giá!';
            if (errorDetails) {
                errorMessage += ` (order_detail_id: ${selectedOrderDetailId}) Chi tiết: ${typeof errorDetails === 'object' ? JSON.stringify(errorDetails) : errorDetails}`;
            }
            toast.error(errorMessage);
        }
    };

    const handleOpenCancelModal = () => {
        setCancelReason('');
        setIsCancelModalOpen(true);
    };

    const handleCancelOrder = async () => {
        if (!cancelReason.trim()) {
            toast.error('Vui lòng nhập lý do hủy đơn hàng!');
            return;
        }

        try {
            await CancellOrder(currentOrder.id);
            toast.success("Đơn hàng đã được hủy thành công!");
            setCurrentOrder({ ...currentOrder, status: "cancelled" });
            setIsCancelModalOpen(false);
        } catch (error) {
            toast.error("Lỗi khi hủy đơn hàng, vui lòng thử lại!");
            console.error("Lỗi khi hủy đơn:", error);
        }
    };

    if (!currentOrder) return <p>Không tìm thấy đơn hàng.</p>;

    const discountAmount = currentOrder.discount ? parseFloat(currentOrder.discount) : 0;

    return (
        <div className="order-detail-container">
            <h2>
                Chi tiết đơn hàng #FV-HN-{currentOrder.id} -
                <span style={{ color: getStatusColor(currentOrder.status) }}>
                    {getStatusLabel(currentOrder.status)}
                </span>
            </h2>
            <p className="order-date">Ngày đặt hàng: {new Date(currentOrder.created_at).toLocaleDateString("vi-VN")}</p>

            <div className="order-info-grid">
                <div className="info-section">
                    <h3>Địa chỉ người nhận</h3>
                    <p><strong>{currentOrder.username}</strong></p>
                    <p>Địa chỉ: {currentOrder.address}</p>
                    <p>Điện thoại: {currentOrder.phone_number}</p>
                </div>
                <div className="info-section">
                    <h3>Hình thức giao hàng</h3>
                    <p>FAST - Giao tiết kiệm</p>
                    <p>Phí vận chuyển: {parseFloat(currentOrder.deliver_fee).toLocaleString()}đ</p>
                </div>
                <div className="info-section">
                    <h3>Hình thức thanh toán</h3>
                    <p>{getPaymentStatusInVietnamese(currentOrder.payment_status)}</p>
                </div>
            </div>

            <h3>Sản phẩm</h3>
            <table className="product-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    {currentOrder.order_details.map((item) => {
                        console.log("Order detail item:", item);
                        let imageSrc = "/default.jpg";

                        if (typeof item.image === "string") {
                            try {
                                const parsed = JSON.parse(item.image);
                                imageSrc = parsed[0] || "/default.jpg";
                            } catch {
                                imageSrc = item.image;
                            }
                        }

                        return (
                            <tr key={item.id}>
                                <td>
                                    <div className="product-item">
                                        <img src={imageSrc} alt="Sản phẩm" onError={(e) => (e.currentTarget.src = "/default.jpg")} />
                                        <div className="product-info">
                                            <p>{item.product_name}</p>
                                            <p>Màu: {item.color || "Không có"}</p>
                                            <p>Kích cỡ: {item.size || "Không có"}</p>
                                            <div className="product-actions">
                                                {currentOrder.status.toLowerCase() === "completed" && (
                                                    Number.isInteger(item.id) ? (
                                                        hasReviewed[item.id] ? (
                                                            <span className="action-btn disabled">Đã đánh giá</span>
                                                        ) : (
                                                            <button
                                                                className="action-btn buy-again"
                                                                onClick={() => handleOpenReviewModal(item.id, item.product_id)}
                                                            >
                                                                Đánh giá
                                                            </button>
                                                        )
                                                    ) : (
                                                        <span className="action-btn disabled">Không thể đánh giá</span>
                                                    )
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{parseFloat(item.price).toLocaleString()}đ</td>
                                <td>{item.quantity}</td>
                                <td>{parseFloat(item.total_price).toLocaleString()}đ</td>
                            </tr>
                        );
                    })}
                </tbody>
            </table>

            <div className="order-summary">
                <p>Tạm tính: {(parseFloat(currentOrder.total_price) + discountAmount - parseFloat(currentOrder.deliver_fee)).toLocaleString()}đ</p>
                <p>Phí vận chuyển: {parseFloat(currentOrder.deliver_fee).toLocaleString()}đ</p>
                <p>
                    Giảm giá: {discountAmount > 0
                        ? `${discountAmount.toLocaleString()}đ (Mã: ${currentOrder.voucher?.name || "N/A"})`
                        : "0đ"}
                </p>
                <p className="total">Tổng cộng: {parseFloat(currentOrder.total_price).toLocaleString()}đ</p>
            </div>

            {canCancel && (
                <button className="cancel-button" onClick={handleOpenCancelModal}>
                    Hủy đơn
                </button>
            )}

            <Modal
                isOpen={isReviewModalOpen}
                onRequestClose={() => setIsReviewModalOpen(false)}
                className="review-modal"
                overlayClassName="review-modal-overlay"
            >
                <h2>Thêm đánh giá của bạn</h2>
                <div className="review-form">
                    <label>Đánh giá (1-5 sao):</label>
                    <div>{renderStars(rating, true)}</div>
                    <label>Nội dung đánh giá:</label>
                    <textarea
                        value={newReview}
                        onChange={(e) => setNewReview(e.target.value)}
                        maxLength={500}
                        placeholder="Viết đánh giá của bạn..."
                        rows={4}
                    />
                    <div className="modal-buttons">
                        <button onClick={handlePostReview} className="submit-btn">Gửi</button>
                        <button onClick={() => setIsReviewModalOpen(false)} className="cancel-btn">Hủy</button>
                    </div>
                </div>
            </Modal>

            <Modal
                isOpen={isCancelModalOpen}
                onRequestClose={() => setIsCancelModalOpen(false)}
                className="review-modal"
                overlayClassName="review-modal-overlay"
            >
                <h2>Hủy đơn hàng</h2>
                <div className="review-form">
                    <label>Lý do hủy đơn hàng:</label>
                    <textarea
                        value={cancelReason}
                        onChange={(e) => setCancelReason(e.target.value)}
                        maxLength={500}
                        placeholder="Vui lòng nhập lý do hủy đơn hàng..."
                        rows={4}
                    />
                    <div className="modal-buttons">
                        <button onClick={handleCancelOrder} className="submit-btn">Xác nhận hủy</button>
                        <button onClick={() => setIsCancelModalOpen(false)} className="cancel-btn">Đóng</button>
                    </div>
                </div>
            </Modal>
        </div>
    );
};

export default OrderDetail;