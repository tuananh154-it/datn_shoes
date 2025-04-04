import { useEffect, useState } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { Category, getAllCategory } from "../services/category";
import { Brand, getBrand } from "../services/brand";
import { Product } from "../types/Product";
import { getAllProduct } from "../services/product";

import Slider from "rc-slider";
import "rc-slider/assets/index.css";

import QuickViewProduct from "./QuickViewProduct";
import toast from "react-hot-toast";


const Shop = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [filteredProducts, setFilteredProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  // const [sortBy, setSortBy] = useState<"asc" | "dsc" | "">("");
  const [selectedCategories, setSelectedCategories] = useState<string[]>([]);
  const [selectedBrands, setSelectedBrands] = useState<string[]>([]);
  const [selectedProductId, setSelectedProductId] = useState<string | null>(null);
  useEffect(() => {
    setLoading(true);
    getAllProduct()
      .then(({ data }) => {
        setProducts(data.data);
        setFilteredProducts(data.data);
      })
      .finally(() => setLoading(false));
  }, []);
  console.log("product",products)
  const location = useLocation();
  const [priceRange, setPriceRange] = useState<[number, number]>([0, 3000000]);
  const [searchTerm, setSearchTerm] = useState<string>("");
  useEffect(() => {
    const queryParams = new URLSearchParams(location.search);
    const search = queryParams.get("search");
    if (search) {
      setSearchTerm(search);
    }
  }, [location]);
  useEffect(() => {
    let updatedProducts = [...products];

    // Lọc theo thể loại
    if (selectedCategories.length) {
      updatedProducts = updatedProducts.filter((product) =>
        selectedCategories.includes(product.category)
      );
    }

    // Lọc theo nhãn hiệu
    if (selectedBrands.length) {
      updatedProducts = updatedProducts.filter((product) =>
        selectedBrands.includes(product.brand)
      );
    }

    // Lọc theo khoảng giá
    if (priceRange) {
      updatedProducts = updatedProducts.filter((product) => {
        const price =
          typeof product.price === "string"
            ? Number(product.price.replace(/,/g, "").replace(" VND", ""))
            : product.price;

        return price >= priceRange[0] && price <= priceRange[1];
      });
    }

    // Lọc theo từ khóa tìm kiếm
    if (searchTerm) {
      const lowerSearchTerm = searchTerm.toLowerCase();
      const searchWords = lowerSearchTerm.split(" "); // Tách từ khóa tìm kiếm thành từng từ
    
      updatedProducts = updatedProducts.filter((product) => {
        const productName = product.name.toLowerCase();
        // Kiểm tra xem TẤT CẢ từ trong searchTerm có xuất hiện trong tên sản phẩm không
        return searchWords.every((word) => productName.includes(word));
      });
    }

    // Cập nhật danh sách sản phẩm đã lọc
    setFilteredProducts(updatedProducts);
  }, [
    selectedCategories,
    selectedBrands,
    priceRange,
    products,
    searchTerm, // Thêm searchTerm vào dependencies
  ]);
  useEffect(() => {
    const fetchProducts = async () => {
      // Fetch dữ liệu sản phẩm từ API (giả sử bạn có hàm fetch)
      const response = await fetch('/api/products');
      const data = await response.json();
      setProducts(data);
    };

    fetchProducts();
  }, []);
  const handleCategoryChange = (category: string) => {
    setSelectedCategories((prev) =>
      prev.includes(category)
        ? prev.filter((c) => c !== category)
        : [...prev, category]
    );
  };

  const handleBrandChange = (brand: string) => {
    setSelectedBrands((prev) =>
      prev.includes(brand) ? prev.filter((b) => b !== brand) : [...prev, brand]
    );
  };

  const [categories, setCategories] = useState<Category[]>([]);
  const [brands, setBrands] = useState<Brand[]>([]);

  useEffect(() => {
    getAllCategory().then(({ data }) => setCategories(data));
    getBrand().then(({ data }) => setBrands(data));
  }, []);
  const handleChange = (newRange: number | number[]) => {
    if (Array.isArray(newRange)) {
      setPriceRange([newRange[0], newRange[1]]);
    }
  };
  const navigator = useNavigate();
  const toggleWishlist = (product: Product) => {
    const user = JSON.parse(localStorage.getItem("user") || "null");
  
    if (!user) {
      alert("Bạn cần đăng nhập để thêm sản phẩm vào danh sách yêu thích!");
      navigator('/login')
      return
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
      <div className="main_section">
        <section className="breadcrumb_section nav">
          <div className="container">
            <nav aria-label="breadcrumb">
              <ol className="breadcrumb">
                <li className="breadcrumb-item text-capitalize">
                  <a href="earthyellow.html">Trang chủ</a>{" "}
                  <i className="flaticon-arrows-4"></i>
                </li>
                <li className="breadcrumb-item active text-capitalize">
                  Cửa hàng
                </li>
              </ol>
            </nav>
            <h1 className="title_h1 font-weight-normal text-capitalize">
              Cửa hàng
            </h1>
          </div>
        </section>
        <section className="padding-top-text-60 padding-bottom-60 featured_section product_list_section product_list_filter_section ">
          <div className="container">
            <div className="row">
              <div className="col-lg-3">
                <div className="collection_sidebar">
                  <div className="sidebar_title padding-bottom-60 hidden-lg">
                    <h3 className="title_h3">Filter</h3>
                    <a className="filter_colse" href="javascript:void(0);">
                      <i className="flaticon-close"></i>
                    </a>
                  </div>
                  <div className="filter_content mCustomScrollbar">
                    {/* //giá// */}
                    <div className="category_list">
                      <div className="category_list_title">
                        <h5 className="title_h5">Giá</h5>
                        <span className="category_close_icon flaticon-down-arrow float-right"></span>
                      </div>
                      <div className="layer-filter">
                        <div>
                          {/* <label>Khoảng giá: {priceRange[0].toLocaleString()} - {priceRange[1].toLocaleString()} VND</label> */}
                          <div
                            style={{
                              display: "flex",
                              alignItems: "center",
                              gap: "10px",
                            }}
                          >
                            {priceRange[0].toLocaleString()}
                            <Slider
                              range
                              min={0}
                              max={3000000}
                              step={10000}
                              value={priceRange}
                              onChange={handleChange}
                              style={{ width: "150px", margin: "10px auto" }}
                            />
                            {priceRange[1].toLocaleString()}
                          </div>
                        </div>
                      </div>
                    </div>
                    {/* //category// */}
                    <div className="category_list">
                      <div className="category_list_title">
                        <h5 className="title_h5">Danh mục</h5>
                        <span className="category_close_icon flaticon-down-arrow float-right"></span>
                      </div>
                      <div className="layer-filter">
                        <ul>
                          {categories.map((category) => (
                            <div className="checkbox">
                              <label
                                key={category.id}
                                style={{
                                  fontSize: "16px",
                                  fontFamily: "Arial, sans-serif",
                                }}
                              >
                                <input
                                  type="checkbox"
                                  checked={selectedCategories.includes(
                                    category.name
                                  )}
                                  onChange={() =>
                                    handleCategoryChange(category.name)
                                  }
                                />{" "}
                                {category.name}
                              </label>
                            </div>
                          ))}
                        </ul>
                      </div>
                    </div>
                    {/* //brand// */}
                    <div className="category_list">
                      <div className="category_list_title">
                        <h5 className="title_h5">Thương hiệu</h5>
                        <span className="category_close_icon flaticon-down-arrow float-right"></span>
                      </div>
                      <div className="layer-filter">
                        <ul>
                          {brands.map((brand) => (
                            <div className="checkbox">
                              <label
                                key={brand.id}
                                style={{
                                  fontSize: "16px",
                                  fontFamily: "Arial, sans-serif",
                                }}
                              >
                                <input
                                  type="checkbox"
                                  checked={selectedBrands.includes(brand.name)}
                                  onChange={() => handleBrandChange(brand.name)}
                                />{" "}
                                {brand.name}
                              </label>
                            </div>
                          ))}
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div className="col-lg-9">
                <div className="collection-sorting-row">
                  <div className="filter_menu hidden-lg ">
                    <a className="title_h5 text-capitalize">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="21"
                        height="16"
                        viewBox="0 0 21 16"
                      >
                        <path
                          id="Filter_Icon"
                          data-name="Filter Icon"
                          fill="#f74f2e"
                          className="cls-1"
                          d="M0,13H5v2H0V13Zm5-1h6v4H5V12Zm6,1H21v2H11V13Zm7-6h3V9H18V7ZM12,6h6v4H12V6ZM0,7H12V9H0V7ZM0,1H3V3H0V1ZM3,0H9V4H3V0ZM9,1H21V3H9V1Z"
                        />
                      </svg>{" "}
                      filter
                    </a>
                  </div>
                  <div className="short_by">
                    <form>
                      <div className="htmlForm-group">
                        <label htmlFor="short_by" className="title_h5">
                          Sắp xếp theo :
                        </label>
                        <select
                          className="sanphamnoibat"
                          id="short_by"
                          name="short_by"
                        >
                          <option>Sản phẩm nổi bật</option>
                        </select>
                      </div>
                    </form>
                  </div>
                  <div className="product_grid visible-lg d-none">
                    <ul>
                      <li className="grid_2 grid-list" data-column="column2">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="20"
                          height="30"
                          viewBox="0 0 20 30"
                        >
                          <path
                            id="Rectangle_21_copy_20"
                            data-name="Rectangle 21 copy 20"
                            fill="#aaa"
                            className="cls-1"
                            d="M12.008,11.006h7.986v8H12.008v-8Zm-12,0H7.994v8H0.008v-8Zm0,11H7.994v8H0.008v-8Zm12,0h7.986v8H12.008v-8Zm-12-22H8v8H0.009v-8ZM12,0.006h7.991v8H12v-8Z"
                          />
                        </svg>
                      </li>
                      <li
                        className="grid_3 active grid-list"
                        data-column="column3 product"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="32"
                          height="30"
                          viewBox="0 0 32 30"
                        >
                          <path
                            id="Rectangle_21_copy_22"
                            data-name="Rectangle 21 copy 22"
                            fill="#aaa"
                            className="cls-1"
                            d="M12.008,11.006h7.986v8H12.008v-8Zm-12,0H7.994v8H0.008v-8Zm0,11H7.994v8H0.008v-8Zm12,0h7.986v8H12.008v-8Zm-12-22H8v8H0.009v-8ZM12,0.006h7.991v8H12v-8Zm12,11h7.986v8H24.008v-8Zm0,11h7.986v8H24.008v-8Zm0-22h7.991v8H24v-8Z"
                          />
                        </svg>
                      </li>
                      <li className="grid_4 grid-list" data-column="column4">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="41"
                          height="30"
                          viewBox="0 0 41 30"
                        >
                          <path
                            id="Rectangle_21_copy_29"
                            data-name="Rectangle 21 copy 29"
                            fill="#aaa"
                            className="cls-1"
                            d="M11.008,11.006h7.986v8H11.008v-8Zm-11,0H7.994v8H0.008v-8Zm0,11H7.994v8H0.008v-8Zm11,0h7.986v8H11.008v-8Zm-11-22H8v8H0.008v-8ZM11,0.006h7.991v8H11v-8Zm11,11h7.986v8H22.008v-8Zm0,11h7.986v8H22.008v-8Zm0-22h7.991v8H22v-8Zm11,11h7.986v8H33.008v-8Zm0,11h7.986v8H33.008v-8Zm0-22h7.991v8H33v-8Z"
                          />
                        </svg>
                      </li>
                    </ul>
                  </div>
                  <div className="short_by show_product text-right">
                    <form>
                      <div className="htmlForm-group">
                        <label htmlFor="show" className="title_h5">
                          Tổng :
                        </label>
                        <select className="show" id="show" name="show">
                          <option>{products.length}</option>
                        </select>
                      </div>
                    </form>
                  </div>
                </div>
                <div>
                  {loading ? (
                    <p>Đang tải...</p>
                  ) : filteredProducts.length === 0 ? (
                    <p className="text-center text-gray-500">
                      Không có sản phẩm nào
                    </p>
                  ) : (
                    <ul className="category-products wow fadeIn row">
                      {filteredProducts.map((product) => (
                        <li
                          className="col-lg-3 col-md-4 col-6 column3 product wow fadeInLeft animated"
                          data-wow-duration="1300ms"
                          key={product.id}
                        >
                          <div className="featured_content">
                            <div className="featured_img_content">
                              <img
                                src={product.image}
                                alt="f_product"
                                className="img-fluid11"
                              />
                              <div className="featured_btn vertical_middle">
                                <Link
                                  to="#"
                                  className="text-uppercase background-btn add_to_bag_btn"
                                  onClick={(e) => {
                                    e.preventDefault(); // Ngăn chặn điều hướng nếu chỉ cần xử lý sự kiện
                                    setSelectedProductId(product.id);
                                  }}
                                >
                                  Thêm vào giỏ hàng
                                </Link>
                                <Link
                                  to={`/product_detail/${product.id}`}
                                  className="text-uppercase border-btn popup_btn"
                                  data-modal="#modalone"
                                >
                                  Xem chi tiết
                                </Link>
                              </div>
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
                            <div className="featured_detail_content">
                              <Link to={`/product_detail/${product.id}`}>
                                <p className="featured_title text-capitalize text-center">
                                  {product.name.slice(0, 25) + (product.name.length > 6 ? "..." : "")}
                                </p>
                              </Link>
                              <p className="featured_price title_h5 text-center">
                                {/* <span>{product.price.toLocaleString()}</span> */}
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
                            </div>
                          </div>
                        </li>
                      ))}
                    </ul>
                  )}
                </div>
                <div className="align-self-center">
                  <ul className="pagination text-center justify-content-center">
                    <li className="page-item">
                      <a className="page-link" href="javascript:void(0);">
                        <i className="flaticon-arrows-1"></i>
                      </a>
                    </li>
                    <li className="page-item">
                      <a className="page-link" href="javascript:void(0);">
                        1
                      </a>
                    </li>
                    <li className="page-item active">
                      <a className="page-link" href="javascript:void(0);">
                        2
                      </a>
                    </li>
                    <li className="page-item">
                      <a className="page-link" href="javascript:void(0);">
                        3
                      </a>
                    </li>
                    <li className="page-item">
                      <a className="page-link" href="javascript:void(0);">
                        <i className="flaticon-arrows"></i>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
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

export default Shop;