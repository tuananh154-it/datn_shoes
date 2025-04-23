import { useEffect, useState } from "react";
import { useCart } from "../context/CartContext";
import { Productyeuthich } from "../types/Product";
import { getProductDetail } from "../services/product";
import toast from "react-hot-toast";
import { Link, useNavigate } from "react-router-dom";
import QuickViewProduct from "./QuickViewProduct"; // Import QuickViewProduct

const Wishlist = () => {
  const { addToCart } = useCart();
  const [wishlistProducts, setWishlistProducts] = useState<Productyeuthich[]>([]);
  const [quantities, setQuantities] = useState<{ [key: number]: number }>({});
  const [showQuickView, setShowQuickView] = useState(false); // State để hiển thị modal
  const [selectedProductId, setSelectedProductId] = useState<string | null>(null); // State để lưu productId
  const nav = useNavigate();

  useEffect(() => {
    const fetchProducts = async () => {
      const wishlistIds = JSON.parse(localStorage.getItem("wishlist") || "[]");
      if (wishlistIds.length === 0) {
        setWishlistProducts([]);
        return;
      }

      const productPromises = wishlistIds.map(async (id: string) => {
        try {
          const response = await getProductDetail(id);
          return response.data.data;
        } catch (error) {
          console.error(`Lỗi khi lấy sản phẩm với ID ${id}:`, error);
          toast.error(`Sản phẩm với ID ${id} không còn tồn tại.`);
          return null;
        }
      });

      const products = (await Promise.all(productPromises)).filter(Boolean);
      if (products.length < wishlistIds.length) {
        toast.warn("Một số sản phẩm trong danh sách yêu thích không còn tồn tại.");
        const validIds = products.map((product) => product.id);
        localStorage.setItem("wishlist", JSON.stringify(validIds));
        window.dispatchEvent(new Event("storage"));
      }
      setWishlistProducts(products);

      const initialQuantities: { [key: number]: number } = {};
      products.forEach((product) => {
        initialQuantities[product.id] = 1;
      });
      setQuantities(initialQuantities);
    };

    fetchProducts().catch((error) => {
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

  const handleRemoveFromWishlist = (productId: number) => {
    setWishlistProducts((prev) => prev.filter((p) => p.id !== productId));
    const updatedWishlist = JSON.parse(localStorage.getItem("wishlist") || "[]").filter(
      (id: number) => id !== productId
    );
    localStorage.setItem("wishlist", JSON.stringify(updatedWishlist));
    toast.success("Đã xóa sản phẩm khỏi danh sách yêu thích");
    window.dispatchEvent(new Event("storage"));
  };

  const openQuickView = (productId: number) => {
    setSelectedProductId(productId.toString());
    setShowQuickView(true);
  };

  const closeQuickView = () => {
    setShowQuickView(false);
    setSelectedProductId(null);
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
                        <div className="th title_h5 border-bottom border-top">Sản Phẩm</div>
                        <div className="th title_h5 border-bottom border-top"></div>
                        <div className="th title_h5 border-bottom border-top"></div>
                        <div className="th title_h5 border-bottom border-top"></div>
                        <div className="th title_h5 border-bottom border-top"></div>
                      </div>
                    </div>
                    <div className="tbody">
                      {wishlistProducts.map((product) => {
                        const quantity = quantities[product.id] || 1;
                        const price = product.details?.[0]?.discount_price
                          ? parseFloat(product.details[0].discount_price.replace(/,/g, "").replace(" VND", "") || "0") * quantity
                          : product.details?.[0]?.default_price
                          ? parseFloat(product.details[0].default_price.replace(/,/g, "").replace(" VND", "") || "0") * quantity
                          : 0;

                        return (
                          <div className="tr" key={product.id}>
                            <div className="td border-bottom" data-title="Product">
                              <div className="product_img d-table-cell">
                                <img
                                  src={product.details?.[0]?.image || product.image || "placeholder.jpg"}
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
                              {/* <p className="price">{price ? price.toLocaleString("vi-VN") + " VND" : "N/A"}</p> */}
                            </div>
                            <div className="td border-bottom" data-title="Quantity">
                              {/* <div className="form-group quantity_box d-inline-block">
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
                              </div> */}
                            </div>
                            <div className="td cart_bag border-bottom" data-title="Add To Bag">
                              <a
                                type="button"
                                onClick={() => openQuickView(product.id)}
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

      {/* Hiển thị modal QuickViewProduct khi showQuickView là true */}
      {showQuickView && selectedProductId && (
        <QuickViewProduct
          productId={selectedProductId}
          onClose={closeQuickView}
        />
      )}
    </>
  );
};

export default Wishlist;