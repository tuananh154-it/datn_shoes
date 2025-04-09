import { useEffect, useState } from "react";
import { Products } from "../types/Product";
import { useNavigate } from "react-router-dom";
import { getProductDetail } from "../services/product";
import { useCart } from "../context/CartContext";
import toast from "react-hot-toast";

const QuickViewProduct = ({ productId, onClose }: { productId: string; onClose: () => void }) => {
    const { addToCart } = useCart();
    const isLoggedIn = !!localStorage.getItem("token");
    const nav = useNavigate();
    const [quantity, setQuantity] = useState<number>(1);
    const [product, setProduct] = useState<Products | null>(null);
    const [selectedDetail, setSelectedDetail] = useState<any>(null);
    const [selectedColor, setSelectedColor] = useState<string | null>(null);
    const [selectedSize, setSelectedSize] = useState<string | null>(null);

    const formatPrice = (price: string | number | undefined) => {
        if (price === undefined) return "0 VND";
        if (typeof price === "number") {
            return price.toLocaleString("vi-VN") + " VND";
        }
        if (typeof price === "string") {
            return Number(price.replace(/,/g, "").replace(" VND", "")).toLocaleString("vi-VN") + " VND";
        }
        return "0 VND";
    };

    useEffect(() => {
        if (!productId) return;
        getProductDetail(productId).then(({ data }) => {
            setProduct(data.data);
        });
    }, [productId]);

    const handleIncrease = () => setQuantity(quantity + 1);
    const handleDecrease = () => quantity > 1 && setQuantity(quantity - 1);

    const colorSizeMap =
        product?.details?.reduce((acc, detail) => {
            if (!acc[detail.color]) {
                acc[detail.color] = [];
            }
            acc[detail.color].push(detail.size);
            return acc;
        }, {} as Record<string, string[]>) || {};

    const uniqueColors = Object.keys(colorSizeMap);

    const getVariantImagesForColor = (color: string) => {
        const colorDetails = product?.details.filter((detail) => detail.color === color) || [];
        return colorDetails.flatMap((detail) => detail.image);
    };

    const handleColorSelect = (color: string) => {
        setSelectedColor(color);
        setSelectedSize(colorSizeMap[color][0]);
        const matchingDetail = product?.details.find(
            (d) => d.color === color && d.size === colorSizeMap[color][0]
        );
        setSelectedDetail(matchingDetail || null);
    };

    const handleSizeSelect = (size: string) => {
        setSelectedSize(size);
        const matchingDetail = product?.details.find(
            (d) => d.color === selectedColor && d.size === size
        );
        setSelectedDetail(matchingDetail || null);
    };

    const handleVariantClick = (detail: any) => {
        setSelectedDetail(detail || null);
    };

    function getColorFromText(colorText: string): string {
        switch (colorText.toLowerCase()) {
            case "màu trắng": return "#FFFFFF";
            case "màu đen": return "#000000";
            case "màu đỏ": return "#FF0000";
            case "màu xanh dương": return "#0000FF";
            case "màu xanh lá": return "#008000";
            case "màu vàng": return "#FFFF00";
            case "màu cam": return "#FFA500";
            case "màu tím": return "#800080";
            case "màu hồng": return "#FFC0CB";
            case "màu nâu": return "#A52A2A";
            case "màu xám": return "#808080";
            case "màu xanh ngọc": return "#00CED1";
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
                                        <p className="text-color">{formatPrice(selectedDetail?.discount_price || product.price)}</p>
                                        <p>Số lượng: {selectedDetail?.quantity}</p>
                                        <div className="star">⭐ (1 Review)</div>

                                        <form>
                                            <div className="product_variant">
                                                <div className="form-group color_box">
                                                    <label className="title_h5 text-capitalize">Color</label>
                                                    {uniqueColors.map((color, index) => (
                                                        <div key={index} className="radio p-2">
                                                            <input
                                                                type="radio"
                                                                name="color"
                                                                id={`color${index}`}
                                                                checked={selectedColor === color}
                                                                onChange={() => handleColorSelect(color)}
                                                            />
                                                            <label
                                                                htmlFor={`color${index}`}
                                                                style={{ backgroundColor: getColorFromText(color) }}
                                                            ></label>
                                                        </div>
                                                    ))}
                                                </div>

                                                <div className="form-group size_box">
                                                    <label className="title_h5 text-capitalize">Kích thước</label>
                                                    <select
                                                        className="form-control"
                                                        value={selectedSize || ""}
                                                        onChange={(e) => handleSizeSelect(e.target.value)}
                                                    >
                                                        {selectedColor &&
                                                            colorSizeMap[selectedColor].map((size, index) => (
                                                                <option key={index} value={size}>{size}</option>
                                                            ))}
                                                    </select>
                                                </div>

                                                <div className="form-group quantity_box">
                                                    <label className="title_h5 text-capitalize">Số lượng</label>
                                                    <div className="qty_number">
                                                        <button type="button" onClick={handleDecrease}>-</button>
                                                        <input
                                                            type="text"
                                                            value={quantity}
                                                            onChange={(e) => {
                                                                const value = Number(e.target.value);
                                                                if (!isNaN(value) && value >= 1) setQuantity(value);
                                                            }}
                                                        />
                                                        <button type="button" onClick={handleIncrease}>+</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div className="product_btns">
                                                <button
                                                    type="button"
                                                    className="background-btn text-uppercase cart_btn"
                                                    onClick={() => {
                                                        if (!isLoggedIn) {
                                                            alert("Vui lòng đăng nhập trước khi thêm vào giỏ hàng!");
                                                            nav("/login");
                                                            return;
                                                        }
                                                        if (!selectedDetail) {
                                                            alert("Vui lòng chọn biến thể trước khi thêm vào giỏ hàng!");
                                                            return;
                                                        }
                                                        addToCart(Number(selectedDetail.id), quantity);
                                                        toast.success("Thêm vào giỏ hàng thành công");
                                                        console.log(
                                                            "Dữ liệu gửi lên API:",
                                                            JSON.stringify({
                                                                product_detail_id: selectedDetail.id,
                                                                quantity,
                                                            })
                                                        );
                                                    }}
                                                >
                                                    Add to cart
                                                </button>
                                                <div className="product_share">
                                                    <p>Share the love</p>
                                                    <ul className="social_icons">
                                                        <li className="text-center">
                                                            <a href="javascript:void(0);">
                                                                <i className="flaticon-facebook vertical_middle"></i>
                                                            </a>
                                                        </li>
                                                        <li className="text-center">
                                                            <a href="javascript:void(0);">
                                                                <i className="flaticon-pinterest vertical_middle"></i>
                                                            </a>
                                                        </li>
                                                        <li className="text-center">
                                                            <a href="javascript:void(0);">
                                                                <i className="flaticon-instagram-logo vertical_middle"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
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