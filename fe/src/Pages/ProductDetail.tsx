import { useEffect, useState } from "react";
import { Product, Products } from "../types/Product";
import { useParams } from "react-router-dom";
import { getAllProduct, getProductDetail } from "../services/product";
import { useCart } from "../context/CartContext";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";

const ProductDetail = () => {
  const { addToCart } = useCart();

  const [quantity, setQuantity] = useState<number>(1);
  const handleIncrease = () => {
    setQuantity(quantity + 1);
  };

  // Hàm để giảm số lượng
  const handleDecrease = () => {
    if (quantity > 1) {
      setQuantity(quantity - 1);
    }
  };

  const [productId, setProductId] = useState<Products>();
  const [selectedDetail, setSelectedDetail] = useState<any>(null);
  const { id } = useParams();
  useEffect(() => {
    if (!id) return;
    getProductDetail(id).then(({ data }) => {
      console.log("data", data);
      setProductId(data.data);
      //   setSelectedDetail(data.data.details[0]);
    });
  }, [id]);
  console.log("data", productId);
  const handleVariantClick = (detail: any) => {
    if (detail) {
      // Nếu có variant, chọn variant đó
      setSelectedDetail(detail);
    } else {
      setSelectedDetail(null);
    }
  };

  function getColorFromText(colorText: any) {
    switch (colorText.toLowerCase()) {
      case "màu vàng":
        return "#FFCC00"; // Mã màu vàng
      case "màu đỏ":
        return "#FF0000"; // Mã màu đỏ
      case "màu xanh":
        return "#008000"; // Mã màu xanh
      // Thêm các màu khác vào đây nếu cần
      default:
        return "#000000"; // Màu mặc định nếu không tìm thấy
    }
  }

    const [products, setProducts] = useState<Product[]>([]);
   useEffect(() => {
      getAllProduct()
        .then(({ data }) => {
          setProducts(data.data);
        })
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
                  <a href="earthyellow.html">Home</a>{" "}
                  <i className="flaticon-arrows-4"></i>
                </li>
                <li className="breadcrumb-item text-capitalize">
                  <a href="product_list_with_sidebar.html">Cửa hàng</a>{" "}
                  <i className="flaticon-arrows-4"></i>
                </li>
                <li className="breadcrumb-item active text-capitalize">
                 {productId?.name}
                </li>
              </ol>
            </nav>
            <h1 className="title_h1 font-weight-normal text-capitalize">
            {productId?.name}
            </h1>
          </div>
        </section>
        <section className="padding-top-text-60 padding-bottom-60 product_detail_section">
          {productId ? (
            <div className="container">
              <div className="main">
                {/* Phần bên trái với ảnh chính */}
                <div className="main-left" data-wow-duration="1300ms">
                  <div className="imageProduct">
                    <img
                      src={selectedDetail?.image || productId.image}
                      alt="Product"
                    />
                  </div>
                  <div className="imageBienthe">
                    <img
                      src={productId.image}
                      alt="Product main"
                      style={{ cursor: "pointer", marginRight: "10px" }}
                      onClick={() => handleVariantClick(null)} // Xử lý khi click vào ảnh chính
                    />
                    {/* Lặp qua chi tiết sản phẩm để hiển thị ảnh các variant */}
                    {productId.details.map((detail, index) => (
                      <img
                        key={index}
                        src={detail.image}
                        alt={`Variant ${index}`}
                        onClick={() => handleVariantClick(detail)} // Xử lý khi click vào variant
                        style={{ cursor: "pointer", marginRight: "10px" }}
                      />
                    ))}
                  </div>
                </div>
                <div className="main-right" data-wow-duration="1300ms">
                  <div className="product_content">
                    <div className="product_title">
                      {/* Tên sản phẩm */}
                      <p className="product_price title_h4">
                        {selectedDetail?.name || productId.name}
                      </p>

                      {/* Thương hiệu */}
                      <p className="sku_text">
                        Thương hiệu:{" "}
                        <a className="font-bold">{productId.brand}</a>
                      </p>

                      {/* Giá sản phẩm */}
                      {/* <p className="text-color title_h4">
                          {selectedDetail?.discount_price ||
                            selectedDetail?.price ||
                            productId.price}
                        </p> */}
                      <p className="text-color title_h4">
                        {selectedDetail?.discount_price ? (
                          <>
                            <span className="original-price">
                              {selectedDetail?.default_price}
                            </span>{" "}
                            {/* Giá gốc */}
                            <span className="discount-price">
                              {selectedDetail?.discount_price}
                            </span>{" "}
                            {/* Giá khuyến mại */}
                          </>
                        ) : (
                          selectedDetail?.default_price || productId.price
                        )}
                      </p>

                      <p>Số lượng: {selectedDetail?.quantity}</p>
                      {/* Đánh giá */}
                      <div className="star">
                        <img
                          src="../public/src/images/star.png"
                          className="img-fluid"
                          alt="star"
                        />
                        (1 Review)
                      </div>
                    </div>

                    <form>
                      <div className="product_variant">
                        <div className="form-group color_box">
                          <label className="title_h5 text-capitalize">
                            Color
                          </label>

                          {/* Màu sắc */}
                          {productId.details.map((detail, index) => (
                            <div
                              key={index}
                              className="radio text-uppercase text-center"
                            >
                              <input
                                type="radio"
                                name="color11"
                                id={`color${index + 1}`}
                                checked={selectedDetail?.id === detail.id}
                                onChange={() => handleVariantClick(detail)}
                              />
                              <label
                                htmlFor={`color${index + 1}`}
                                className={`color${index + 1}`}
                                style={{
                                  backgroundColor: getColorFromText(
                                    detail.color
                                  ),
                                }}
                              ></label>
                            </div>
                          ))}
                        </div>

                        <div className="form-group size_box">
                          <label className="title_h5 text-capitalize">
                            Kích thước
                          </label>
                          <select className="form-control">
                            <option>
                              {selectedDetail?.size ||
                                productId.details[0]?.size}
                            </option>
                          </select>
                        </div>

                        <div className="form-group quantity_box">
                          <label className="title_h5 text-capitalize">
                            Số lượng
                          </label>
                          <div className="qty_number">
                            <button
                              type="button"
                              onClick={handleDecrease}
                              style={{
                                padding: "5px 10px",
                                cursor: "pointer",
                              }}
                            >
                              -
                            </button>

                            <input type="text" value={quantity} />
                            <button
                              type="button"
                              onClick={handleIncrease}
                              style={{
                                padding: "5px 10px",
                                cursor: "pointer",
                              }}
                            >
                              +
                            </button>
                          </div>
                        </div>
                      </div>

                      <div className="product_btns">
                        <a
                          href="/wishlist"
                          className="wishlist_btn border-btn text-uppercase"
                        >
                          add to wishlist
                        </a>
                        <a
                            href="/cart"
                            className="background-btn text-uppercase cart_btn"
                          >
                            add to bag
                          </a>
                        
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
              <div className="product_description padding-top-60">
                <div className="row">
                  <div
                    className="col-md-6 wow fadeInRight"
                    data-wow-duration="1300ms"
                  >
                    <h5 className="title_h5 text-capitalize">Description</h5>
                    <p>{productId.description}</p>
                  </div>
                  <div
                    className="col-md-6 wow fadeInRight"
                    data-wow-duration="1300ms"
                  >
                    <h5 className="title_h5 text-capitalize">
                      Quality Time, All The Time
                    </h5>
                    <p>
                      Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                      sed do eiusmod tempor incididunt ut labore et dolore magna
                      aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                      ullamco laboris nisi ut aliquip ex ea commodo consequat.
                      Duis aute irure dolor in reprehenderit in voluptate velit
                      esse cillum dolore eu fugiat nulla pariatur. Excepteur
                      sint occaecat cupidatat non proident, sunt in culpa qui
                      officia deserunt mollit anim id est laborum.
                    </p>
                  </div>
                  <div
                    className="col-md-12 wow fadeInUp"
                    data-wow-duration="1300ms"
                  >
                    <div id="accordion">
                      <div className="card">
                        <div className="card-header" id="headingOne">
                          <h5 className="mb-0">
                            <button
                              className="title_h5 btn-link collapsed text-left"
                              data-toggle="collapse"
                              data-target="#collapseOne"
                              aria-expanded="true"
                              aria-controls="collapseOne"
                            >
                              Additional Info
                            </button>
                          </h5>
                        </div>
                        <div
                          id="collapseOne"
                          className="collapse"
                          aria-labelledby="headingOne"
                          data-parent="#accordion"
                        >
                          <div className="card-body">
                            Lorem ipsum dolor sit amet, consectetur adipiscing
                            elit, sed do eiusmod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad minim veniam, quis
                            nostrud exercitation ullamco laboris nisi ut aliquip
                            ex ea commodo consequat.
                          </div>
                        </div>
                      </div>
                      <div className="card">
                        <div className="card-header" id="headingTwo">
                          <h5 className="mb-0">
                            <button
                              className="title_h5 btn-link collapsed text-left"
                              data-toggle="collapse"
                              data-target="#collapseTwo"
                              aria-expanded="false"
                              aria-controls="collapseTwo"
                            >
                              Size Chart
                            </button>
                          </h5>
                        </div>
                        <div
                          id="collapseTwo"
                          className="collapse"
                          aria-labelledby="headingTwo"
                          data-parent="#accordion"
                        >
                          <div className="card-body">
                            <p>
                              Lorem ipsum dolor sit amet, consectetur adipiscing
                              elit, sed do eiusmod tempor incididunt ut labore
                              et dolore magna aliqua. Ut enim ad minim veniam,
                              quis nostrud exercitation ullamco laboris nisi ut
                              aliquip ex ea commodo consequat.
                            </p>
                            <p>
                              Duis aute irure dolor in reprehenderit in
                              voluptate velit esse cillum dolore eu fugiat nulla
                              pariatur.
                            </p>
                          </div>
                        </div>
                      </div>
                      <div className="card">
                        <div className="card-header" id="headingThree">
                          <h5 className="mb-0">
                            <button
                              className=" btn-link title_h5 text-left"
                              data-toggle="collapse"
                              data-target="#collapseThree"
                              aria-expanded="false"
                              aria-controls="collapseThree"
                            >
                              Reviews (1)
                            </button>
                          </h5>
                        </div>
                        <div
                          id="collapseThree"
                          className="collapse show"
                          aria-labelledby="headingThree"
                          data-parent="#accordion"
                        >
                          <div className="card-body">
                            <div className="review_title">
                              <h4 className="title_h4">Customer Reviews</h4>
                              <div className="star">
                                <img
                                  src="src/images/star.png"
                                  className="img-fluid"
                                  alt="star"
                                />{" "}
                                Based on 1 review
                              </div>
                              <a
                                href="javascript:void(0):"
                                className="write_review_text"
                              >
                                Write a review
                              </a>
                            </div>
                            <div className="review_content">
                              <div className="user_img rounded-circle">
                                <img
                                  src="src/images/reivew_user.png"
                                  className="img-fluid vertical_middle"
                                  alt="star"
                                />
                              </div>
                              <div className="user_detail">
                                <h5 className="title_h5">Ammy G.</h5>
                                <span className="review__date">
                                  April 5, 2018
                                </span>
                                <p>
                                  Curabitur egestas malesuada volutpat. Nunc vel
                                  vestibulum odio, ac pellen tesque lacus.
                                  Pellentesque dapibus nunc nec est imperdiet, a
                                  malesuada sem rutrum.{" "}
                                </p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div className="featured_section padding-top-text-60 wow fadeIn">
      <h2 className="text-center title_h3">Bạn có thể thích</h2>
      <Swiper
        modules={[Navigation]}
        spaceBetween={20}
        slidesPerView={4} // Hiển thị 4 sản phẩm mỗi lần
        navigation // Bật mũi tên điều hướng
        breakpoints={{
          320: { slidesPerView: 1 },
          768: { slidesPerView: 2 },
          1024: { slidesPerView: 3 },
          1200: { slidesPerView: 4 },
        }}
      >
        {products.map((product) => (
          <SwiperSlide key={product.id}>
            <div className="featured_content">
              <div className="featured_img_content">
                <img
                  src={product.image}
                  alt="f_product"
                  className="img-product_detail"
                />
                <div className="featured_btn vertical_middle">
                  <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">
                    Thêm vào giỏ hàng
                  </a>
                  <a href={`/product_detail/${product.id}`} className="text-uppercase border-btn popup_btn">
                    Xem chi tiết
                  </a>
                </div>
                <a href="javascript:void(0);" className="heart rounded-circle text-center">
                  <i className="flaticon-heart vertical_middle"></i>
                </a>
              </div>
              <div className="featured_detail_content">
                <a href={`/product_detail/${product.id}`}>
                  <p className="featured_title text-capitalize text-center">{product.name}</p>
                </a>
                <p className="featured_price title_h5 text-center">
                  <span>{product.price}</span>
                </p>
              </div>
            </div>
          </SwiperSlide>
        ))}
      </Swiper>
    </div>
            </div>
          ) : (
            <div className="container">
              <p>Đang tải...</p>
            </div>
          )}
        </section>
      </div>

      <div id="modalone" className="modal">
        <div className="modal-content">
          <a href="javascript:void(0);" className="close_popup">
            <span>&times;</span>
          </a>
          <div className="product_detail_section">
            <div className="container">
              <div className="row">
                <div className="col-lg-6">
                  <div id="q_sync1" className="owl-carousel owl-theme">
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img2.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img3.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img4.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                  </div>
                  <div id="q_sync2" className="owl-carousel owl-theme">
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img2.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img3.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img4.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                  </div>
                </div>
                <div className="col-lg-6">
                  <div className="product_content">
                    <div className="product_title">
                      <span className="product_price title_h4"> $59.95</span>
                      <span className="stock text-right">In Stock</span>
                      <p className="sku_text">SKU: 01-2345678</p>
                      <div className="star">
                        <img
                          src="src/images/star.png"
                          className="img-fluid"
                          alt="star"
                        />
                        (1 Review)
                      </div>
                    </div>

                    <form>
                      <div className="product_variant">
                        <div className="form-group color_box">
                          <label className="title_h5 text-capitalize">
                            Color
                          </label>
                          <div className="radio text-uppercase text-center">
                            <input type="radio" name="color" id="color1" />
                            <label htmlFor="color1" className="color1"></label>
                          </div>
                          <div className="radio text-uppercase text-center">
                            <input type="radio" name="color" id="color2" />
                            <label htmlFor="color2" className="color2"></label>
                          </div>
                          <div className="radio text-uppercase text-center">
                            <input type="radio" name="color" id="color3" />
                            <label htmlFor="color3" className="color3"></label>
                          </div>
                          <div className="radio text-uppercase text-center">
                            <input type="radio" name="color" id="color4" />
                            <label htmlFor="color4" className="color4"></label>
                          </div>
                        </div>
                        <div className="form-group size_box">
                          <label className="title_h5 text-capitalize">
                            Size
                          </label>
                          <select className="form-control">
                            <option>XS</option>
                            <option>S</option>
                            <option>M</option>
                            <option>L</option>
                            <option>XL</option>
                          </select>
                        </div>
                        <div className="form-group quantity_box">
                          <label className="title_h5 text-capitalize">
                            Quantity
                          </label>
                          <div className="qty_number">
                            <input type="text" value="1" />
                          </div>
                        </div>
                      </div>

                      <div className="product_btns">
                        <a
                          href="wishlist.html"
                          className="wishlist_btn border-btn text-uppercase"
                        >
                          add to wishlist{" "}
                        </a>
                        <button
                          type="submit"
                          className="background-btn text-uppercase cart_btn"
                        >
                          add to bag
                        </button>
                        <a
                          href="/cart"
                          className="background-btn text-uppercase cart_btn"
                        >
                          add to bag
                        </a>
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
                      <div className="info_text">
                        <a href="product_list_detail.html">View full info</a>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default ProductDetail;
