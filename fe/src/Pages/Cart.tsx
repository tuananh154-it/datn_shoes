import React, { useEffect } from "react";
import { useCart } from "../context/CartContext";

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

  useEffect(() => {
    fetchCartData();
  }, []);

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
                  <a href="/shop" className="background-btn text-uppercase">
                    Quay lại mua sắm
                  </a>
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
                        {cart.map((item) => (
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
                              {/* <button onClick={() => removeCartItem(item.id_cart_item)}>
                              <i className="flaticon-close"></i>
                              </button> */}
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
                    <a href="/shop" className="text-uppercase border-btn">
                      Tiếp tục mua sắm
                    </a>
                    <a href="/checkout" className="text-uppercase background-btn">
                      Thanh toán
                    </a>
                  </div>

                  <div className="form-group cart_notes">
                    <label className="title_h5" htmlFor="notes">
                      Ghi chú cho người bán
                    </label>
                    <textarea className="form-control" id="notes" name="Notes"></textarea>
                  </div>
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
