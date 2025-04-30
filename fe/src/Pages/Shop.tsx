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
import Pagination from "./Pagination"; // Import component Pagination

const Shop = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [filteredProducts, setFilteredProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [selectedCategories, setSelectedCategories] = useState<string[]>([]);
  const [selectedBrands, setSelectedBrands] = useState<string[]>([]);
  const [selectedProductId, setSelectedProductId] = useState<string | null>(null);
  const [priceRange, setPriceRange] = useState<[number, number]>([0,5000000]);
  const [searchTerm, setSearchTerm] = useState<string>("");
  const navigate = useNavigate();
  const location = useLocation();

  // Phân trang
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 9;
  const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
  const paginatedProducts = filteredProducts.slice(
    (currentPage - 1) * itemsPerPage,
    currentPage * itemsPerPage
  );

  // Lấy sản phẩm từ API
  useEffect(() => {
    setLoading(true);
    getAllProduct()
      .then(async (response) => {
        console.log("Response status:", response.status);
        console.log("Response headers:", response.headers);

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          const text = await response.data.text();
          console.log("Response is not JSON. Raw response:", text);
          throw new Error('Response is not JSON');
        }

        const data = response.data;
        console.log("Log 1 - API products:", data);
        console.log("Log 1 - API products count:", data.data.length);
        setProducts(data.data); // Gán mảng sản phẩm
        setFilteredProducts(data.data); // Gán mảng sản phẩm
      })
      .catch((error) => {
        console.error("Error fetching products:", error);
        toast.error("Không thể tải sản phẩm!");
      })
      .finally(() => setLoading(false));
  }, []);

  // Lấy danh mục và thương hiệu
  const [categories, setCategories] = useState<Category[]>([]);
  const [brands, setBrands] = useState<Brand[]>([]);
  useEffect(() => {
    Promise.all([getAllCategory(), getBrand()])
      .then(([{ data: categoryData }, { data: brandData }]) => {
        setCategories(categoryData);
        setBrands(brandData);
      })
      .catch((error) => {
        console.error("Error fetching categories/brands:", error);
        toast.error("Không thể tải danh mục/thương hiệu!");
      });
  }, []);

  // Xử lý tìm kiếm
  const handleSearch = () => {
    if (searchTerm.trim()) {
      navigate(`/shop?search=${encodeURIComponent(searchTerm)}`);
    }
  };

  // Lấy searchTerm từ URL
  useEffect(() => {
    const queryParams = new URLSearchParams(location.search);
    const search = queryParams.get("search");
    if (search) {
      setSearchTerm(search);
    }
  }, [location]);

  // Áp dụng bộ lọc
  useEffect(() => {
    let updatedProducts = [...products];
    console.log("Log 2 - Initial products:", updatedProducts);
    console.log("Log 2 - Initial products count:", updatedProducts.length);

    if (selectedCategories.length) {
      updatedProducts = updatedProducts.filter((product) =>
        selectedCategories.includes(product.category)
      );
      console.log("Log 3 - After category filter:", updatedProducts);
      console.log("Log 3 - After category filter count:", updatedProducts.length);
    }

    if (selectedBrands.length) {
      updatedProducts = updatedProducts.filter((product) =>
        selectedBrands.includes(product.brand)
      );
      console.log("Log 4 - After brand filter:", updatedProducts);
      console.log("Log 4 - After brand filter count:", updatedProducts.length);
    }

    if (priceRange) {
      updatedProducts = updatedProducts.filter((product) => {
        const price =
          typeof product.price === "string"
            ? Number(product.price.replace(/,/g, "").replace(" VND", ""))
            : product.price;
        return price >= priceRange[0] && price <= priceRange[1];
      });
      console.log("Log 5 - After price filter:", updatedProducts);
      console.log("Log 5 - After price filter count:", updatedProducts.length);
    }

    if (searchTerm) {
      const lowerSearchTerm = searchTerm.toLowerCase();
      const searchWords = lowerSearchTerm.split(" ");
      updatedProducts = updatedProducts.filter((product) => {
        const productName = product.name.toLowerCase();
        return searchWords.every((word) => productName.includes(word));
      });
      console.log("Log 6 - After search filter:", updatedProducts);
      console.log("Log 6 - After search filter count:", updatedProducts.length);
    }

    console.log("Log 7 - Final filtered products:", updatedProducts);
    console.log("Log 7 - Final filtered products count:", updatedProducts.length);
    setFilteredProducts(updatedProducts);
  }, [selectedCategories, selectedBrands, priceRange, products, searchTerm]);

  // Xử lý thay đổi bộ lọc
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

  const handleChange = (newRange: number | number[]) => {
    if (Array.isArray(newRange)) {
      setPriceRange([newRange[0], newRange[1]]);
    }
  };

  // Xử lý wishlist
  const [wishlist, setWishlist] = useState<string[]>([]);

  useEffect(() => {
    const storedWishlist = JSON.parse(localStorage.getItem("wishlist") || "[]");
    setWishlist(storedWishlist);
  }, []);
  // const toggleWishlist = (product: Product) => {
  //   // const user = JSON.parse(localStorage.getItem("user") || "null");

  //   // if (!user) {
  //   //   alert("Bạn cần đăng nhập để thêm sản phẩm vào danh sách yêu thích!");
  //   //   return;
  //   // }

  //   let wishlist = JSON.parse(localStorage.getItem("wishlist") || "[]");

  //   // Lưu ID sản phẩm thay vì object
  //   const index = wishlist.indexOf(product.id);

  //   if (index !== -1) {
  //     wishlist.splice(index, 1);
  //   } else {
  //     wishlist.push(product.id);
  //     toast.success("Đã thêm sản phẩm yêu thích");
  //   }

  //   localStorage.setItem("wishlist", JSON.stringify(wishlist));

  //   // Phát sự kiện cập nhật để các component khác biết
  //   window.dispatchEvent(new Event("storage"));
  // };

  const toggleWishlist = (product: Product) => {
    let updatedWishlist = [...wishlist];
    const index = updatedWishlist.indexOf(product.id);
  
    if (index !== -1) {
      updatedWishlist.splice(index, 1);
    } else {
      updatedWishlist.push(product.id);
      toast.success("Đã thêm sản phẩm yêu thích");
    }
  
    localStorage.setItem("wishlist", JSON.stringify(updatedWishlist));
    setWishlist(updatedWishlist);
  
    // Nếu bạn cần phát event cho component khác, giữ cái này
    window.dispatchEvent(new Event("storage"));
  };

  // Log danh sách sản phẩm sau khi phân trang
  console.log("Log 8 - paginatedProducts:", paginatedProducts);
  console.log("Log 8 - paginatedProducts count:", paginatedProducts.length);

  // Hàm xử lý thay đổi trang
  const handlePageChange = (newPage: number) => {
    setCurrentPage(newPage);
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
        <section className="padding-top-text-60 padding-bottom-60 featured_section product_list_section product_list_filter_section">
          <div className="container">
            <div className="row">
              <div className="col-lg-3">
                <div className="collection_sidebar">
                  <div className="sidebar_title padding-bottom-60 hidden-lg">
                    <h3 className="title_h3">Filter</h3>
                    <a
                      className="filter_colse"
                      href="#"
                      onClick={(e) => e.preventDefault()}
                    >
                      <i className="flaticon-close"></i>
                    </a>
                  </div>
                  <div className="shopProduct">
                    <div className="loc">
                      <div className="layer-filter">
                        <div>
                          <h5>Danh mục</h5>
                          {categories.map((category) => (
                            <div className="checkbox" key={category.id}>
                              <label>
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
                        </div>
                        <div className="mt-4">
                          <h5>Thương hiệu</h5>
                          {brands.map((brand) => (
                            <div className="checkbox" key={brand.id}>
                              <label>
                                <input
                                  type="checkbox"
                                  checked={selectedBrands.includes(brand.name)}
                                  onChange={() => handleBrandChange(brand.name)}
                                />{" "}
                                {brand.name}
                              </label>
                            </div>
                          ))}
                        </div>
                        <div className="mt-4">
                          <h5>Giá</h5>
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
                              max={5000000}
                              step={10000}
                              value={priceRange}
                              onChange={handleChange}
                              style={{ width: "150px" }}
                            />
                            {priceRange[1].toLocaleString()}
                          </div>
                        </div>
                        <div className="mt-4">
                          <img
                            src="https://htmldemo.net/james/james/img/product/banner_left.jpg"
                            alt="Banner"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div className="col-lg-9 shopProduct">
                <div>
                  <div className="search1">
                    <input
                      className="search2"
                      type="text"
                      placeholder="Tìm kiếm..."
                      value={searchTerm}
                      onChange={(e) => setSearchTerm(e.target.value)}
                      onKeyDown={(e) => e.key === "Enter" && handleSearch()}
                    />
                    <div>Tổng: {filteredProducts.length}</div>
                  </div>
                  {loading ? (
                    <p>Đang tải...</p>
                  ) : paginatedProducts.length === 0 ? (
                    <p className="text-center text-gray-500">
                      Không có sản phẩm nào
                    </p>
                  ) : (
                    <div className="container">
                      <ul className="wow fadeIn row">
                        {paginatedProducts.map((last, index) => (
                          <div className="product-card1" key={last.id}>
                            {console.log(`Log 9 - Rendering product ${index + 1}:`, last)}
                            <a href={`/product_detail/${last.id}`}>
                              <img
                                className="product-image1"
                                src={last.image || "https://via.placeholder.com/150"}
                                alt={last.name}
                                loading="lazy"
                              />
                            </a>
                            <div className="product-name">
                              {last.name.slice(0, 20) +
                                (last.name.length > 20 ? "..." : "")}
                            </div>
                            <div className="product-price">
                              <strong>
                                {last?.price
                                  ? Number(
                                      last.price
                                        .replace(/,/g, "")
                                        .replace(" VND", "")
                                    ).toLocaleString("vi-VN") + " VND"
                                  : "0 VND"}
                              </strong>
                            </div>
                            <div className="rating">★★★★☆</div>
                            <div className="product-actions">
                              <Link
                                to="#"
                                className="text-uppercase add_to_bag_btn rounded-circle d-block"
                                onClick={(e) => {
                                  e.preventDefault();
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
                                  <i
  className={`flaticon-heart yeuthich ${wishlist.includes(last.id) ? "active" : ""}`}
/>
                                </a>
                                <i className="fas fa-sync-alt"></i>
                              </div>
                            </div>
                          </div>
                        ))}
                      </ul>
                    </div>
                  )}
                </div>

                {/* Sử dụng component Pagination */}
                <Pagination
                  currentPage={currentPage}
                  totalPages={totalPages}
                  onPageChange={handlePageChange}
                />
              </div>
            </div>
          </div>
        </section>
      </div>
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