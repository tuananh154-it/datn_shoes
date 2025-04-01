import { useEffect, useState } from "react";
import { useCart } from "../context/CartContext";
import { Link } from "react-router-dom";
import Pagination from "../Pages/Pagination"; // Import component phân trang

const Cart = () => {
  const {
    cart,
    totalPrice,
    fetchCartData,
    updateCartItem,
    removeCartItem,
    selectedItems,
    toggleSelectItem,
    selectAllItems,
  } = useCart();

  // State cho phân trang
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 5; // Số sản phẩm hiển thị mỗi trang

  useEffect(() => {
    fetchCartData();
  }, []);

  // Tính toán danh sách sản phẩm hiển thị trên trang hiện tại
  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentItems = cart.slice(indexOfFirstItem, indexOfLastItem);
  const totalPages = Math.ceil(cart.length / itemsPerPage);
  return (
    <>
      <div className="menu_overlay"></div>
      <div className="main_section">
        <section className="breadcrumb_section nav">
          <div className="container">
            <nav aria-label="breadcrumb">
              <ol className="breadcrumb">
                <li className="breadcrumb-item text-capitalize">
                  <a href="/">Home</a> <i className="flaticon-arrows-4"></i>
                </li>
                <li className="breadcrumb-item active text-capitalize">Giỏ hàng</li>
              </ol>
            </nav>
            <h1 className="title_h1 font-weight-normal text-capitalize">Giỏ hàng</h1>
          </div>
        </section>

        <section className="wishlist_section login_section padding-top-60 padding-bottom-60 check_out">
          <div className="container">
            <div className="login_form">
              {cart.length === 0 ? (
                <div className="text-center">
                  <p>Giỏ hàng trống</p>
                  <Link to="/shop" className="back-shop">
                    Tiếp tục quay lại mua sắm
                  </Link>
                </div>
              ) : (
                <form>
                  <div className="cart_table">
                    <div className="table">
                      <div className="thead">
                        <div className="tr">
                          <div className="th title_h5 border-bottom border-top">Sản phẩm</div>
                          <div className="th title_h5 border-bottom border-top">Giá</div>
                          <div className="th title_h5 border-bottom border-top">Số lượng</div>
                          <div className="th title_h5 border-bottom border-top">Tổng</div>
                          <div className="th title_h5 border-bottom border-top">
                            <input
                              type="checkbox"
                              onChange={(e) => selectAllItems(e.target.checked)}
                              checked={selectedItems.length === cart.length && cart.length > 0}
                            />
                          </div>
                          <div className="th border-bottom border-top"></div>
                        </div>
                      </div>
                      <div className="tbody">
                        {currentItems.map((item) => (
                          <div className="tr" key={item.id_cart_item}>
                            <div className="td border-bottom" data-title="Product">
                              <div className="product_img d-table-cell">
                                <img
                                  src={item.image}
                                  className="vertical_middle img-fluid"
                                  alt={item.product_name}
                                />
                              </div>
                              <div className="product_details d-table-cell">
                                <div className="product_title">
                                  <h5 className="title_h5">{item.product_name}</h5>
                                </div>
                                <div className="product_variant">
                                  <p>Màu: {item.color}</p>
                                  <p>Size: {item.size}</p>
                                </div>
                              </div>
                            </div>
                            <div className="td border-bottom" data-title="Price">
                              {item.discount_price.toLocaleString()} VNĐ
                            </div>
                            <div className="td border-bottom" data-title="Quantity">
                              <div className="form-group quantity_box">
                                <div className="qty_number">
                                  <button
                                    type="button"
                                    onClick={() => updateCartItem(item.id_cart_item, item.quantity - 1)}
                                    disabled={item.quantity <= 1} 
                                  >
                                    -
                                  </button>
                                  <input type="text" value={item.quantity} readOnly />
                                  <button
                                    type="button"
                                    onClick={() => updateCartItem(item.id_cart_item, item.quantity + 1)}
                                  >
                                    +
                                  </button>
                                </div>
                              </div>
                            </div>
                            <div className="td border-bottom" data-title="Total">
                              {(item.discount_price * item.quantity).toLocaleString()} VNĐ
                            </div>
                            <div className="td border-bottom">
                              <input
                                type="checkbox"
                                checked={selectedItems.includes(item.id_cart_item)}
                                onChange={() => toggleSelectItem(item.id_cart_item)}
                              />
                            </div>
                            <div className="td remove_cart border-bottom" data-title="Remove">
                              <a href="javascript:void(0);" onClick={() => removeCartItem(item.id_cart_item)}>
                                <i className="flaticon-close"></i>
                              </a>
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  </div>

                  <div className="cart_subtotal text-right">
                    <div className="subtotal_text">Tổng thanh toán</div>
                    <div className="subtotal_price title_h4 text-right">{totalPrice.toLocaleString()}</div>
                  </div>

                  <div className="cart_btns text-right">
                    <Link to="/shop" className="text-uppercase border-btn">
                      Tiếp tục mua sắm
                    </Link>
                    <Link to="/checkout" className="text-uppercase background-btn">
                      Thanh toán
                    </Link>
                  </div>

                  {/* Phân trang */}
                  <Pagination
                    currentPage={currentPage}
                    totalPages={totalPages}
                    onPageChange={setCurrentPage}
                  />
                </form>
              )}
            </div>
          </div>
        </section>
      </div>
    </>
  );
};

export default Cart;