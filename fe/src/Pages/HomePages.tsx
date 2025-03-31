// import { useEffect, useState } from "react";

// import { Product } from "../types/Product";
// import { Link } from "react-router-dom";

// import { Banner, getBanners } from "../services/banners";
// import { Swiper, SwiperSlide } from "swiper/react";
// import { Navigation, Pagination, Autoplay } from "swiper/modules";
// // import { getAllProduct } from "../axios/asiox";
// import "swiper/css";
// import "swiper/css/navigation";
// import "swiper/css/pagination";
// import "swiper/css/autoplay";
// import { getAllProduct, getLatesProducts } from "../services/product";
// import toast from "react-hot-toast";
// import QuickViewProduct from "./QuickViewProduct";
// // import { getAllProduct } from "../services/product";

// const HomePages = () => {
//     const [product, setProduct] = useState<Product[]>([]);
//     const [lastProduct, getlatesProducts] = useState<Product[]>([]);
//     const [selectedProductId, setSelectedProductId] = useState<string | null>(null);
//     useEffect(() => {
//         getAllProduct().then(({ data }) => {
//             // setProduct(response.data);
//             console.log("data", data);
//             setProduct(data.data);
//         });
//     }, []);
//     useEffect(() => {
//         getLatesProducts().then(({ data }) => {
//             // setProduct(response.data);
//             console.log("data", data);
//             getlatesProducts(data.data);
//         });
//     }, []);

//     console.log("product", product);
//     useEffect(() => {
//         // Nếu bạn đang sử dụng thư viện như Revolution Slider, khởi tạo slider ở đây nếu cần.
//         const script = document.createElement("script");
//         script.src = "path_to_revolution_slider.js"; // Thêm đường dẫn script của slider nếu cần
//         script.async = true;
//         document.body.appendChild(script);
//     }, []);
//     const [banners, setBanners] = useState<Banner[]>([]);
//     useEffect(() => {
//         getBanners().then(({ data }) => {
//             setBanners(data);
//             console.log(data);
//         });
//     }, []);


//     const toggleWishlist = (product: Product) => {
//         const user = JSON.parse(localStorage.getItem("user") || "null");

//         if (!user) {
//             alert("Bạn cần đăng nhập để thêm sản phẩm vào danh sách yêu thích!");
//             return;
//         }

//         let wishlist = JSON.parse(localStorage.getItem("wishlist") || "[]");

//         // Lưu ID sản phẩm thay vì object
//         const index = wishlist.indexOf(product.id);

//         if (index !== -1) {
//             wishlist.splice(index, 1);
//         } else {
//             wishlist.push(product.id);
//         }
//         toast.success("Đã thêm sản phẩm yêu thích")
//         localStorage.setItem("wishlist", JSON.stringify(wishlist));
//     };

//     return (
//         <>
//             <div className="menu_overlay"></div>
//             <div className="banner nav">
//                 <Swiper
//                     modules={[Navigation, Pagination, Autoplay]}
//                     pagination={{ clickable: true }}
//                     autoplay={{ delay: 2000 }}
//                     loop
//                 >
//                     {banners.map((banner) => (
//                         <SwiperSlide key={banner.id} style={{ height: "600px" }}>
//                             <a href={banner.link}>
//                                 <img src={banner.image_url} alt="" className="img-fluid" />
//                             </a>
//                         </SwiperSlide>
//                     ))}
//                 </Swiper>
//             </div>
//             <div className="main_section">
//                 {/*hình 2018*/}
//                 <div className="shoes_collection_section padding-top-60 wow fadeIn">
//                     <div className="container">
//                         <div className="row">
//                             <div className="col-md-8 col-sm-8 wow fadeInLeft animated">
//                                 <div className="home_collection_content  position-relative">
//                                     <img
//                                         src="src/images/shoes_collection1.jpg"
//                                         alt="women"
//                                         className="img-fluid"
//                                     />
//                                     <div className="shoes_collection_content">
//                                         <h2 className="text-uppercase title_h2">2025</h2>
//                                         <p className="text-uppercase">Bộ siêu tập mới</p>
//                                         <a
//                                             href="/shop"
//                                             className="background-btn  text-uppercase"
//                                         >
//                                             Mua ngay <i className="flaticon-arrows-4"></i>
//                                         </a>
//                                     </div>
//                                 </div>
//                             </div>
//                             <div className="col-md-4 col-sm-4 wow fadeInDown animated">
//                                 <div className="home_collection_content position-relative">
//                                     <img
//                                         src="src/images/shoes_collection2.jpg"
//                                         alt="men"
//                                         className="img-fluid"
//                                     />
//                                     <div className="shoes_collection_content">
//                                         <span className="text-uppercase">Phiên Bản</span>
//                                         <p className="text-uppercase">Nam</p>
//                                     </div>
//                                 </div>
//                                 <div className="home_collection_content position-relative">
//                                     <img
//                                         src="src/images/shoes_collection3.png"
//                                         alt="products"
//                                         className="img-fluid"
//                                     />
//                                     <div className="shoes_collection_content">
//                                         <span className="text-uppercase">Phiên bản</span>
//                                         <p className="text-uppercase">Nữ</p>
//                                     </div>
//                                 </div>
//                             </div>
//                         </div>
//                     </div>
//                 </div>
//                 {/* hinh 2018 */}
//                 {/* New arrival */}
//                 <section className="padding-top-text-60 arrival_featured_section wow fadeIn">
//                     <div className="container">
//                         <div className="row">
//                             <div className="col-lg-3 col-md-4 col-6">
//                                 <div className="arrival_collection_content position-relative">
//                                     <img
//                                         src="src/images/arrival_img.jpg"
//                                         alt="arrival_img"
//                                         className="img-fluid"
//                                     />
//                                     <div className="arrival_collection_text ">
//                                         <h2 className="title_h2 text-uppercase">Sản phẩm mới</h2>
//                                         <p className="text-uppercase">Ngay bây giờ</p>
//                                     </div>
//                                 </div>
//                             </div>
//                             {lastProduct.map((lastproduct) => (
//                                 <div
//                                     className="col-lg-3 col-md-4 col-6 wow fadeInLeft animated arrival"
//                                     data-wow-duration="1300ms"
//                                 >
//                                     <div className="featured_content">
//                                         <div className="featured_img_content position-relative">
//                                             <img
//                                                 src={lastproduct.image}
//                                                 className="img-product"
//                                                 alt="shoes_product"
//                                             />
//                                             <div className="featured_btn vertical_middle">
//                                                 <Link
//                                                     to="#"
//                                                     className="text-uppercase add_to_bag_btn rounded-circle d-block"
//                                                     onClick={(e) => {
//                                                         e.preventDefault(); // Ngăn chặn điều hướng nếu chỉ cần xử lý sự kiện
//                                                         setSelectedProductId(lastproduct.id);
//                                                     }}
//                                                 >
//                                                     <svg
//                                                         xmlns="http://www.w3.org/2000/svg"
//                                                         width="18"
//                                                         height="21"
//                                                         viewBox="0 0 18 21"
//                                                     >
//                                                         <path
//                                                             fill="#000"
//                                                             data-name="Bag Icon copy"
//                                                             className="cls-1"
//                                                             d="M18,18.2L16.7,4.58a0.543,0.543,0,0,0-.56-0.474H13.411A4.278,4.278,0,0,0,9,0,4.278,4.278,0,0,0,4.588,4.106H1.856a0.549,0.549,0,0,0-.56.474L0,18.2v0.048A3.089,3.089,0,0,0,3.334,21H14.666A3.089,3.089,0,0,0,18,18.247V18.2ZM9,1.041a3.191,3.191,0,0,1,3.292,3.065H5.707A3.191,3.191,0,0,1,9,1.041Zm5.666,18.91H3.334a2.02,2.02,0,0,1-2.215-1.687L2.369,5.149h2.22v1.83a0.561,0.561,0,0,0,1.119,0V5.149h6.584v1.83a0.561,0.561,0,0,0,1.119,0V5.149h2.22l1.25,13.119A2.02,2.02,0,0,1,14.666,19.951Z"
//                                                         />
//                                                     </svg>
//                                                 </Link>
//                                                 <a
//                                                     href={`/product_detail/${lastproduct.id}`}
//                                                     className="text-uppercase  popup_btn rounded-circle d-block"
//                                                     data-modal="#modalone"
//                                                 >
//                                                     <svg
//                                                         xmlns="http://www.w3.org/2000/svg"
//                                                         width="18"
//                                                         height="11"
//                                                         viewBox="0 0 18 11"
//                                                     >
//                                                         <path
//                                                             fill="#000"
//                                                             data-name="Forma 1"
//                                                             className="cls-1"
//                                                             d="M17.885,5.164C17.724,4.953,13.893,0,9,0S0.274,4.953.114,5.163a0.551,0.551,0,0,0,0,.672C0.274,6.046,4.106,11,9,11s8.725-4.953,8.885-5.164A0.55,0.55,0,0,0,17.885,5.164ZM9,9.861c-3.6,0-6.726-3.288-7.65-4.362C2.272,4.423,5.387,1.137,9,1.137S15.725,4.424,16.65,5.5C15.727,6.576,12.611,9.861,9,9.861ZM9,2.085A3.493,3.493,0,0,0,5.439,5.5,3.494,3.494,0,0,0,9,8.913,3.494,3.494,0,0,0,12.56,5.5,3.493,3.493,0,0,0,9,2.085ZM9,7.775A2.329,2.329,0,0,1,6.626,5.5,2.329,2.329,0,0,1,9,3.224,2.329,2.329,0,0,1,11.373,5.5,2.329,2.329,0,0,1,9,7.775Z"
//                                                         />
//                                                     </svg>
//                                                 </a>
//                                                 <a href="/wishlist" className="heart yeuthich  rounded-circle text-center rounded-circle d-block"><i className="flaticon-heart"></i></a>
//                                             </div>
//                                             <div className="product-label rounded-circle  newProduct">
//                                                 MỚI
//                                             </div>
//                                         </div>
//                                         <div className="featured_detail_content position-relative">
//                                             <a href={`/product_detail/${lastproduct.id}`}>
//                                                 <p className="featured_title  text-capitalize  ">
//                                                     {lastproduct.name}
//                                                 </p>
//                                             </a>
//                                             <p className="featured_price title_h5 ">
//                                                 <span>{lastproduct.price}</span>
//                                             </p>
//                                             <div className="featured_btn d-xl-none">
//                                                 s
//                                                 <a
//                                                     href="cart.html"
//                                                     className="text-uppercase add_to_bag_btn rounded-circle d-block"
//                                                 >
//                                                     <svg
//                                                         xmlns="http://www.w3.org/2000/svg"
//                                                         width="18"
//                                                         height="21"
//                                                         viewBox="0 0 18 21"
//                                                     >
//                                                         <path
//                                                             fill="#000"
//                                                             data-name="Bag Icon copy"
//                                                             className="cls-1"
//                                                             d="M18,18.2L16.7,4.58a0.543,0.543,0,0,0-.56-0.474H13.411A4.278,4.278,0,0,0,9,0,4.278,4.278,0,0,0,4.588,4.106H1.856a0.549,0.549,0,0,0-.56.474L0,18.2v0.048A3.089,3.089,0,0,0,3.334,21H14.666A3.089,3.089,0,0,0,18,18.247V18.2ZM9,1.041a3.191,3.191,0,0,1,3.292,3.065H5.707A3.191,3.191,0,0,1,9,1.041Zm5.666,18.91H3.334a2.02,2.02,0,0,1-2.215-1.687L2.369,5.149h2.22v1.83a0.561,0.561,0,0,0,1.119,0V5.149h6.584v1.83a0.561,0.561,0,0,0,1.119,0V5.149h2.22l1.25,13.119A2.02,2.02,0,0,1,14.666,19.951Z"
//                                                         />
//                                                     </svg>
//                                                 </a>
//                                                 <a
//                                                     href="javascript:void(0);"
//                                                     className="text-uppercase  popup_btn rounded-circle d-block"
//                                                     data-modal="#modalone"
//                                                 >
//                                                     <svg
//                                                         xmlns="http://www.w3.org/2000/svg"
//                                                         width="18"
//                                                         height="11"
//                                                         viewBox="0 0 18 11"
//                                                     >
//                                                         <path
//                                                             fill="#000"
//                                                             data-name="Forma 1"
//                                                             className="cls-1"
//                                                             d="M17.885,5.164C17.724,4.953,13.893,0,9,0S0.274,4.953.114,5.163a0.551,0.551,0,0,0,0,.672C0.274,6.046,4.106,11,9,11s8.725-4.953,8.885-5.164A0.55,0.55,0,0,0,17.885,5.164ZM9,9.861c-3.6,0-6.726-3.288-7.65-4.362C2.272,4.423,5.387,1.137,9,1.137S15.725,4.424,16.65,5.5C15.727,6.576,12.611,9.861,9,9.861ZM9,2.085A3.493,3.493,0,0,0,5.439,5.5,3.494,3.494,0,0,0,9,8.913,3.494,3.494,0,0,0,12.56,5.5,3.493,3.493,0,0,0,9,2.085ZM9,7.775A2.329,2.329,0,0,1,6.626,5.5,2.329,2.329,0,0,1,9,3.224,2.329,2.329,0,0,1,11.373,5.5,2.329,2.329,0,0,1,9,7.775Z"
//                                                         />
//                                                     </svg>
//                                                 </a>
//                                                 {/* <a
//                           href="#"
//                           className="heart yeuthich rounded-circle text-center d-block"
//                           onClick={(e) => {
//                             e.preventDefault();
//                             toggleWishlist(product);
//                           }}
//                         >
//                           <i className="flaticon-heart"></i>
//                         </a> */}
//                                             </div>
//                                         </div>
//                                     </div>
//                                 </div>
//                             ))}
//                             {/* Quick View hiển thị khi có productId */}
//                             {selectedProductId && (
//                                 <QuickViewProduct
//                                     productId={selectedProductId}
//                                     onClose={() => setSelectedProductId(null)}
//                                 />
//                             )}
//                         </div>
//                     </div>
//                 </section>
//                 {/* new arrival */}
//                 <section className="padding-top-60 wow fadeIn">
//                     <div className="container">
//                         <div className="promoton_collection_section text-center wow fadeInUp position-relative">
//                             <p className="position-relative">MÙA HÈ BẮT ĐẦU</p>
//                             <h2 className="title_h2 text-capitalize position-relative">
//                                 KHUYẾN MẠI GIẢM GIÁ 50%
//                             </h2>
//                             <a
//                                 href="product_list_with_sidebar.html"
//                                 className="background-btn text-uppercase position-relative"
//                             >
//                                 MUA NGAY
//                                 <i className="flaticon-arrows-4"></i>
//                             </a>
//                         </div>
//                     </div>
//                 </section>

//                 {/* best selles */}
//                 <section className="padding-top-text-60 arrival_featured_section wow fadeIn">
//                     <div className="container">
//                         <div className="shoes_featured_title">
//                             <h3 className="title_h3 text-uppercase">
//                                 Sản phẩm bán chạy nhất
//                             </h3>
//                             <p className="shoes_featured_title_link mb-0">
//                                 <a href="/shop" className="text-uppercase">
//                                     Xem thêm<i className="flaticon-arrows-4"></i>
//                                 </a>
//                             </p>
//                         </div>
//                         <div className="row">
//                             {product.map((product) => (
//                                 <div
//                                     className="col-lg-3 col-md-4 col-6 wow fadeInLeft animated"
//                                     data-wow-duration="1300ms"
//                                 >
//                                     <div className="featured_content">
//                                         <div className="featured_img_content position-relative">
//                                             <img
//                                                 src={product.image}
//                                                 className="img-product"
//                                                 alt="shoes_product"
//                                             />
//                                             <div className="featured_btn vertical_middle">
//                                                 {/* <a
//                           href="/cart"
//                           className="text-uppercase add_to_bag_btn rounded-circle d-block"
//                         >
//                           <svg
//                             xmlns="http://www.w3.org/2000/svg"
//                             width="18"
//                             height="21"
//                             viewBox="0 0 18 21"
//                           >
//                             <path
//                               fill="#000"
//                               data-name="Bag Icon copy"
//                               className="cls-1"
//                               d="M18,18.2L16.7,4.58a0.543,0.543,0,0,0-.56-0.474H13.411A4.278,4.278,0,0,0,9,0,4.278,4.278,0,0,0,4.588,4.106H1.856a0.549,0.549,0,0,0-.56.474L0,18.2v0.048A3.089,3.089,0,0,0,3.334,21H14.666A3.089,3.089,0,0,0,18,18.247V18.2ZM9,1.041a3.191,3.191,0,0,1,3.292,3.065H5.707A3.191,3.191,0,0,1,9,1.041Zm5.666,18.91H3.334a2.02,2.02,0,0,1-2.215-1.687L2.369,5.149h2.22v1.83a0.561,0.561,0,0,0,1.119,0V5.149h6.584v1.83a0.561,0.561,0,0,0,1.119,0V5.149h2.22l1.25,13.119A2.02,2.02,0,0,1,14.666,19.951Z"
//                             />
//                           </svg>
//                         </a> */}
//                                                 <Link
//                                                     to="#"
//                                                     className="text-uppercase add_to_bag_btn rounded-circle d-block"
//                                                     onClick={(e) => {
//                                                         e.preventDefault(); // Ngăn chặn điều hướng nếu chỉ cần xử lý sự kiện
//                                                         setSelectedProductId(product.id);
//                                                     }}
//                                                 >
//                                                     <svg
//                                                         xmlns="http://www.w3.org/2000/svg"
//                                                         width="18"
//                                                         height="21"
//                                                         viewBox="0 0 18 21"
//                                                     >
//                                                         <path
//                                                             fill="#000"
//                                                             data-name="Bag Icon copy"
//                                                             className="cls-1"
//                                                             d="M18,18.2L16.7,4.58a0.543,0.543,0,0,0-.56-0.474H13.411A4.278,4.278,0,0,0,9,0,4.278,4.278,0,0,0,4.588,4.106H1.856a0.549,0.549,0,0,0-.56.474L0,18.2v0.048A3.089,3.089,0,0,0,3.334,21H14.666A3.089,3.089,0,0,0,18,18.247V18.2ZM9,1.041a3.191,3.191,0,0,1,3.292,3.065H5.707A3.191,3.191,0,0,1,9,1.041Zm5.666,18.91H3.334a2.02,2.02,0,0,1-2.215-1.687L2.369,5.149h2.22v1.83a0.561,0.561,0,0,0,1.119,0V5.149h6.584v1.83a0.561,0.561,0,0,0,1.119,0V5.149h2.22l1.25,13.119A2.02,2.02,0,0,1,14.666,19.951Z"
//                                                         />
//                                                     </svg>
//                                                 </Link>
//                                                 <a
//                                                     href={`/product_detail/${product.id}`}
//                                                     className="text-uppercase  popup_btn rounded-circle d-block"
//                                                     data-modal="#modalone"
//                                                 >
//                                                     <svg
//                                                         xmlns="http://www.w3.org/2000/svg"
//                                                         width="18"
//                                                         height="11"
//                                                         viewBox="0 0 18 11"
//                                                     >
//                                                         <path
//                                                             fill="#000"
//                                                             data-name="Forma 1"
//                                                             className="cls-1"
//                                                             d="M17.885,5.164C17.724,4.953,13.893,0,9,0S0.274,4.953.114,5.163a0.551,0.551,0,0,0,0,.672C0.274,6.046,4.106,11,9,11s8.725-4.953,8.885-5.164A0.55,0.55,0,0,0,17.885,5.164ZM9,9.861c-3.6,0-6.726-3.288-7.65-4.362C2.272,4.423,5.387,1.137,9,1.137S15.725,4.424,16.65,5.5C15.727,6.576,12.611,9.861,9,9.861ZM9,2.085A3.493,3.493,0,0,0,5.439,5.5,3.494,3.494,0,0,0,9,8.913,3.494,3.494,0,0,0,12.56,5.5,3.493,3.493,0,0,0,9,2.085ZM9,7.775A2.329,2.329,0,0,1,6.626,5.5,2.329,2.329,0,0,1,9,3.224,2.329,2.329,0,0,1,11.373,5.5,2.329,2.329,0,0,1,9,7.775Z"
//                                                         />
//                                                     </svg>
//                                                 </a>
//                                                 <a
//                                                     href="#"
//                                                     className="heart yeuthich rounded-circle text-center d-block"
//                                                     onClick={(e) => {
//                                                         e.preventDefault();
//                                                         toggleWishlist(product);
//                                                     }}
//                                                 >
//                                                     <i className="flaticon-heart"></i>
//                                                 </a>
//                                                 {/* <button
//                                                     onClick={() => handleAddToWishlist} // Gọi hàm khi nhấn vào nút yêu thích
//                                                     className="heart rounded-circle text-center rounded-circle d-block"
//                                                 >
//                                                     <i className="flaticon-heart"></i>
//                                                 </button> */}
//                                             </div>
//                                         </div>
//                                         <div className="featured_detail_content">
//                                             <a href={`/product_detail/${product.id}`}>
//                                                 <p className="featured_title  text-capitalize  ">
//                                                     {product.name}
//                                                 </p>
//                                             </a>
//                                             <p className="featured_price title_h5  ">
//                                                 <span className="text-color">{product.price}</span>
//                                             </p>
//                                             <div className="featured_btn d-xl-none">
//                                                 <a
//                                                     href="cart.html"
//                                                     className="text-uppercase add_to_bag_btn rounded-circle d-block"
//                                                 >
//                                                     <svg
//                                                         xmlns="http://www.w3.org/2000/svg"
//                                                         width="18"
//                                                         height="21"
//                                                         viewBox="0 0 18 21"
//                                                     >
//                                                         <path
//                                                             fill="#000"
//                                                             data-name="Bag Icon copy"
//                                                             className="cls-1"
//                                                             d="M18,18.2L16.7,4.58a0.543,0.543,0,0,0-.56-0.474H13.411A4.278,4.278,0,0,0,9,0,4.278,4.278,0,0,0,4.588,4.106H1.856a0.549,0.549,0,0,0-.56.474L0,18.2v0.048A3.089,3.089,0,0,0,3.334,21H14.666A3.089,3.089,0,0,0,18,18.247V18.2ZM9,1.041a3.191,3.191,0,0,1,3.292,3.065H5.707A3.191,3.191,0,0,1,9,1.041Zm5.666,18.91H3.334a2.02,2.02,0,0,1-2.215-1.687L2.369,5.149h2.22v1.83a0.561,0.561,0,0,0,1.119,0V5.149h6.584v1.83a0.561,0.561,0,0,0,1.119,0V5.149h2.22l1.25,13.119A2.02,2.02,0,0,1,14.666,19.951Z"
//                                                         />
//                                                     </svg>
//                                                 </a>
//                                                 <a
//                                                     href="javascript:void(0);"
//                                                     className="text-uppercase  popup_btn rounded-circle d-block"
//                                                     data-modal="#modalone"
//                                                 >
//                                                     <svg
//                                                         xmlns="http://www.w3.org/2000/svg"
//                                                         width="18"
//                                                         height="11"
//                                                         viewBox="0 0 18 11"
//                                                     >
//                                                         <path
//                                                             fill="#000"
//                                                             data-name="Forma 1"
//                                                             className="cls-1"
//                                                             d="M17.885,5.164C17.724,4.953,13.893,0,9,0S0.274,4.953.114,5.163a0.551,0.551,0,0,0,0,.672C0.274,6.046,4.106,11,9,11s8.725-4.953,8.885-5.164A0.55,0.55,0,0,0,17.885,5.164ZM9,9.861c-3.6,0-6.726-3.288-7.65-4.362C2.272,4.423,5.387,1.137,9,1.137S15.725,4.424,16.65,5.5C15.727,6.576,12.611,9.861,9,9.861ZM9,2.085A3.493,3.493,0,0,0,5.439,5.5,3.494,3.494,0,0,0,9,8.913,3.494,3.494,0,0,0,12.56,5.5,3.493,3.493,0,0,0,9,2.085ZM9,7.775A2.329,2.329,0,0,1,6.626,5.5,2.329,2.329,0,0,1,9,3.224,2.329,2.329,0,0,1,11.373,5.5,2.329,2.329,0,0,1,9,7.775Z"
//                                                         />
//                                                     </svg>
//                                                 </a>
//                                                 {/* <a
//                                                     href="#"
//                                                     className="heart yeuthich rounded-circle text-center d-block"
//                                                     onClick={(e) => {
//                                                         e.preventDefault();
//                                                         toggleWishlist(product);
//                                                     }}
//                                                 >
//                                                     <i className="flaticon-heart"></i>
//                                                 </a> */}
//                                             </div>
//                                         </div>
//                                     </div>
//                                 </div>
//                             ))}
//                         </div>
//                     </div>
//                 </section>
//                 {/* best */}
//                 <section className="padding-top-text-60 wow fadeIn blog_section shoes_blog_section">
//                     <div className="container">
//                         <div className="shoes_featured_title">
//                             <h3 className="title_h3 text-uppercase">Bài viết mới nhất</h3>
//                             <p className="shoes_featured_title_link mb-0">
//                                 <Link to="/blog" className="text-uppercase">
//                                     Xem thêm<i className="flaticon-arrows-4"></i>
//                                 </Link>
//                             </p>
//                         </div>
//                         <div className="row">
//                             <div
//                                 className="col-md-6 wow fadeInLeft"
//                                 data-wow-duration="1300ms"
//                             >
//                                 <div className="blog_content">
//                                     <div className="row">
//                                         <div className="col-lg-6">
//                                             <a href="blog_list_detail.html">
//                                                 <img
//                                                     src="src/images/shoes_blog1.png"
//                                                     alt="blog"
//                                                     className="img-fluid"
//                                                 />
//                                             </a>
//                                         </div>
//                                         <div className="col-lg-6">
//                                             <span className="article__date">
//                                                 March 21, 2018 | Posted By Admin
//                                             </span>
//                                             <a href="blog_list_detail.html">
//                                                 <h5 className="article__title title_h5">
//                                                     Sed ut perspiciatis unde omnisiste natus error sit
//                                                 </h5>
//                                             </a>
//                                             <p>
//                                                 There are many variations of passages of Lorem Ipsum
//                                                 available but the majority have...
//                                             </p>
//                                         </div>
//                                     </div>
//                                 </div>
//                             </div>
//                             <div
//                                 className="col-md-6 wow fadeInRight"
//                                 data-wow-duration="1300ms"
//                             >
//                                 <div className="blog_content">
//                                     <div className="row">
//                                         <div className="col-lg-6">
//                                             <a href="blog_list_detail.html">
//                                                 <img
//                                                     src="src/images/shoes_blog2.png"
//                                                     alt="blog"
//                                                     className="img-fluid"
//                                                 />
//                                             </a>
//                                         </div>
//                                         <div className="col-lg-6">
//                                             <span className="article__date">
//                                                 March 21, 2018 | Posted By Admin
//                                             </span>
//                                             <a href="blog_list_detail.html">
//                                                 <h5 className="article__title title_h5">
//                                                     Voluptatem accusantium dolor emque laudantium
//                                                 </h5>
//                                             </a>
//                                             <p>
//                                                 Duis aute irure dolor in reprehenderit in voluptate
//                                                 velit esse cillum dolore eu fugiat...
//                                             </p>
//                                         </div>
//                                     </div>
//                                 </div>
//                             </div>
//                         </div>
//                     </div>
//                 </section>
//                 <section className="padding-top-60 padding-bottom-60 instagram_section shoes_instagram_section  wow animated">
//                     <div className="container">
//                         <div className="row">
//                             <div
//                                 className="col-md-12 wow fadeInLeft"
//                                 data-wow-duration="1300ms"
//                             >
//                                 <div className="instagram_title text-center">
//                                     <img src="src/images/ins_icon.png" alt="" />
//                                     <h4 className="title_h4  text-uppercase">
//                                         Follow Us On Instagram{" "}
//                                     </h4>
//                                     <p>
//                                         Shop our favorites and share yours!{" "}
//                                         <span>#Running Shoes Trending Style Sneakers</span>
//                                     </p>
//                                 </div>
//                             </div>
//                             <div
//                                 className="col-md-12 wow fadeInRight"
//                                 data-wow-duration="1300ms"
//                                 data-wow-delay="0.2s"
//                             >
//                                 <div
//                                     className="owl-carousel owl-theme instagram_slider"
//                                     id="shoes_instafeed"
//                                 ></div>
//                             </div>
//                         </div>
//                     </div>
//                 </section>
//                 <div className="padding-top-60 brand_logo_section shoes_brand_logo_section wow fadeIn">
//                     <div className="container">
//                         <div id="brand_slider" className="owl-carousel">
//                             <div className="item">
//                                 <div className="brand_logo_img text-center position-relative">
//                                     <img
//                                         src="src/images/s_logo1.png"
//                                         alt="brand_logo"
//                                         className="img-fluid vertical_middle"
//                                     />
//                                 </div>
//                             </div>
//                             <div className="item">
//                                 <div className="brand_logo_img text-center position-relative">
//                                     <img
//                                         src="src/images/s_logo2.png"
//                                         alt="brand_logo"
//                                         className="img-fluid vertical_middle"
//                                     />
//                                 </div>
//                             </div>
//                             <div className="item">
//                                 <div className="brand_logo_img text-center position-relative">
//                                     <img
//                                         src="src/images/s_logo3.png"
//                                         alt="brand_logo"
//                                         className="img-fluid vertical_middle"
//                                     />
//                                 </div>
//                             </div>
//                             <div className="item">
//                                 <div className="brand_logo_img text-center position-relative">
//                                     <img
//                                         src="src/images/s_logo4.png"
//                                         alt="brand_logo"
//                                         className="img-fluid vertical_middle"
//                                     />
//                                 </div>
//                             </div>
//                         </div>
//                     </div>
//                 </div>
//             </div>
//         </>
//     );
// };

// export default HomePages;

import { useEffect, useState } from "react";

import { Product } from "../types/Product";
import { Link } from "react-router-dom";

import { Banner, getBanners } from "../services/banners";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination, Autoplay } from "swiper/modules";
// import { getAllProduct } from "../axios/asiox";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/autoplay";
import { getAllProduct, getLatesProducts } from "../services/product";
import toast from "react-hot-toast";
import QuickViewProduct from "./QuickViewProduct";
// import { getAllProduct } from "../services/product";

const HomePages = () => {
    const [product, setProduct] = useState<Product[]>([]);
    const [lastProduct, getlatesProducts] = useState<Product[]>([]);
    const [selectedProductId, setSelectedProductId] = useState<string | null>(null);
    useEffect(() => {
        getAllProduct().then(({ data }) => {
            // setProduct(response.data);
            console.log("data", data);
            setProduct(data.data);
        });
    }, []);
    useEffect(() => {
        getLatesProducts().then(({ data }) => {
            // setProduct(response.data);
            console.log("data", data);
            getlatesProducts(data.data);
        });
    }, []);

    console.log("product", product);
    useEffect(() => {
        // Nếu bạn đang sử dụng thư viện như Revolution Slider, khởi tạo slider ở đây nếu cần.
        const script = document.createElement("script");
        script.src = "path_to_revolution_slider.js"; // Thêm đường dẫn script của slider nếu cần
        script.async = true;
        document.body.appendChild(script);
    }, []);
    const [banners, setBanners] = useState<Banner[]>([]);
    useEffect(() => {
        getBanners().then(({ data }) => {
            setBanners(data);
            console.log(data);
        });
    }, []);
  const toggleWishlist = (product: Product) => {
    const user = JSON.parse(localStorage.getItem("user") || "null");
  
    if (!user) {
      alert("Bạn cần đăng nhập để thêm sản phẩm vào danh sách yêu thích!");
      return;
    }
  
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
                                <img src={banner.image_url} alt="" className="img-fluid" />
                            </a>
                        </SwiperSlide>
                    ))}
                </Swiper>
            </div>
            <div className="main_section">
                {/*hình 2018*/}
                <div className="shoes_collection_section padding-top-60 wow fadeIn">
                    <div className="container">
                        <div className="row">
                            <div className="col-md-8 col-sm-8 wow fadeInLeft animated">
                                <div className="home_collection_content  position-relative">
                                    <img
                                        src="src/images/shoes_collection1.jpg"
                                        alt="women"
                                        className="img-fluid"
                                    />
                                    <div className="shoes_collection_content">
                                        <h2 className="text-uppercase title_h2">2025</h2>
                                        <p className="text-uppercase">Bộ siêu tập mới</p>
                                        <a
                                            href="/shop"
                                            className="background-btn  text-uppercase"
                                        >
                                            Mua ngay <i className="flaticon-arrows-4"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div className="col-md-4 col-sm-4 wow fadeInDown animated">
                                <div className="home_collection_content position-relative">
                                    <img
                                        src="src/images/shoes_collection2.jpg"
                                        alt="men"
                                        className="img-fluid"
                                    />
                                    <div className="shoes_collection_content">
                                        <span className="text-uppercase">Phiên Bản</span>
                                        <p className="text-uppercase">Nam</p>
                                    </div>
                                </div>
                                <div className="home_collection_content position-relative">
                                    <img
                                        src="src/images/shoes_collection3.png"
                                        alt="products"
                                        className="img-fluid"
                                    />
                                    <div className="shoes_collection_content">
                                        <span className="text-uppercase">Phiên bản</span>
                                        <p className="text-uppercase">Nữ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {/* hinh 2018 */}
                {/* New arrival */}
                <section className="padding-top-text-60 arrival_featured_section wow fadeIn">
                    <div className="container">
                        <div className="row">
                            <div className="col-lg-3 col-md-4 col-6">
                                <div className="arrival_collection_content position-relative">
                                    <img
                                        src="src/images/arrival_img.jpg"
                                        alt="arrival_img"
                                        className="img-fluid"
                                    />
                                    <div className="arrival_collection_text ">
                                        <h2 className="title_h2 text-uppercase">Sản phẩm mới</h2>
                                        <p className="text-uppercase">Ngay bây giờ</p>
                                    </div>
                                </div>
                            </div>
                            {lastProduct.map((lastproduct) => (
                                <div
                                    className="col-lg-3 col-md-4 col-6 wow fadeInLeft animated arrival"
                                    data-wow-duration="1300ms"
                                >
                                    <div className="featured_content">
                                        <div className="featured_img_content position-relative">
                                            <img
                                                src={lastproduct.image}
                                                className="img-product"
                                                alt="shoes_product"
                                            />
                                            <div className="featured_btn vertical_middle">
                                                <Link
                                                    to="#"
                                                    className="text-uppercase add_to_bag_btn rounded-circle d-block"
                                                    onClick={(e) => {
                                                        e.preventDefault(); // Ngăn chặn điều hướng nếu chỉ cần xử lý sự kiện
                                                        setSelectedProductId(lastproduct.id);
                                                    }}
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="18"
                                                        height="21"
                                                        viewBox="0 0 18 21"
                                                    >
                                                        <path
                                                            fill="#000"
                                                            data-name="Bag Icon copy"
                                                            className="cls-1"
                                                            d="M18,18.2L16.7,4.58a0.543,0.543,0,0,0-.56-0.474H13.411A4.278,4.278,0,0,0,9,0,4.278,4.278,0,0,0,4.588,4.106H1.856a0.549,0.549,0,0,0-.56.474L0,18.2v0.048A3.089,3.089,0,0,0,3.334,21H14.666A3.089,3.089,0,0,0,18,18.247V18.2ZM9,1.041a3.191,3.191,0,0,1,3.292,3.065H5.707A3.191,3.191,0,0,1,9,1.041Zm5.666,18.91H3.334a2.02,2.02,0,0,1-2.215-1.687L2.369,5.149h2.22v1.83a0.561,0.561,0,0,0,1.119,0V5.149h6.584v1.83a0.561,0.561,0,0,0,1.119,0V5.149h2.22l1.25,13.119A2.02,2.02,0,0,1,14.666,19.951Z"
                                                        />
                                                    </svg>
                                                </Link>
                                                <a
                                                    href={`/product_detail/${lastproduct.id}`}
                                                    className="text-uppercase  popup_btn rounded-circle d-block"
                                                    data-modal="#modalone"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="18"
                                                        height="11"
                                                        viewBox="0 0 18 11"
                                                    >
                                                        <path
                                                            fill="#000"
                                                            data-name="Forma 1"
                                                            className="cls-1"
                                                            d="M17.885,5.164C17.724,4.953,13.893,0,9,0S0.274,4.953.114,5.163a0.551,0.551,0,0,0,0,.672C0.274,6.046,4.106,11,9,11s8.725-4.953,8.885-5.164A0.55,0.55,0,0,0,17.885,5.164ZM9,9.861c-3.6,0-6.726-3.288-7.65-4.362C2.272,4.423,5.387,1.137,9,1.137S15.725,4.424,16.65,5.5C15.727,6.576,12.611,9.861,9,9.861ZM9,2.085A3.493,3.493,0,0,0,5.439,5.5,3.494,3.494,0,0,0,9,8.913,3.494,3.494,0,0,0,12.56,5.5,3.493,3.493,0,0,0,9,2.085ZM9,7.775A2.329,2.329,0,0,1,6.626,5.5,2.329,2.329,0,0,1,9,3.224,2.329,2.329,0,0,1,11.373,5.5,2.329,2.329,0,0,1,9,7.775Z"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    href="#"
                                                    className="heart yeuthich rounded-circle text-center d-block"
                                                    onClick={(e) => {
                                                        e.preventDefault();
                                                        toggleWishlist(lastproduct);
                                                    }}
                                                >
                                                    <i className="flaticon-heart"></i>
                                                </a>
                                            </div>
                                            <div className="product-label rounded-circle  newProduct">
                                                MỚI
                                            </div>
                                        </div>
                                        <div className="featured_detail_content position-relative">
                                            <a href={`/product_detail/${lastproduct.id}`}>
                                                <p className="featured_title  text-capitalize  ">
                                                    {lastproduct.name}
                                                </p>
                                            </a>
                                            <p className="featured_price title_h5 ">
                                            <span className="text-color">
                                  {lastproduct?.price
                                    ? Number(
                                        lastproduct.price
                                          .replace(/,/g, "")
                                          .replace(" VND", "")
                                      ).toLocaleString("vi-VN") + " VND"
                                    : "0 VND"}
                                </span>
                                            </p>
                                            <div className="featured_btn d-xl-none">
                                                s
                                                <a
                                                    href="cart.html"
                                                    className="text-uppercase add_to_bag_btn rounded-circle d-block"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="18"
                                                        height="21"
                                                        viewBox="0 0 18 21"
                                                    >
                                                        <path
                                                            fill="#000"
                                                            data-name="Bag Icon copy"
                                                            className="cls-1"
                                                            d="M18,18.2L16.7,4.58a0.543,0.543,0,0,0-.56-0.474H13.411A4.278,4.278,0,0,0,9,0,4.278,4.278,0,0,0,4.588,4.106H1.856a0.549,0.549,0,0,0-.56.474L0,18.2v0.048A3.089,3.089,0,0,0,3.334,21H14.666A3.089,3.089,0,0,0,18,18.247V18.2ZM9,1.041a3.191,3.191,0,0,1,3.292,3.065H5.707A3.191,3.191,0,0,1,9,1.041Zm5.666,18.91H3.334a2.02,2.02,0,0,1-2.215-1.687L2.369,5.149h2.22v1.83a0.561,0.561,0,0,0,1.119,0V5.149h6.584v1.83a0.561,0.561,0,0,0,1.119,0V5.149h2.22l1.25,13.119A2.02,2.02,0,0,1,14.666,19.951Z"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    href="javascript:void(0);"
                                                    className="text-uppercase  popup_btn rounded-circle d-block"
                                                    data-modal="#modalone"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="18"
                                                        height="11"
                                                        viewBox="0 0 18 11"
                                                    >
                                                        <path
                                                            fill="#000"
                                                            data-name="Forma 1"
                                                            className="cls-1"
                                                            d="M17.885,5.164C17.724,4.953,13.893,0,9,0S0.274,4.953.114,5.163a0.551,0.551,0,0,0,0,.672C0.274,6.046,4.106,11,9,11s8.725-4.953,8.885-5.164A0.55,0.55,0,0,0,17.885,5.164ZM9,9.861c-3.6,0-6.726-3.288-7.65-4.362C2.272,4.423,5.387,1.137,9,1.137S15.725,4.424,16.65,5.5C15.727,6.576,12.611,9.861,9,9.861ZM9,2.085A3.493,3.493,0,0,0,5.439,5.5,3.494,3.494,0,0,0,9,8.913,3.494,3.494,0,0,0,12.56,5.5,3.493,3.493,0,0,0,9,2.085ZM9,7.775A2.329,2.329,0,0,1,6.626,5.5,2.329,2.329,0,0,1,9,3.224,2.329,2.329,0,0,1,11.373,5.5,2.329,2.329,0,0,1,9,7.775Z"
                                                        />
                                                    </svg>
                                                </a>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                            {/* Quick View hiển thị khi có productId */}
                            {selectedProductId && (
                                <QuickViewProduct
                                    productId={selectedProductId}
                                    onClose={() => setSelectedProductId(null)}
                                />
                            )}
                        </div>
                    </div>
                </section>
                {/* new arrival */}
                <section className="padding-top-60 wow fadeIn">
                    <div className="container">
                        <div className="promoton_collection_section text-center wow fadeInUp position-relative">
                            <p className="position-relative">MÙA HÈ BẮT ĐẦU</p>
                            <h2 className="title_h2 text-capitalize position-relative">
                                KHUYẾN MẠI GIẢM GIÁ 50%
                            </h2>
                            <a
                                href="product_list_with_sidebar.html"
                                className="background-btn text-uppercase position-relative"
                            >
                                MUA NGAY
                                <i className="flaticon-arrows-4"></i>
                            </a>
                        </div>
                    </div>
                </section>

                {/* best selles */}
                <section className="padding-top-text-60 arrival_featured_section wow fadeIn">
                    <div className="container">
                        <div className="shoes_featured_title">
                            <h3 className="title_h3 text-uppercase">
                                Sản phẩm bán chạy nhất
                            </h3>
                            <p className="shoes_featured_title_link mb-0">
                                <a href="/shop" className="text-uppercase">
                                    Xem thêm<i className="flaticon-arrows-4"></i>
                                </a>
                            </p>
                        </div>
                        <div className="row">
                            {product.map((product) => (
                                <div
                                    className="col-lg-3 col-md-4 col-6 wow fadeInLeft animated"
                                    data-wow-duration="1300ms"
                                >
                                    <div className="featured_content">
                                        <div className="featured_img_content position-relative">
                                            <img
                                                src={product.image}
                                                className="img-product"
                                                alt="shoes_product"
                                            />
                                            <div className="featured_btn vertical_middle">
    
                                                <Link
                                                    to="#"
                                                    className="text-uppercase add_to_bag_btn rounded-circle d-block"
                                                    onClick={(e) => {
                                                        e.preventDefault(); // Ngăn chặn điều hướng nếu chỉ cần xử lý sự kiện
                                                        setSelectedProductId(product.id);
                                                    }}
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="18"
                                                        height="21"
                                                        viewBox="0 0 18 21"
                                                    >
                                                        <path
                                                            fill="#000"
                                                            data-name="Bag Icon copy"
                                                            className="cls-1"
                                                            d="M18,18.2L16.7,4.58a0.543,0.543,0,0,0-.56-0.474H13.411A4.278,4.278,0,0,0,9,0,4.278,4.278,0,0,0,4.588,4.106H1.856a0.549,0.549,0,0,0-.56.474L0,18.2v0.048A3.089,3.089,0,0,0,3.334,21H14.666A3.089,3.089,0,0,0,18,18.247V18.2ZM9,1.041a3.191,3.191,0,0,1,3.292,3.065H5.707A3.191,3.191,0,0,1,9,1.041Zm5.666,18.91H3.334a2.02,2.02,0,0,1-2.215-1.687L2.369,5.149h2.22v1.83a0.561,0.561,0,0,0,1.119,0V5.149h6.584v1.83a0.561,0.561,0,0,0,1.119,0V5.149h2.22l1.25,13.119A2.02,2.02,0,0,1,14.666,19.951Z"
                                                        />
                                                    </svg>
                                                </Link>
                                                <a
                                                    href={`/product_detail/${product.id}`}
                                                    className="text-uppercase  popup_btn rounded-circle d-block"
                                                    data-modal="#modalone"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="18"
                                                        height="11"
                                                        viewBox="0 0 18 11"
                                                    >
                                                        <path
                                                            fill="#000"
                                                            data-name="Forma 1"
                                                            className="cls-1"
                                                            d="M17.885,5.164C17.724,4.953,13.893,0,9,0S0.274,4.953.114,5.163a0.551,0.551,0,0,0,0,.672C0.274,6.046,4.106,11,9,11s8.725-4.953,8.885-5.164A0.55,0.55,0,0,0,17.885,5.164ZM9,9.861c-3.6,0-6.726-3.288-7.65-4.362C2.272,4.423,5.387,1.137,9,1.137S15.725,4.424,16.65,5.5C15.727,6.576,12.611,9.861,9,9.861ZM9,2.085A3.493,3.493,0,0,0,5.439,5.5,3.494,3.494,0,0,0,9,8.913,3.494,3.494,0,0,0,12.56,5.5,3.493,3.493,0,0,0,9,2.085ZM9,7.775A2.329,2.329,0,0,1,6.626,5.5,2.329,2.329,0,0,1,9,3.224,2.329,2.329,0,0,1,11.373,5.5,2.329,2.329,0,0,1,9,7.775Z"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    href="#"
                                                    className="heart yeuthich rounded-circle text-center d-block"
                                                    onClick={(e) => {
                                                        e.preventDefault();
                                                        toggleWishlist(product);
                                                    }}
                                                >
                                                    <i className="flaticon-heart"></i>
                                                </a>
        
                                            </div>
                                        </div>
                                        <div className="featured_detail_content">
                                            <a href={`/product_detail/${product.id}`}>
                                                <p className="featured_title  text-capitalize  ">
                                                    {product.name}
                                                </p>
                                            </a>
                                            <p className="featured_price title_h5  ">
                                            <span className="text-color">
                                  {product?.price
                                    ? Number(
                                        product.price
                                          .replace(/,/g, "")
                                          .replace(" VND", "")
                                      ).toLocaleString("vi-VN") + " VND"
                                    : "0 VND"}
                                </span>
                                            </p>
                                            <div className="featured_btn d-xl-none">
                                                <a
                                                    href="cart.html"
                                                    className="text-uppercase add_to_bag_btn rounded-circle d-block"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="18"
                                                        height="21"
                                                        viewBox="0 0 18 21"
                                                    >
                                                        <path
                                                            fill="#000"
                                                            data-name="Bag Icon copy"
                                                            className="cls-1"
                                                            d="M18,18.2L16.7,4.58a0.543,0.543,0,0,0-.56-0.474H13.411A4.278,4.278,0,0,0,9,0,4.278,4.278,0,0,0,4.588,4.106H1.856a0.549,0.549,0,0,0-.56.474L0,18.2v0.048A3.089,3.089,0,0,0,3.334,21H14.666A3.089,3.089,0,0,0,18,18.247V18.2ZM9,1.041a3.191,3.191,0,0,1,3.292,3.065H5.707A3.191,3.191,0,0,1,9,1.041Zm5.666,18.91H3.334a2.02,2.02,0,0,1-2.215-1.687L2.369,5.149h2.22v1.83a0.561,0.561,0,0,0,1.119,0V5.149h6.584v1.83a0.561,0.561,0,0,0,1.119,0V5.149h2.22l1.25,13.119A2.02,2.02,0,0,1,14.666,19.951Z"
                                                        />
                                                    </svg>
                                                </a>
                                                <a
                                                    href="javascript:void(0);"
                                                    className="text-uppercase  popup_btn rounded-circle d-block"
                                                    data-modal="#modalone"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="18"
                                                        height="11"
                                                        viewBox="0 0 18 11"
                                                    >
                                                        <path
                                                            fill="#000"
                                                            data-name="Forma 1"
                                                            className="cls-1"
                                                            d="M17.885,5.164C17.724,4.953,13.893,0,9,0S0.274,4.953.114,5.163a0.551,0.551,0,0,0,0,.672C0.274,6.046,4.106,11,9,11s8.725-4.953,8.885-5.164A0.55,0.55,0,0,0,17.885,5.164ZM9,9.861c-3.6,0-6.726-3.288-7.65-4.362C2.272,4.423,5.387,1.137,9,1.137S15.725,4.424,16.65,5.5C15.727,6.576,12.611,9.861,9,9.861ZM9,2.085A3.493,3.493,0,0,0,5.439,5.5,3.494,3.494,0,0,0,9,8.913,3.494,3.494,0,0,0,12.56,5.5,3.493,3.493,0,0,0,9,2.085ZM9,7.775A2.329,2.329,0,0,1,6.626,5.5,2.329,2.329,0,0,1,9,3.224,2.329,2.329,0,0,1,11.373,5.5,2.329,2.329,0,0,1,9,7.775Z"
                                                        />
                                                    </svg>
                                                </a>
                                                {/* <a
                                                    href="#"
                                                    className="heart yeuthich rounded-circle text-center d-block"
                                                    onClick={(e) => {
                                                        e.preventDefault();
                                                        toggleWishlist(product);
                                                    }}
                                                >
                                                    <i className="flaticon-heart"></i>
                                                </a> */}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
                {/* best */}
                <section className="padding-top-text-60 wow fadeIn blog_section shoes_blog_section">
                    <div className="container">
                        <div className="shoes_featured_title">
                            <h3 className="title_h3 text-uppercase">Bài viết mới nhất</h3>
                            <p className="shoes_featured_title_link mb-0">
                                <Link to="/blog" className="text-uppercase">
                                    Xem thêm<i className="flaticon-arrows-4"></i>
                                </Link>
                            </p>
                        </div>
                        <div className="row">
                            <div
                                className="col-md-6 wow fadeInLeft"
                                data-wow-duration="1300ms"
                            >
                                <div className="blog_content">
                                    <div className="row">
                                        <div className="col-lg-6">
                                            <a href="blog_list_detail.html">
                                                <img
                                                    src="src/images/shoes_blog1.png"
                                                    alt="blog"
                                                    className="img-fluid"
                                                />
                                            </a>
                                        </div>
                                        <div className="col-lg-6">
                                            <span className="article__date">
                                                March 21, 2018 | Posted By Admin
                                            </span>
                                            <a href="blog_list_detail.html">
                                                <h5 className="article__title title_h5">
                                                    Sed ut perspiciatis unde omnisiste natus error sit
                                                </h5>
                                            </a>
                                            <p>
                                                There are many variations of passages of Lorem Ipsum
                                                available but the majority have...
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                className="col-md-6 wow fadeInRight"
                                data-wow-duration="1300ms"
                            >
                                <div className="blog_content">
                                    <div className="row">
                                        <div className="col-lg-6">
                                            <a href="blog_list_detail.html">
                                                <img
                                                    src="src/images/shoes_blog2.png"
                                                    alt="blog"
                                                    className="img-fluid"
                                                />
                                            </a>
                                        </div>
                                        <div className="col-lg-6">
                                            <span className="article__date">
                                                March 21, 2018 | Posted By Admin
                                            </span>
                                            <a href="blog_list_detail.html">
                                                <h5 className="article__title title_h5">
                                                    Voluptatem accusantium dolor emque laudantium
                                                </h5>
                                            </a>
                                            <p>
                                                Duis aute irure dolor in reprehenderit in voluptate
                                                velit esse cillum dolore eu fugiat...
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section className="padding-top-60 padding-bottom-60 instagram_section shoes_instagram_section  wow animated">
                    <div className="container">
                        <div className="row">
                            <div
                                className="col-md-12 wow fadeInLeft"
                                data-wow-duration="1300ms"
                            >
                                <div className="instagram_title text-center">
                                    <img src="src/images/ins_icon.png" alt="" />
                                    <h4 className="title_h4  text-uppercase">
                                        Follow Us On Instagram{" "}
                                    </h4>
                                    <p>
                                        Shop our favorites and share yours!{" "}
                                        <span>#Running Shoes Trending Style Sneakers</span>
                                    </p>
                                </div>
                            </div>
                            <div
                                className="col-md-12 wow fadeInRight"
                                data-wow-duration="1300ms"
                                data-wow-delay="0.2s"
                            >
                                <div
                                    className="owl-carousel owl-theme instagram_slider"
                                    id="shoes_instafeed"
                                ></div>
                            </div>
                        </div>
                    </div>
                </section>
                <div className="padding-top-60 brand_logo_section shoes_brand_logo_section wow fadeIn">
                    <div className="container">
                        <div id="brand_slider" className="owl-carousel">
                            <div className="item">
                                <div className="brand_logo_img text-center position-relative">
                                    <img
                                        src="src/images/s_logo1.png"
                                        alt="brand_logo"
                                        className="img-fluid vertical_middle"
                                    />
                                </div>
                            </div>
                            <div className="item">
                                <div className="brand_logo_img text-center position-relative">
                                    <img
                                        src="src/images/s_logo2.png"
                                        alt="brand_logo"
                                        className="img-fluid vertical_middle"
                                    />
                                </div>
                            </div>
                            <div className="item">
                                <div className="brand_logo_img text-center position-relative">
                                    <img
                                        src="src/images/s_logo3.png"
                                        alt="brand_logo"
                                        className="img-fluid vertical_middle"
                                    />
                                </div>
                            </div>
                            <div className="item">
                                <div className="brand_logo_img text-center position-relative">
                                    <img
                                        src="src/images/s_logo4.png"
                                        alt="brand_logo"
                                        className="img-fluid vertical_middle"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default HomePages;