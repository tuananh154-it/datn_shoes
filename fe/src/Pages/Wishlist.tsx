
import { useEffect, useState } from "react";
import { useCart } from "../context/CartContext";
import { Productyeuthich, Detail } from "../types/Product";
import { getProductDetail } from "../services/product";
import toast from "react-hot-toast";
import { Link } from "react-router-dom";

const Wishlist = () => {
  const { addToCart } = useCart();
  const [wishlistProducts, setWishlistProducts] = useState<Productyeuthich[]>(
    []
  );
  const [selectedDetails, setSelectedDetails] = useState<{
    [key: number]: Detail;
  }>({});
  const [quantities, setQuantities] = useState<{ [key: number]: number }>({});

  useEffect(() => {
    const wishlistIds = JSON.parse(localStorage.getItem("wishlist") || "[]");
    if (wishlistIds.length === 0) return;

    Promise.all(wishlistIds.map((id: string) => getProductDetail(id)))
      .then((responses) => {
        const products = responses.map(({ data }) => data.data);
        setWishlistProducts(products);

        // Khởi tạo state số lượng và biến thể mặc định
        const initialDetails: { [key: number]: Detail } = {};
        const initialQuantities: { [key: number]: number } = {};

        products.forEach((product) => {
          if (product.details.length > 0) {
            initialDetails[product.id] = product.details[0]; // Chọn biến thể đầu tiên mặc định
            initialQuantities[product.id] = 1; // Số lượng mặc định là 1
          }
        });

        setSelectedDetails(initialDetails);
        setQuantities(initialQuantities);
      })
      .catch((error) =>
        console.error("Lỗi khi lấy sản phẩm yêu thích:", error)
      );
  }, []);

  // Hàm tăng số lượng
  const handleIncrease = (productId: number) => {
    setQuantities((prev) => ({ ...prev, [productId]: prev[productId] + 1 }));
  };

  // Hàm giảm số lượng
  const handleDecrease = (productId: number) => {
    setQuantities((prev) => ({
      ...prev,
      [productId]: prev[productId] > 1 ? prev[productId] - 1 : 1,
    }));
  };

  const handleVariantChange = (productId: number, detail: Detail) => {
    setSelectedDetails((prev) => ({ ...prev, [productId]: detail }));
  };

  const handleAddToCart = (
    productId: number,
    detailId: number,
    quantity: number
  ) => {
    addToCart(detailId, quantity);
    toast.success("Thêm vào giỏ hàng thành công");
  
    // Xóa sản phẩm khỏi wishlist
    setWishlistProducts((prev) => prev.filter((p) => p.id !== productId));
  
    // Cập nhật localStorage
    const updatedWishlist = JSON.parse(
      localStorage.getItem("wishlist") || "[]"
    ).filter((id: number) => id !== productId);
    localStorage.setItem("wishlist", JSON.stringify(updatedWishlist));
  
    // Phát sự kiện cập nhật để header tự động cập nhật
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
          // ✅ Hiển thị danh sách sản phẩm yêu thích
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
                  const selectedDetail = selectedDetails[product.id] || product.details[0];
                  const quantity = quantities[product.id] || 1;
                  const price = selectedDetail?.discount_price
                    ? Number(selectedDetail.discount_price.replace(/,/g, "").replace(" VND", "")) * quantity
                    : Number(selectedDetail.default_price.replace(/,/g, "").replace(" VND", "")) * quantity;

                  return (
                    <div className="tr" key={product.id}>
                      <div className="td border-bottom" data-title="Product">
                        <div className="product_img d-table-cell">
                          <img src={selectedDetail?.image || product.image} alt={product.name} />
                        </div>
                        <div className="product_details d-table-cell">
                          <div className="product_title">
                            <a href="product_list_detail.html">
                              <h5 className="title_h5">{product.name}</h5>
                            </a>
                          </div>
                        </div>
                      </div>
                      <div className="td border-bottom" data-title="Price">
                        <p className="price">{price.toLocaleString("vi-VN")} VND</p>
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
                                const newDetail = product.details.find((d) => d.size === e.target.value);
                                if (newDetail) handleVariantChange(product.id, newDetail);
                              }}
                            >
                              {product.details.map((detail) => (
                                <option key={detail.id} value={detail.size}>{detail.size}</option>
                              ))}
                            </select>
                          </div>
                          <div className="options">
                            <label htmlFor="colors" className="title_h5">Màu:</label>
                            <select
                              className="form-control chon"
                              onChange={(e) => {
                                const newDetail = product.details.find((d) => d.color === e.target.value);
                                if (newDetail) handleVariantChange(product.id, newDetail);
                              }}
                            >
                              {product.details.map((detail) => (
                                <option key={detail.id} value={detail.color}>{detail.color}</option>
                              ))}
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
