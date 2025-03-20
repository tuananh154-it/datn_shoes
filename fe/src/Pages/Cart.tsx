import React from 'react';

const Cart = () => {
  return (
    <>
     <div className="menu_overlay"></div>
      <div className="main_section">
        <section className="breadcrumb_section nav">
          <div className="container">
            <nav aria-label="breadcrumb">
              <ol className="breadcrumb">
                <li className="breadcrumb-item text-capitalize">
                  <a href="earthyellow.html">Home</a> <i className="flaticon-arrows-4"></i>
                </li>
                <li className="breadcrumb-item active text-capitalize">Your Cart</li>
              </ol>
            </nav>
            <h1 className="title_h1 font-weight-normal text-capitalize">Your Cart</h1>
          </div>
        </section>

        <section className="cart_section login_section padding-top-60 padding-bottom-60 check_out">
          <div className="container">
            <div className="login_form">
              <form>
                <div className="cart_table">
                  <div className="table">
                    <div className="thead">
                      <div className="tr">
                        <div className="th title_h5 border-bottom border-top">Product</div>
                        <div className="th title_h5 border-bottom border-top">Price</div>
                        <div className="th title_h5 border-bottom border-top">Quantity</div>
                        <div className="th title_h5 border-bottom border-top">Total</div>
                        <div className="th border-bottom border-top"></div>
                      </div>
                    </div>
                    <div className="tbody">
                      <div className="tr">
                        <div className="td border-bottom" data-title="Product">
                          <div className="product_img d-table-cell">
                            <img
                              src="src/images/blue_jacket_img.png"
                              className="vertical_middle img-fluid"
                              alt="Blue Jacket"
                            />
                          </div>
                          <div className="product_details d-table-cell">
                            <div className="product_title">
                              <a href="product_list_detail.html">
                                <h5 className="title_h5">Blue Jacket</h5>
                              </a>
                            </div>
                            <div className="product_variant">
                              <p>Color: Blue</p>
                              <p>Size: XL</p>
                            </div>
                          </div>
                        </div>
                        <div className="td border-bottom" data-title="Price">
                          $59.95
                        </div>
                        <div className="td border-bottom" data-title="Quantity">
                          <div className="form-group quantity_box">
                            <div className="qty_number">
                              <input type="text" defaultValue="1" />
                            </div>
                          </div>
                        </div>
                        <div className="td border-bottom" data-title="Total">
                          $59.95
                        </div>
                        <div className="td remove_cart border-bottom" data-title="Remove">
                          <a href="javascript:void(0);">
                            <i className="flaticon-close"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div className="cart_subtotal text-right">
                  <div className="subtotal_text">Subtotal</div>
                  <div className="subtotal_price title_h4 text-right">$59.95</div>
                </div>
                <div className="cart_btns text-right">
                  <button type="submit" className="border-btn text-uppercase">
                    Update Cart
                  </button>
                  <a
                    href="product_list_with_sidebar.html"
                    className="text-uppercase border-btn"
                  >
                    Continue shopping
                  </a>
                  <a href="/checkout" className="text-uppercase background-btn">
                    Proceed to checkout
                  </a>
                </div>
                <div className="form-group cart_notes">
                  <label className="title_h5" htmlFor="notes">
                    Add a note for seller
                  </label>
                  <textarea className="form-control" id="notes" name="Notes"></textarea>
                </div>
              </form>
            </div>
          </div>
        </section>
      </div>
    </>
  );
};

export default Cart;
