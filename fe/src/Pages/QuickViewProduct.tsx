import { useEffect, useState } from "react";
import { Products } from "../types/Product";
import { useNavigate } from "react-router-dom";
import { getProductDetail } from "../services/product";
import { useCart } from "../context/CartContext";
import toast from "react-hot-toast";

const QuickViewProduct = ({ productId, onClose }: { productId: string, onClose: () => void }) => {
    const { addToCart } = useCart();
    const isLoggedIn = localStorage.getItem('token') ? true : false;
    const nav = useNavigate();
    const [quantity, setQuantity] = useState<number>(1);
    const [product, setProduct] = useState<Products | null>(null);
    const [selectedDetail, setSelectedDetail] = useState<any>(null);

    useEffect(() => {
        if (!productId) return;
        getProductDetail(productId).then(({ data }) => {
            setProduct(data.data);
        });
    }, [productId]);

    const handleIncrease = () => setQuantity(quantity + 1);
    const handleDecrease = () => quantity > 1 && setQuantity(quantity - 1);

    const handleVariantClick = (detail: any) => {
        setSelectedDetail(detail || null);
    };

    function getColorFromText(colorText: any) {
        switch (colorText.toLowerCase()) {
            case "màu vàng": return "#FFCC00";
            case "màu đỏ": return "#FF0000";
            case "màu xanh": return "#008000";
            default: return "#000000";
        }
    }

    return (
        <div className="quickview-overlay">
        <div className="main_section">
            <button className="quickview-close" onClick={onClose}>×</button>
            <section className="product_detail_section">
                {product ? (
                    <div className="container">
                        <div className="main">
                            <div className="main-left">
                                <div className="imageProduct">
                                    <img src={selectedDetail?.image || product.image} alt="Product" />
                                </div>
                                <div className="imageBienthe">
                                    <img src={product.image} alt="Product main" onClick={() => handleVariantClick(null)} />
                                    {product.details.map((detail, index) => (
                                        <img key={index} src={detail.image} alt={`Variant ${index}`} onClick={() => handleVariantClick(detail)} />
                                    ))}
                                </div>
                            </div>
                            <div className="main-right">
                                <div className="product_content">
                                    <p className="product_price">{selectedDetail?.name || product.name}</p>
                                    <p className="sku_text">Thương hiệu: <strong>{product.brand}</strong></p>
                                    <p className="text-color">{selectedDetail?.discount_price || product.price}</p>
                                    <p>Số lượng: {selectedDetail?.quantity}</p>
                                    <div className="star">⭐ (1 Review)</div>
                                    <form>
                                        <div className="product_variant">
                                            <div className="form-group color_box">
                                                <label>Màu sắc</label>
                                                {product.details.map((detail, index) => (
                                                    <div key={index} className="radio">
                                                        <input type="radio" name="color" id={`color${index}`} checked={selectedDetail?.id === detail.id} onChange={() => handleVariantClick(detail)} />
                                                        <label htmlFor={`color${index}`} style={{ backgroundColor: getColorFromText(detail.color) }}></label>
                                                    </div>
                                                ))}
                                            </div>
                                            <div className="form-group quantity_box">
                                                <label>Số lượng</label>
                                                <div className="qty_number">
                                                    <button type="button" onClick={handleDecrease}>-</button>
                                                    <input type="text" value={quantity} readOnly />
                                                    <button type="button" onClick={handleIncrease}>+</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="product_btns">
                                            <button
                                                type="button"
                                                className="background-btn text-uppercase cart_btn"
                                                onClick={() => {
                                                    // Kiểm tra nếu người dùng chưa đăng nhập
                                                    if (!isLoggedIn) {
                                                        alert("Vui lòng đăng nhập trước khi thêm vào giỏ hàng!");
                                                        nav("/login")
                                                        return;
                                                    }

                                                    // Kiểm tra xem biến thể đã được chọn chưa
                                                    if (!selectedDetail) {
                                                        alert("Vui lòng chọn biến thể trước khi thêm vào giỏ hàng!");
                                                        return;
                                                    }

                                                    // Thêm sản phẩm vào giỏ hàng
                                                    addToCart(Number(selectedDetail.id), quantity);
                                                    toast.success("Thêm vào giỏ hàng thành công");

                                                    // Log dữ liệu gửi lên API
                                                    console.log("Dữ liệu gửi lên API:", JSON.stringify({
                                                        product_detail_id: selectedDetail.id,
                                                        quantity
                                                    }));
                                                }}
                                            >
                                                Thêm vào giỏ hàng
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                ) : (
                    <div className="container">
                        <p>Đang tải...</p>
                    </div>
                )}
            </section>
        </div>
        </div>
    );
};

export default QuickViewProduct;