import React from 'react'

const CheckOut = () => {
  return (
  <>
  <div className="menu_overlay"></div>
{/* END Header */}
<div className="main_section">
  {/* START Breadcrumb */}
  <section className="breadcrumb_section nav">
    <div className="container">
      <nav aria-label="breadcrumb">
        <ol className="breadcrumb">
          <li className="breadcrumb-item text-capitalize">
            <a href="earthyellow.html">Home</a>
            <i className="flaticon-arrows-4"></i>
          </li>
          <li className="breadcrumb-item active text-capitalize">Checkout</li>
        </ol>
      </nav>
      <h1 className="title_h1 font-weight-normal text-capitalize">Checkout</h1>
    </div>
  </section>
  {/* END Breadcrumb */}
  {/* START Checkout Section */}
  <section className="login_section checkout_section padding-top-60 padding-bottom-60">
    <div className="container">
      <div className="login_form">
        <form>
          <div className="row">
            <div className="col-lg-6">
              <div className="head_title">
                <h4 className="title_h4">Your Order</h4>
              </div>
              <div className="cart_table">
                <div className="table">
                  <div className="thead">
                    <div className="tr">
                      <div className="th title_h5 border-bottom border-top">Product</div>
                      <div className="th title_h5 border-bottom border-top text-right">Price</div>
                    </div>
                  </div>
                  <div className="tbody">
                    <div className="tr">
                      <div className="td border-bottom" data-title="Product">
                        <div className="product_img d-table-cell">
                          <img
                            src="src/images/blue_jacket_img.png"
                            className="img-fluid vertical_middle"
                            alt="Blue Jacket"
                          />
                        </div>
                        <div className="product_details d-table-cell">
                          <div className="product_title">
                            <a href="product_detail.html">
                              <h5 className="title_h5">Blue Jacket</h5>
                            </a>
                          </div>
                          <div className="product_variant">
                            <p>Color: Blue</p>
                            <p>Size: XL</p>
                          </div>
                        </div>
                      </div>
                      <div className="td border-bottom text-right" data-title="Price">
                        $59.95
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div className="cart_subtotal">
                <div className="subtotal_text">Subtotal</div>
                <div className="subtotal_price title_h4 text-right">$59.95</div>
              </div>
              <div className="head_title">
                <h4 className="title_h4">Billing Details</h4>
              </div>
              <div className="row">
                <div className="col-sm-6">
                  <div className="form-group">
                    <label className="title_h5">First Name*</label>
                    <input
                      type="text"
                      className="form-control"
                      id="fname"
                      name="Firstname"
                      required
                    />
                  </div>
                </div>
                <div className="col-sm-6">
                  <div className="form-group">
                    <label className="title_h5">Last Name*</label>
                    <input type="text" className="form-control" name="Lastname" required />
                  </div>
                </div>
              </div>
              <div className="form-group">
                <label className="title_h5">Company</label>
                <input type="text" className="form-control" name="Company name" />
              </div>
              <div className="form-group">
                <label className="title_h5">Country*</label>
                <select className="form-control" id="country" name="country">
                  <option>- Select -</option>
                  <option>India</option>
                  <option>USA</option>
                  <option>UAE</option>
                </select>
              </div>
              <div className="form-group">
                <label className="title_h5">Address Line 1*</label>
                <input type="text" className="form-control" name="address" required />
              </div>
              <div className="form-group">
                <label className="title_h5">Address Line 2</label>
                <input type="text" className="form-control" name="address" />
              </div>
              <div className="row">
                <div className="col-sm-12 col-md-6">
                  <div className="form-group">
                    <label className="title_h5">City*</label>
                    <input type="text" className="form-control" name="City" />
                  </div>
                </div>
                <div className="col-sm-12 col-md-6">
                  <div className="form-group">
                    <label className="title_h5">Postal/Zip code*</label>
                    <input
                      type="text"
                      className="form-control"
                      name="Postal"
                      onKeyPress={(e) => e.charCode >= 48 && e.charCode <= 57}
                    />
                  </div>
                </div>
              </div>
              <div className="form-group">
                <label htmlFor="email_one" className="title_h5">
                  Email*
                </label>
                <input
                  type="email"
                  className="form-control"
                  id="email_one"
                  name="Email"
                  required
                />
              </div>
              <div className="form-group">
                <label htmlFor="phone" className="title_h5">
                  Phone*
                </label>
                <input
                  type="text"
                  className="form-control"
                  id="phone"
                  name="Phone"
                  required
                  onKeyPress={(e) => e.charCode >= 48 && e.charCode <= 57}
                  maxLength={10}
                />
              </div>
              <div className="form-group">
                <div className="check_box">
                  <input type="checkbox" name="box1" id="box1" />
                  <label htmlFor="box1">Ship To A Different Address?</label>
                </div>
                <div className="border-bottom"></div>
                <div className="check_box">
                  <input type="checkbox" name="box2" id="box2" />
                  <label htmlFor="box2">Create An Account</label>
                </div>
              </div>
              <div className="form-group">
                <label className="title_h5" htmlFor="notes">
                  Order Notes
                </label>
                <textarea className="form-control" id="notes" name="Notes"></textarea>
              </div>
            </div>
            <div className="col-lg-6">
              <div className="head_title">
                <h4 className="title_h4">Already a Member?</h4>
              </div>
              <div className="row">
                <div className="col-sm-6">
                  <div className="form-group">
                    <label htmlFor="email" className="title_h5">
                      Email*
                    </label>
                    <input
                      type="email"
                      className="form-control"
                      id="email"
                      name="Email"
                      required
                    />
                  </div>
                </div>
                <div className="col-sm-6">
                  <div className="form-group">
                    <label htmlFor="password" className="title_h5">
                      Password*
                    </label>
                    <input
                      type="password"
                      className="form-control"
                      id="password"
                      name="Password"
                      required
                    />
                  </div>
                </div>
                <div className="col-sm-12 border-bottom">
                  <div className="login_links checkbox_links">
                    <a className="btn-link forgot_text" href="reset_password.html">
                      <span className="border-bottom">Forgot Password?</span>
                    </a>
                    <button
                      type="submit"
                      className="btn float-sm-right background-btn text-uppercase"
                    >
                      login
                    </button>
                  </div>
                </div>
              </div>
              <div className="head_title">
                <h4 className="title_h4">Have a coupon? Enter Code below!</h4>
              </div>
              <div className="form-group">
                <label htmlFor="ccode" className="title_h5">
                  Coupon Code
                </label>
                <input
                  type="text"
                  className="form-control float-left"
                  id="ccode"
                  name="coupon code"
                />
                <button type="submit" className="btn background-btn text-uppercase float-left">
                  APPLY
                </button>
              </div>
              <div className="head_title d-inline-block padding-top-text-60">
                <h4 className="title_h4">Payment Details</h4>
              </div>
              <div className="radiobtn_section">
                <div className="radio_btn d-inline-block">
                  <input type="radio" name="box3" id="box3" defaultChecked />
                  <label htmlFor="box3">Credit/Debit Card</label>
                </div>
                <div className="radio_btn d-inline-block">
                  <input type="radio" name="box3" id="box4" />
                  <label htmlFor="box4">PayPal</label>
                </div>
                <div className="radio_btn d-inline-block">
                  <input type="radio" name="box3" id="box5" />
                  <label htmlFor="box5">Cash on Delivery</label>
                </div>
              </div>
              <div className="form-group">
                <label htmlFor="card_name" className="title_h5">
                  Name On Card*
                </label>
                <input
                  type="text"
                  className="form-control"
                  id="card_name"
                  name="Card name"
                  required
                />
              </div>
              <div className="form-group">
                <label htmlFor="card_no" className="title_h5">
                  Card Number*
                </label>
                <input
                  type="text"
                  className="form-control"
                  id="card_no"
                  name="Card no"
                  required
                />
              </div>
              <div className="row">
                <div className="col-sm-6">
                  <div className="form-group">
                    <label htmlFor="Edate" className="title_h5">
                      Expiry Date*
                    </label>
                    <input
                      type="text"
                      className="form-control"
                      id="Edate"
                      name="Expiry date"
                      required
                    />
                  </div>
                </div>
                <div className="col-sm-6">
                  <div className="form-group">
                    <label htmlFor="cvv" className="title_h5">
                      CVV*
                    </label>
                    <input
                      type="text"
                      className="form-control"
                      id="cvv"
                      name="CVV"
                      required
                    />
                  </div>
                </div>
              </div>
              <div className="form-group">
                <button
                  type="submit"
                  className="btn background-btn text-uppercase full-width"
                >
                  Place Order
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
  {/* END Checkout Section */}
</div>

  </>
  )
}

export default CheckOut
