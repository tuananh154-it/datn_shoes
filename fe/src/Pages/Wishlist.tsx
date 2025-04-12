import { useEffect, useState } from "react";
import { useCart } from "../context/CartContext";
import { Productyeuthich, Detail } from "../types/Product";
import { getProductDetail } from "../services/product";
import toast from "react-hot-toast";
import { Link, useNavigate } from "react-router-dom";
// import "./styles.css"; // Đảm bảo import CSS nếu cần

const Wishlist = () => {
  const { addToCart } = useCart();
  const [wishlistProducts, setWishlistProducts] = useState<Productyeuthich[]>([]);
  const [selectedDetails, setSelectedDetails] = useState<{ [key: number]: Detail }>({});
  const [quantities, setQuantities] = useState<{ [key: number]: number }>({});
  const nav = useNavigate();

  useEffect(() => {
    const wishlistIds = JSON.parse(localStorage.getItem("wishlist") || "[]");
    if (wishlistIds.length === 0) {
      setWishlistProducts([]);
      return;
    }

    Promise.all(wishlistIds.map((id: string) => getProductDetail(id)))
      .then((responses) => {
        console.log("API responses:", responses); // Debug dữ liệu API
        const products = responses.map(({ data }) => data.data).filter(Boolean);
        setWishlistProducts(products);

        const initialDetails: { [key: number]: Detail } = {};
        const initialQuantities: { [key: number]: number } = {};

        products.forEach((product) => {
          if (product.details && product.details.length > 0) {
            initialDetails[product.id] = product.details[0];
            initialQuantities[product.id] = 1;
          }
        });

        setSelectedDetails(initialDetails);
        setQuantities(initialQuantities);
      })
      .catch((error) => {
        console.error("Lỗi khi lấy sản phẩm yêu thích:", error);
        setWishlistProducts([]);
      });
  }, []);

  const handleIncrease = (productId: number) => {
    setQuantities((prev) => ({ ...prev, [productId]: (prev[productId] || 1) + 1 }));
  };

  const handleDecrease = (productId: number) => {
    setQuantities((prev) => ({
      ...prev,
      [productId]: prev[productId] > 1 ? prev[productId] - 1 : 1,
    }));
  };

  const handleVariantChange = (productId: number, detail: Detail) => {
    setSelectedDetails((prev) => ({ ...prev, [productId]: detail }));
  };

  const handleAddToCart = (productId: number, detailId: number, quantity: number) => {
    const user = JSON.parse(localStorage.getItem("user") || "null");

    if (!user) {
      alert("Bạn cần đăng nhập để thêm sản phẩm vào trong giỏ hàng!");
      nav("/login");
      return;
    }
    addToCart(detailId, quantity);
    toast.success("Thêm vào giỏ hàng thành công");

    // setWishlistProducts((prev) => prev.filter((p) => p.id !== productId));
    // const updatedWishlist = JSON.parse(localStorage.getItem("wishlist") || "[]").filter(
    //   (id: number) => id !== productId
    // );
    // localStorage.setItem("wishlist", JSON.stringify(updatedWishlist));
    // window.dispatchEvent(new Event("storage"));
  };

  // Thêm hàm xóa sản phẩm yêu thích
  const handleRemoveFromWishlist = (productId: number) => {
    setWishlistProducts((prev) => prev.filter((p) => p.id !== productId));
    const updatedWishlist = JSON.parse(localStorage.getItem("wishlist") || "[]").filter(
      (id: number) => id !== productId
    );
    localStorage.setItem("wishlist", JSON.stringify(updatedWishlist));
    toast.success("Đã xóa sản phẩm khỏi danh sách yêu thích");
    window.dispatchEvent(new Event("storage"));
  };

  return (
    <>
      <section className="breadcrumb_section nav">
        <div className="container">
          <nav aria-label="breadcrumb">
            <ol className="breadcrumb">
              <li className="breadcrumb-item text-capitalize">
                <a href="earthyellow.html">Home</a> <i className="flaticon-arrows-4"></i>
              </li>
              <li className="breadcrumb-item active text-capitalize">Sản phẩm yêu thích</li>
            </ol>
          </nav>
          <h1 className="title_h1 font-weight-normal text-capitalize">Sản phẩm yêu thích</h1>
        </div>
      </section>
      <div className="wishlist_section">
        <div className="wishlist_container">
          <section className="wishlist_section padding-top-60 padding-bottom-60">
            <div className="container">
              {wishlistProducts.length === 0 ? (
                <div className="text-center">
                  <h3 className="title_h3">Không có sản phẩm yêu thích</h3>
                  <p>Hãy thêm sản phẩm vào danh sách yêu thích để xem sau!</p>
                  <Link to="/shop" className="btn btn-primary">
                    Tiếp tục mua sắm
                  </Link>
                </div>
              ) : (
                <div className="cart_table">
                  <div className="table">
                    <div className="thead">
                      <div className="tr">
                        <div className="th title_h5 border-bottom border-top">Ảnh</div>
                        <div className="th title_h5 border-bottom border-top">Giá</div>
                        <div className="th title_h5 border-bottom border-top">Số lượng</div>
                        <div className="th title_h5 border-bottom border-top">Tùy chọn</div>
                        <div className="th title_h5 border-bottom border-top"></div>
                        <div className="th title_h5 border-bottom border-top"></div>
                      </div>
                    </div>
                    <div className="tbody">
                      {wishlistProducts.map((product) => {
                        const selectedDetail = selectedDetails[product.id] || product.details?.[0];
                        const quantity = quantities[product.id] || 1;
                        const price = selectedDetail?.discount_price
                          ? Number(selectedDetail.discount_price.replace(/,/g, "").replace(" VND", "")) * quantity
                          : selectedDetail?.default_price
                          ? Number(selectedDetail.default_price.replace(/,/g, "").replace(" VND", "")) * quantity
                          : 0;

                        return (
                          <div className="tr" key={product.id}>
                            <div className="td border-bottom" data-title="Product">
                              <div className="product_img d-table-cell">
                                <img
                                  src={selectedDetail?.image || product.image || "placeholder.jpg"}
                                  className="vertical_middle img-fluid"
                                  alt={product.name || "Product"}
                                />
                              </div>
                              <div className="product_details d-table-cell">
                                <div className="product_title">
                                  <a href="product_list_detail.html">
                                    <h5 className="title_h5">{product.name || "N/A"}</h5>
                                  </a>
                                </div>
                              </div>
                            </div>
                            <div className="td border-bottom" data-title="Price">
                              <p className="price">{price ? price.toLocaleString("vi-VN") + " VND" : "N/A"}</p>
                            </div>
                            <div className="td border-bottom" data-title="Quantity">
                              <div className="form-group quantity_box d-inline-block">
                                <div className="qty_number">
                                  <button
                                    type="button"
                                    onClick={() => handleDecrease(product.id)}
                                    style={{ padding: "5px 10px", cursor: "pointer" }}
                                  >
                                    -
                                  </button>
                                  <input type="text" value={quantity} readOnly />
                                  <button
                                    type="button"
                                    onClick={() => handleIncrease(product.id)}
                                    style={{ padding: "5px 10px", cursor: "pointer" }}
                                  >
                                    +
                                  </button>
                                </div>
                              </div>
                            </div>
                            <div className="td border-bottom" data-title="Options">
                              <div className="wishlist_variant">
                                <div className="options">
                                  <label htmlFor="sizes" className="title_h5">Size:</label>
                                  <select
                                    className="form-control chon"
                                    onChange={(e) => {
                                      const newDetail = product.details?.find((d) => d.size === e.target.value);
                                      if (newDetail) handleVariantChange(product.id, newDetail);
                                    }}
                                    value={selectedDetail?.size || ""}
                                  >
                                    {product.details?.map((detail) => (
                                      <option key={detail.id} value={detail.size}>
                                        {detail.size}
                                      </option>
                                    )) || <option value="">Không có kích thước</option>}
                                  </select>
                                </div>
                                <div className="options">
                                  <label htmlFor="colors" className="title_h5">Màu:</label>
                                  <select
                                    className="form-control chon"
                                    onChange={(e) => {
                                      const newDetail = product.details?.find((d) => d.color === e.target.value);
                                      if (newDetail) handleVariantChange(product.id, newDetail);
                                    }}
                                    value={selectedDetail?.color || ""}
                                  >
                                    {product.details?.map((detail) => (
                                      <option key={detail.id} value={detail.color}>
                                        {detail.color}
                                      </option>
                                    )) || <option value="">Không có màu</option>}
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div className="td cart_bag border-bottom" data-title="Add To Bag">
                              <a
                                type="button"
                                onClick={() => {
                                  if (!selectedDetail) {
                                    alert("Vui lòng chọn biến thể trước khi thêm vào giỏ hàng!");
                                    return;
                                  }
                                  handleAddToCart(product.id, Number(selectedDetail.id), quantity);
                                }}
                              >
                                <i className="flaticon-shopping-bag"></i>
                              </a>
                            </div>
                            <div className="td remove_cart border-bottom text-right" data-title="Remove">
                              <a
                                href="javascript:void(0);"
                                onClick={() => handleRemoveFromWishlist(product.id)}
                              >
                                <i className="flaticon-close"></i>
                              </a>
                            </div>
                          </div>
                        );
                      })}
                    </div>
                  </div>
                </div>
              )}
            </div>
          </section>
        </div>
      </div>
    </>
  );
};

export default Wishlist;