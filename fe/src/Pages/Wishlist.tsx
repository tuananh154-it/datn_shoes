import React from 'react'

const Wishlist = () => {
  return (
   <>
    <div className="menu_overlay"></div>
   <div className="main_section">
  {/* START Breadcrumb */}
  <section className="breadcrumb_section nav">
    <div className="container">
      <nav aria-label="breadcrumb">
        <ol className="breadcrumb">
          <li className="breadcrumb-item text-capitalize">
            <a href="earthyellow.html">Home</a> <i className="flaticon-arrows-4"></i>
          </li>
          <li className="breadcrumb-item active text-capitalize">Wishlist</li>
        </ol>
      </nav>
      <h1 className="title_h1 font-weight-normal text-capitalize">Wishlist</h1>
    </div>
  </section>
  {/* END Breadcrumb */}
  {/* START Wishlist Section */}
  <section className="wishlist_section padding-top-60 padding-bottom-60">
    <div className="container">
      <div className="cart_table">
        <div className="table">
          <div className="thead">
            <div className="tr">
              <div className="th title_h5 border-bottom border-top">Product</div>
              <div className="th title_h5 border-bottom border-top">Price</div>
              <div className="th title_h5 border-bottom border-top">Quantity</div>
              <div className="th title_h5 border-bottom border-top">Options</div>
              <div className="th title_h5 border-bottom border-top"></div>
              <div className="th title_h5 border-bottom border-top"></div>
            </div>
          </div>
          <div className="tbody">
            <div className="tr">
              <div className="td border-bottom" data-title="Product">
                <div className="product_img d-table-cell">
                  <img src="src/images/blue_jacket_img.png" className="vertical_middle img-fluid" alt="blue Jacket" />
                </div>
                <div className="product_details d-table-cell">
                  <div className="product_title">
                    <a href="product_list_detail.html">
                      <h5 className="title_h5">Blue Jacket</h5>
                    </a>
                  </div>
                </div>
              </div>
              <div className="td border-bottom" data-title="Price">$59.95</div>
              <div className="td border-bottom" data-title="Quantity">
                <div className="form-group quantity_box d-inline-block">
                  <div className="qty_number">
                    <input type="text" value="1" />
                    <div className="inc button"><span>+</span></div>
                    <div className="dec button"><span>-</span></div>
                  </div>
                </div>
              </div>
              <div className="td border-bottom" data-title="Options">
                <div className="wishlist_variant">
                  <div className="options">
                    <form>
                      <div className="form-group">
                        <label htmlFor="size" className="title_h5">Size:</label>
                        <select className="form-control" id="size" name="size">
                          <option>S</option>
                        </select>
                      </div>
                    </form>
                  </div>
                  <div className="options">
                    <form>
                      <div className="form-group">
                        <label htmlFor="color" className="title_h5">Color:</label>
                        <select className="form-control" id="color" name="color">
                          <option>Red</option>
                        </select>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div className="td cart_bag border-bottom" data-title="Add To Bag">
                <a href="cart.html">
                  <i className="flaticon-shopping-bag"></i>
                </a>
              </div>
              <div className="td remove_cart border-bottom text-right" data-title="Remove">
                <a href="javascript:void(0);">
                  <i className="flaticon-close"></i>
                </a>
              </div>
            </div>
            <div className="tr">
              <div className="td border-bottom" data-title="Product">
                <div className="product_img d-table-cell">
                  <img src="src/images/f_product8.png" className="vertical_middle img-fluid" alt="Product" />
                </div>
                <div className="product_details d-table-cell">
                  <div className="product_title">
                    <a href="product_list_detail.html">
                      <h5 className="title_h5">Black Dotted Dress</h5>
                    </a>
                  </div>
                </div>
              </div>
              <div className="td border-bottom" data-title="Price">$59.95</div>
              <div className="td border-bottom" data-title="Quantity">
                <div className="form-group quantity_box d-inline-block">
                  <div className="qty_number">
                    <input type="text" value="1" />
                    <div className="inc button"><span>+</span></div>
                    <div className="dec button"><span>-</span></div>
                  </div>
                </div>
              </div>
              <div className="td border-bottom" data-title="Options">
                <div className="wishlist_variant">
                  <div className="options">
                    <form>
                      <div className="form-group">
                        <label htmlFor="sizes" className="title_h5">Size:</label>
                        <select className="form-control" id="sizes" name="sizes">
                          <option>S</option>
                        </select>
                      </div>
                    </form>
                  </div>
                  <div className="options">
                    <form>
                      <div className="form-group">
                        <label htmlFor="show" className="title_h5">Color:</label>
                        <select className="form-control" id="show" name="show">
                          <option>Red</option>
                        </select>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div className="td cart_bag border-bottom" data-title="Add To Bag">
                <a href="cart.html">
                  <i className="flaticon-shopping-bag"></i>
                </a>
              </div>
              <div className="td remove_cart border-bottom text-right" data-title="Remove">
                <a href="javascript:void(0);">
                  <i className="flaticon-close"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  {/* END Wishlist Section */}
</div>

   </>
  )
}

export default Wishlist