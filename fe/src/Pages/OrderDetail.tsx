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
    const [isReviewModalOpen, setIsReviewModalOpen] = useState(false); // Modal cho đánh giá
    const [isCancelModalOpen, setIsCancelModalOpen] = useState(false); // Modal cho hủy đơn
    const [selectedProductId, setSelectedProductId] = useState<number | null>(null);
    const [rating, setRating] = useState<number>(0);
    const [newReview, setNewReview] = useState<string>('');
    const [cancelReason, setCancelReason] = useState<string>(''); // Lý do hủy đơn
    const [hasReviewed, setHasReviewed] = useState<{ [key: number]: boolean }>({});
    const [reviewUpdated, setReviewUpdated] = useState<number>(0);

    // Chỉ cho phép hủy khi trạng thái là "pending"
    const canCancel = currentOrder.status.toLowerCase() === "pending";

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
                console.log("Dữ liệu từ API /reviews:", response.data);

                let reviews = [];
                if (response.data && Array.isArray(response.data.my_reviews)) {
                    reviews = response.data.my_reviews;
                } else {
                    console.error("Dữ liệu từ API không chứa mảng my_reviews:", response.data);
                    reviews = [];
                }

                const reviewedProducts: { [key: number]: boolean } = {};
                currentOrder.order_details.forEach((item) => {
                    const hasReviewed = reviews.some((review: any) =>
                        review.product_id === item.product_id &&
                        review.order_id === currentOrder.id
                    );
                    reviewedProducts[item.product_id] = hasReviewed;
                });

                console.log("Reviewed products:", reviewedProducts);
                setHasReviewed(reviewedProducts);
            } catch (error) {
                console.error("Lỗi khi kiểm tra đánh giá:", error);
                const reviewedProducts: { [key: number]: boolean } = {};
                currentOrder.order_details.forEach((item) => {
                    reviewedProducts[item.product_id] = false;
                });
                setHasReviewed(reviewedProducts);
            }
        };

        if (currentOrder.status.toLowerCase() === "completed") {
            checkReviews();
        }
    }, [currentOrder, reviewUpdated]);

    console.log(currentOrder);

    const handleOpenReviewModal = (productId: number) => {
        if (hasReviewed[productId]) {
            toast.error("Bạn đã đánh giá sản phẩm này rồi.");
            return;
        }
        setSelectedProductId(productId);
        setRating(0);
        setNewReview('');
        setIsReviewModalOpen(true);
    };

    const handlePostReview = async () => {
        if (!newReview.trim()) {
            alert('Vui lòng nhập nội dung đánh giá!');
            return;
        }
        if (!selectedProductId) {
            alert('Không có sản phẩm được chọn để đánh giá!');
            return;
        }
        if (rating === 0) {
            alert('Vui lòng chọn số sao!');
            return;
        }

        const reviewData: ReviewPayload = {
            rating,
            content: newReview,
        };

        try {
            await postReview(
                selectedProductId.toString(),
                currentOrder.id.toString(),
                reviewData
            );
            toast.success("Đánh giá của bạn đã được đăng thành công!");
            setNewReview('');
            setRating(0);
            setIsReviewModalOpen(false);
            setReviewUpdated((prev) => prev + 1);
        } catch (error: any) {
            alert(error.message || 'Lỗi khi đăng đánh giá!');
        }
    };

    const handleOpenCancelModal = () => {
        setCancelReason(''); // Reset lý do hủy đơn
        setIsCancelModalOpen(true);
    };

    const handleCancelOrder = async () => {
        if (!cancelReason.trim()) {
            alert('Vui lòng nhập lý do hủy đơn hàng!');
            return;
        }

        try {
            await CancellOrder(currentOrder.id);
            toast.success("Đơn hàng đã được hủy thành công!");
            setCurrentOrder({ ...currentOrder, status: "cancelled" });
            setIsCancelModalOpen(false);
        } catch (error) {
            alert("Lỗi khi hủy đơn hàng, vui lòng thử lại!");
            console.error("Lỗi khi hủy đơn:", error);
        }
    };

    if (!currentOrder) return <p>Không tìm thấy đơn hàng.</p>;

    // Sử dụng discount từ API
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
                    {currentOrder.order_details.map((item, index) => {
                        const key = `${item.product_name}-${item.color}-${item.size}-${index}`;
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
                            <tr key={key}>
                                <td>
                                    <div className="product-item">
                                        <img src={imageSrc} alt="Sản phẩm" onError={(e) => (e.currentTarget.src = "/default.jpg")} />
                                        <div className="product-info">
                                            <p>{item.product_name}</p>
                                            <p>Màu: {item.color || "Không có"}</p>
                                            <p>Kích cỡ: {item.size || "Không có"}</p>
                                            <div className="product-actions">
                                                {currentOrder.status.toLowerCase() === "completed" && (
                                                    hasReviewed[item.product_id] ? (
                                                        <span className="action-btn disabled">Đã đánh giá</span>
                                                    ) : (
                                                        <button
                                                            className="action-btn buy-again"
                                                            onClick={() => handleOpenReviewModal(item.product_id)}
                                                        >
                                                            Đánh giá
                                                        </button>
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

            {/* Modal cho đánh giá */}
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

            {/* Modal cho hủy đơn hàng */}
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