import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { Category, getAllCategory } from "../services/category";
import { Brand, getBrand } from "../services/brand";
import { Product } from "../types/Product";
import { getAllProduct } from "../services/product";

const Shop = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [filteredProducts, setFilteredProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [sortBy, setSortBy] = useState<"asc" | "dsc" | "">("");
  const [selectedCategories, setSelectedCategories] = useState<string[]>([]);
  const [selectedBrands, setSelectedBrands] = useState<string[]>([]);

  useEffect(() => {
    setLoading(true);
    getAllProduct()
      .then(({ data }) => {
        setProducts(data.data);
        setFilteredProducts(data.data);
      })
      .finally(() => setLoading(false));
  }, []);

  // useEffect(() => {
  //   let updatedProducts = [...products];

  //   if (selectedCategories.length) {
  //     updatedProducts = updatedProducts.filter((product) =>
  //       selectedCategories.includes(product.category)
  //     );
  //   }

  //   if (selectedBrands.length) {
  //     updatedProducts = updatedProducts.filter((product) =>
  //       selectedBrands.includes(product.brand)
  //     );
  //   }

  //   if (sortBy) {
  //     updatedProducts.sort((a, b) => {
  //       const priceA =
  //         typeof a.price === "string"
  //           ? Number(a.price.replace(" VND", ""))
  //           : a.price;
  //       const priceB =
  //         typeof b.price === "string"
  //           ? Number(b.price.replace(" VND", ""))
  //           : b.price;
  //       return sortBy === "asc" ? priceA - priceB : priceB - priceA;
  //     });
  //   }

  //   setFilteredProducts(updatedProducts);
  // }, [sortBy, selectedCategories, selectedBrands, products]);
  const [priceRange, setPriceRange] = useState<[number, number] | null>(null);
  useEffect(() => {
    let updatedProducts = [...products];

    if (selectedCategories.length) {
      updatedProducts = updatedProducts.filter((product) =>
        selectedCategories.includes(product.category)
      );
    }

    if (selectedBrands.length) {
      updatedProducts = updatedProducts.filter((product) =>
        selectedBrands.includes(product.brand)
      );
    }

    if (priceRange) {
      updatedProducts = updatedProducts.filter((product) => {
        // Chuyển `price` từ chuỗi thành số đúng
        const price =
          typeof product.price === "string"
            ? Number(product.price.replace(/,/g, "").replace(" VND", ""))
            : product.price;

        return price >= priceRange[0] && price <= priceRange[1];
      });
    }

    setFilteredProducts(updatedProducts);
  }, [selectedCategories, selectedBrands, priceRange, products]);
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
  // const [colors, setColors] = useState<Color[]>([]);

  // Giả lập API call

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
                        {/* <form className="py-2">
                          <ul>
                            <li>
                              <div className="flex items-center gap-3">
                                <label
                                  style={{
                                    fontSize: "16px",
                                    fontFamily: "Arial, sans-serif",
                                  }}
                                >
                                  <input
                                    type="radio"
                                    name="sortBy"
                                    value="asc"
                                    checked={sortBy === "asc"}
                                    onChange={() => setSortBy("asc")}
                                  />{" "}
                                  Giá - Giá từ bé đến lớn
                                </label>
                              </div>
                              <div className="flex items-center gap-3">
                                <label
                                  style={{
                                    fontSize: "16px",
                                    fontFamily: "Arial, sans-serif",
                                  }}
                                >
                                  <input
                                    type="radio"
                                    name="sortBy"
                                    value="dsc"
                                    checked={sortBy === "dsc"}
                                    onChange={() => setSortBy("dsc")}
                                  />{" "}
                                  Giá - Giá từ lớn đến bé
                                </label>
                              </div>
                            </li>
                          </ul>
                        </form> */}
                        <form className="price-filter-container">
                          <ul className="price-filter-list">
                            <li className="price-filter-item">
                              <input
                                type="radio"
                                name="priceRange"
                                id="price-range-all"
                                onChange={() => setPriceRange(null)}
                              />
                              <label htmlFor="price-range-all">Tất cả</label>
                            </li>
                            <li className="price-filter-item">
                              <input
                                type="radio"
                                name="priceRange"
                                id="price-range-1"
                                onChange={() => setPriceRange([0, 200000])}
                              />
                              <label htmlFor="price-range-1">0 - 200K</label>
                            </li>
                            <li className="price-filter-item">
                              <input
                                type="radio"
                                name="priceRange"
                                id="price-range-2"
                                onChange={() => setPriceRange([200000, 500000])}
                              />
                              <label htmlFor="price-range-2">200K - 500K</label>
                            </li>
                            <li className="price-filter-item">
                              <input
                                type="radio"
                                name="priceRange"
                                id="price-range-3"
                                onChange={() =>
                                  setPriceRange([500000, 1000000])
                                }
                              />
                              <label htmlFor="price-range-3">
                                500K - 1000K
                              </label>
                            </li>
                          </ul>
                        </form>
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
                                  to="/cart"
                                  className="text-uppercase background-btn add_to_bag_btn"
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
                                href="javascript:void(0);"
                                className="heart rounded-circle text-center"
                              >
                                <i className="flaticon-heart vertical_middle"></i>
                              </a>
                            </div>
                            <div className="featured_detail_content">
                              <a href="product_list_detail.html">
                                <p className="featured_title text-capitalize text-center">
                                  {product.name}
                                </p>
                              </a>
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
    </>
  );
};

export default Shop;
