import { useEffect, useRef, useState } from "react";
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
  const [totalAddedToCart, setTotalAddedToCart] = useState(0);
  const scrollRef = useRef<HTMLDivElement>(null);

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
    if (selectedDetail) {
      setTotalAddedToCart(0);
    }
  }, [selectedDetail]);

  useEffect(() => {
    if (!productId) return;

    getProductDetail(productId).then(({ data }) => {
      const product = data.data;
      setProduct(product);

      if (product.details?.length > 0) {
        const firstDetail = product.details[0];
        setSelectedColor(firstDetail.color);
        setSelectedSize(firstDetail.size);
        setSelectedDetail(firstDetail);
      }
    });
  }, [productId]);

  const handleIncrease = () => setQuantity(quantity + 1);
  const handleDecrease = () => quantity > 1 && setQuantity(quantity - 1);

  const colorSizeMap =
    product?.details?.reduce((acc, detail) => {
      if (!acc[detail.color]) {
        acc[detail.color] = [];
      }
      if (!acc[detail.color].includes(detail.size)) {
        acc[detail.color].push(detail.size);
      }
      return acc;
    }, {} as Record<string, string[]>) || {};

  const uniqueColors = Object.keys(colorSizeMap);

  // Lấy danh sách ảnh biến thể duy nhất từ tất cả biến thể
  const uniqueVariantImages = product?.details
    ? [...new Set(product.details.map((detail) => detail.image))]
    : [];
     // Lấy danh sách ảnh từ biến thể
  const detailImages: string[] = product?.details
  ?.map(detail => detail.image?.[0])
  .filter((img): img is string => typeof img === 'string') || [];

// Kiểm tra và thêm ảnh chính nếu chưa có trong biến thể
const allImages = [...detailImages];
if (product?.image && !detailImages.includes(product.image)) {
  allImages.unshift(product.image);
}

// Lọc ảnh không trùng nhau
const uniqueImages = Array.from(new Set(allImages)).map(img => {
  return product?.details.find(detail => detail.image[0] === img) || {
    image: [img], // ảnh chính không có detail nên tạo object giả
    size: '',
    color: '',
  };
});
  const handleColorSelect = (color: string) => {
    setSelectedColor(color);
    const availableSizes = colorSizeMap[color];
    const firstSize = availableSizes[0];
    setSelectedSize(firstSize);
    const matchingDetail = product?.details.find(
      (d) => d.color === color && d.size === firstSize
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
    setSelectedColor(detail?.color || null);
    setSelectedSize(detail?.size || null);
  };

  const handleScroll = (direction: "left" | "right") => {
    const scrollContainer = scrollRef.current;
    if (scrollContainer) {
      const scrollAmount = 120;
      scrollContainer.scrollBy({
        left: direction === "right" ? scrollAmount : -scrollAmount,
        behavior: "smooth",
      });
    }
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
                  <div className="imageBienthe-wrapper" style={{ position: "relative" }}>
                    {uniqueVariantImages.length + 1 > 2 && (
                      <a className="nav-button left" onClick={() => handleScroll("left")}>
                        ‹
                      </a>
                    )}
                    <div
                      ref={scrollRef}
                      className="imageBienthe overflow-x-auto whitespace-nowrap no-scrollbar"
                      style={{ scrollBehavior: "smooth" }}
                    >
                      {/* <img
                        src={product.image}
                        alt="Product main"
                        className="inline-block w-24 h-24 object-cover mx-1 cursor-pointer rounded variant-thumb"
                        onClick={() => handleVariantClick(null)}
                      /> */}
                        {uniqueImages.map((image,index)=>(
                                              <img key={index} src={image.image[0]} alt={`Variant ${index}`} onClick={() => handleVariantClick(image)} />
                                        ))}
                    </div>
                    {uniqueVariantImages.length + 1 > 2 && (
                      <a className="nav-button right" onClick={() => handleScroll("right")}>
                        ›
                      </a>
                    )}
                  </div>
                </div>
                <div className="main-right">
                  <div className="product_content">
                    <p className="product_price">{selectedDetail?.name || product.name}</p>
                    <p className="sku_text">
                      Thương hiệu: <strong>{product.brand}</strong>
                    </p>
                    <p className="text-color title_h4">
                      {selectedDetail?.discount_price ? (
                        <>
                          <span className="original-price">
                            {formatPrice(selectedDetail?.default_price)}
                          </span>{" "}
                          <span className="discount-price">
                            {formatPrice(selectedDetail?.discount_price)}
                          </span>{" "}
                        </>
                      ) : (
                        <span className="default-price">
                          {formatPrice(selectedDetail?.default_price || product.price)}
                        </span>
                      )}
                    </p>
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
                            {selectedColor && colorSizeMap[selectedColor]?.map((size, index) => (
                              <option key={index} value={size}>{size}</option>
                            ))}
                          </select>
                        </div>

                        <div className="form-group quantity_box">
                          <label className="title_h5 text-capitalize">Số lượng</label>
                          <div className="qty_number">
                            <button type="button" onClick={handleDecrease}>
                              -
                            </button>
                            <input
                              type="text"
                              value={quantity}
                              onChange={(e) => {
                                const value = Number(e.target.value);
                                if (!isNaN(value) && value >= 1) setQuantity(value);
                              }}
                            />
                            <button type="button" onClick={handleIncrease}>
                              +
                            </button>
                          </div>
                        </div>
                      </div>

                      <div className="product_btns">
                        <button
                          type="button"
                          className="background-btn text-uppercase cart_btn"
                          onClick={async () => {
                            if (!isLoggedIn) {
                              alert("Vui lòng đăng nhập trước khi thêm vào giỏ hàng!");
                              nav("/login");
                              return;
                            }

                            if (!selectedDetail) {
                              alert("Vui lòng chọn biến thể trước khi thêm vào giỏ hàng!");
                              return;
                            }

                            try {
                              const originalQuantity = Number(selectedDetail.quantity);

                              if (quantity <= 0) {
                                toast.error("Số lượng phải lớn hơn 0");
                                return;
                              }

                              const newTotalAddedToCart = totalAddedToCart + quantity;

                              if (newTotalAddedToCart > originalQuantity) {
                                toast.error(
                                  "Không thể thêm vào giỏ hàng. Tổng số lượng đã thêm vượt quá số lượng gốc."
                                );
                                return;
                              }

                              await addToCart(Number(selectedDetail.id), quantity);
                              setTotalAddedToCart(newTotalAddedToCart);

                              console.log(
                                "Dữ liệu gửi lên API:",
                                JSON.stringify({
                                  product_detail_id: selectedDetail.id,
                                  quantity,
                                })
                              );
                              console.log("Số lượng gốc:", originalQuantity);
                              console.log("Tổng số lượng đã thêm:", newTotalAddedToCart);
                            } catch (error) {
                              console.error("Lỗi:", error);
                              toast.error("Có lỗi xảy ra khi thêm vào giỏ hàng: ");
                            }
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