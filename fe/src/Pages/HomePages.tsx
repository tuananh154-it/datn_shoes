import { useEffect, useState } from "react";

import { Product, TopProductResponse } from "../types/Product";
import { Link } from "react-router-dom";

import { Banner, getBanners } from "../services/banners";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination, Autoplay } from "swiper/modules";
// import { getAllProduct } from "../axios/asiox";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/autoplay";
import { getLatesProducts, topProduct } from "../services/product";
import toast from "react-hot-toast";
import QuickViewProduct from "./QuickViewProduct";
import { Article, getArticles } from "../services/articles";
// import { getAllProduct } from "../services/product";

const HomePages = () => {


  const [product, setProduct] = useState<TopProductResponse | null>(null);
  const [lastProduct, getlatesProducts] = useState<Product[]>([]);
  const [articles, setArticles] = useState<Article[]>([]);

  const [selectedProductId, setSelectedProductId] = useState<string | null>(
    null
  );
  const latestArticles = articles
    .sort((a, b) => b.id - a.id) // Sắp xếp theo id
    .slice(0, 4);
  // console.log("bài viết", latestArticles);

  // useEffect(() => {
  //   const script = document.createElement("script");
  //   script.src = "path_to_revolution_slider.js"; // Thêm đường dẫn script của slider nếu cần
  //   script.async = true;
  //   document.body.appendChild(script);
  // }, []);
  const [banners, setBanners] = useState<Banner[]>([]);
  useEffect(() => {
    const fetchData = async () => {
      try {
        const [topProductRes, latestProductsRes, articlesRes, bannersRes] = await Promise.all([
          topProduct(),
          getLatesProducts(),
          getArticles(),
          getBanners(),
        ]);

        setProduct(topProductRes.data);
        getlatesProducts(latestProductsRes.data.data);
        setArticles(articlesRes.data);
        setBanners(bannersRes.data);

      } catch (error) {
        toast.error("Lỗi khi tải dữ liệu");
        console.error(error);
      }
    };

    fetchData();
  }, []);

  const toggleWishlist = (product: Product) => {
    // const user = JSON.parse(localStorage.getItem("user") || "null");

    // if (!user) {
    //   alert("Bạn cần đăng nhập để thêm sản phẩm vào danh sách yêu thích!");
    //   return;
    // }

    let wishlist = JSON.parse(localStorage.getItem("wishlist") || "[]");

    // Lưu ID sản phẩm thay vì object
    const index = wishlist.indexOf(product.id);

    if (index !== -1) {
      wishlist.splice(index, 1);
    } else {
      wishlist.push(product.id);
      toast.success("Đã thêm sản phẩm yêu thích");
    }

    localStorage.setItem("wishlist", JSON.stringify(wishlist));

    // Phát sự kiện cập nhật để các component khác biết
    window.dispatchEvent(new Event("storage"));
  };

  return (
    <>
      <div className="menu_overlay"></div>
      <div className="banner nav">
        <Swiper
          modules={[Navigation, Pagination, Autoplay]}
          pagination={{ clickable: true }}
          autoplay={{ delay: 2000 }}
          loop
        >
          {banners.map((banner) => (
            <SwiperSlide key={banner.id} style={{ height: "600px" }}>
              <a href={banner.link}>
                <img src={banner.image_url} alt="" className="img-fluid" loading="lazy" />
              </a>
            </SwiperSlide>
          ))}
        </Swiper>
      </div>
      <section className="bestseller">
     <div className="container">
     <h2 className="section-title">
          <span>Sản phẩm mới</span>
        </h2>

        <div className="products">
          {lastProduct.map((last) => (
            <div className="product-card" key={last.id}>
              <div className="label new">Mới</div>
              <a href={`/product_detail/${last.id}`}>
              <img className="product-image1" src={last.image} alt="Product 1" loading="lazy"/>
              </a>
              <div className="product-name">{last.name}</div>
              <div className="product-price">
                <strong>{last?.price
                            ? Number(
                                last.price
                                  .replace(/,/g, "")
                                  .replace(" VND", "")
                              ).toLocaleString("vi-VN") + " VND"
                            : "0 VND"}</strong>
              </div>
              <div className="rating">★★★★☆</div>
              <div className="product-actions">
              <Link
                          to="#"
                          className="text-uppercase add_to_bag_btn rounded-circle d-block"
                          onClick={(e) => {
                            e.preventDefault(); // Ngăn chặn điều hướng nếu chỉ cần xử lý sự kiện
                            setSelectedProductId(last.id);
                          }}
                        >
                <button>Thêm giỏ hàng</button>
                          </Link>
                <div className="icons">
                  <i className="fas fa-search"></i>
                  <a
                    href="#"
                    className="heart rounded-circle text-center d-block"
                    onClick={(e) => {
                      e.preventDefault();
                      toggleWishlist(last);
                    }}
                  >
                    <i className="flaticon-heart yeuthich"></i>
                  </a>
                  <i className="fas fa-sync-alt"></i>
                </div>
              </div>
            </div>
          ))}
        </div>
     </div>
      </section>
      <section className="bestseller">
        <div className="container">
          <h2 className="section-title">
            <span>Top sản phẩm bán chạy</span>
          </h2>
          <div className="content11">
            <div className="products">
              {product?.top_selling_products.map((product) => (
                <div className="product-card">
                  <div className="label">TOP</div>
                  <a href={`/product_detail/${product.id}`}>
                  <img
                  
                  className="product-image1"
                  src={product.image}
                  alt="Product 4"
                />
                  </a>
                
                  <div className="product-name">{product.name}</div>
                  <div className="product-price">
                    <strong>{product?.price
                      ? Number(
                        product.price
                          .replace(/,/g, "")
                          .replace(" VND", "")
                      ).toLocaleString("vi-VN") + " VND"
                      : "0 VND"}</strong>
                  </div>
                  <div className="rating">★★★★★</div>
                  <div className="product-actions">
                    <Link
                      to="#"
                      className="text-uppercase add_to_bag_btn rounded-circle d-block"
                      onClick={(e) => {
                        e.preventDefault(); // Ngăn chặn điều hướng nếu chỉ cần xử lý sự kiện
                        setSelectedProductId(product.id);
                      }}
                    >
                      <button className="themgiohang">Thêm giỏ hàng</button>
                    </Link>
                    <div className="icons">
                      <i className="fas fa-search"></i>
                      <a
                        href="#"
                        className="heart rounded-circle text-center d-block"
                        onClick={(e) => {
                          e.preventDefault();
                          toggleWishlist(product);
                        }}
                      >
                        <i className="flaticon-heart yeuthich"></i>
                      </a>
                      <i className="fas fa-sync-alt"></i>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>
      <div className="another-banner-area">
        <div className="container">
          <div className="row">
            <div className="col-md-12">
              <div className="big-banner">
                <a href="/shop">
                  <img
                    src="https://lambanner.com/wp-content/uploads/2022/10/MNT-DESIGN-BANNER-GIAY-07.jpg"
                    alt=""
                  />
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div className="blog-section">
        <h2 className="section-title">
          <span>Bài viết</span>
        </h2>
        <div className="blog-grid">
          {latestArticles.map((blog) => (
            <div className="blog-box" key={blog.id}>
              <img src={blog.image} alt="Blog" className="blog-img" />
              <div className="blog-info">
                <h3>{blog.name
                  .slice(0, 70) + (blog.content.length > 70 ? "..." : "")
                }</h3>
                <p className="meta">{blog.created_at.split("T")[0]}</p>
                {/* <p className="excerpt1">
                  {blog.content
                    .replace(/<p>/g, "")
                    .replace(/<\/p>/g, "")
                    .slice(0, 150) + (blog.content.length > 150 ? "..." : "")}
                </p> */}
                <p>{blog.title
                  .slice(0, 120) + (blog.content.length > 120 ? "..." : "")
                }</p>
                <a href={`/blog/${blog.id}`} className="read-more">
                  Xem chi tiết
                </a>
              </div>
            </div>
          ))}
        </div>
      </div>
      <div className="another-banner-area">
        <div className="container">
          <div className="row">
            <div className="col-md-12">
              <div className="big-banner">
                {/* <a href="/shop">
                  <img
                    src={banners.}
                    alt=""
                  />
                </a> */}
                {banners.map((banner) => (
                  <a href="/shop">
                    <img
                      src={banner.image_url[3]}
                      alt=""
                    />
                  </a>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
      {/* <section className="padding-top-60 wow fadeIn">
          <div className="container">
            <img src="https://intphcm.com/data/upload/poster-giay-den.jpg"/>
            <div className=" text-center wow fadeInUp position-relative">
              <p className="position-relative">MÙA HÈ BẮT ĐẦU</p>
              <h2 className="title_h2 text-capitalize position-relative">
                KHUYẾN MẠI GIẢM GIÁ 50%
              </h2>
              <a
                href="/shop"
                className="background-btn text-uppercase position-relative"
              >
                MUA NGAY
                <i className="flaticon-arrows-4"></i>
              </a>
            </div>
          </div>
        </section> */}
      {/* <img src="https://tse4.mm.bing.net/th?id=OIP.C0b3zlLfZ0GD-5txQXOkzQHaE8&pid=Api&P=0&h=180"/> */}
        {/* Quick View hiển thị khi có productId */}
        {selectedProductId && (
                <QuickViewProduct
                  productId={selectedProductId}
                  onClose={() => setSelectedProductId(null)}
                />
              )}
    </>
  );
};

export default HomePages;