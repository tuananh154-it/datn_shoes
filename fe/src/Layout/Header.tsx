import MegaMenu from "./MegaMenu";
import { useDispatch, useSelector } from "react-redux";
import { RootState } from "../store/store";
import { useEffect, useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { logout } from "../store/useSlice";
import { useCart } from "../context/CartContext";
import { Product } from "../types/Product";
import { getAllProduct } from "../services/product";


const Header = () => {
  const dispatch = useDispatch();
  const [menuDisplay, setMenuDisplay] = useState(false);
  const userId = useSelector((state: RootState) => state.user.user);
  const { totalItems } = useCart();

  const handleLogout = () => {
    dispatch(logout());
    window.location.href = "/login";
  };


  //số lượng trong yêu thích
  const [wishlistCount, setWishlistCount] = useState(0);

  // Hàm cập nhật số lượng wishlist
  const updateWishlistCount = () => {
    const wishlist = JSON.parse(localStorage.getItem("wishlist") || "[]");
    setWishlistCount(wishlist.length);
  };

  useEffect(() => {
    // Cập nhật số lượng khi component mount
    updateWishlistCount();

    // Lắng nghe sự kiện storage để cập nhật realtime
    const handleStorageChange = () => {
      updateWishlistCount();
    };

    window.addEventListener("storage", handleStorageChange);
    return () => {
      window.removeEventListener("storage", handleStorageChange);
    };
  }, []);
  const [products, setProducts] = useState<Product[]>([]);
    useEffect(() => {
       getAllProduct()
         .then(({ data }) => {
           setProducts(data.data);
         })
     }, []);

  const [isOpen, setIsOpen] = useState(false);
  const [searchTerm, setSearchTerm] = useState("");

  // Lọc sản phẩm theo tên nhập vào
  const navigate = useNavigate();

  // Xử lý khi nhấn tìm kiếm
  const handleSearch = () => {
    if (searchTerm.trim()) {
      // Thực hiện tìm kiếm và điều hướng
      navigate(`/shop?search=${encodeURIComponent(searchTerm)}`);
      
      // Tắt thanh tìm kiếm sau khi tìm kiếm xong
      setIsOpen(false);
    }
  };
  const toggleDropdown = () => {
    setIsOpen(!isOpen);
  };
  return (
    <>
      <header className="shoes_header shoes_home_header">
        <div className="header_logo col_4 visible-lg d-none">
          <a href="/">
            <img
              src="../src/images/logo_footvibe_01.png"
              className="logo"
              alt="logo"
            />
          </a>
        </div>

        <div className="col_6 visible-lg d-none">
          <nav className="navbar-dark navbar-expand-lg navbar">
            <div className="navbar-collapse collapse" id="collapseNavbar">
              <ul className="navbar-nav">
                <li className="nav-item active">
                  <Link to="/" className="nav-link text-uppercase">
                    Trang chủ
                  </Link>
                </li>
                <MegaMenu />
                <li className="nav-item dropdown mega-dropdown">
                  {/* <a className="nav-link text-uppercase" href="/blog">
                    Blog
                  </a> */}
                  <Link to="/blog" className="nav-link text-uppercase"  >
                    Bài viết
                  </Link>
                </li>
                <li className="nav-item dropdown mega-dropdown">
                  <Link to="/contacts" className="nav-link text-uppercase  dropdown-toggle" >
                    Liên Hệ
                  </Link>
                </li>

              </ul>
            </div>
          </nav>
        </div>
        <div className=" visible-lg d-none">
          <ul className="social_icons float-md-right">
            <li className="login_icon drop">
              {userId?.id ? (
                <div
                  className="text-3xl cursor-pointer relative flex justify-center"
                  onClick={() => setMenuDisplay((prev) => !prev)}
                >
                  <a style={{ cursor: "pointer" }}>{userId.name}</a>
                </div>
              ) : (
                <Link to="/login">
                  <i className="flaticon-social"></i>
                </Link>
              )}

              {userId?.id && menuDisplay && (
                <div className="dropdownUser p-4">
                  <nav>
                    {userId?.role === "admin" || userId?.role === "superadmin" && (
                      <Link to={"http://127.0.0.1:8000/admin/dashboards"} className='whitespace-nowrap hidden md:block hover:bg-slate-100 p-2' onClick={() => setMenuDisplay(prev => !prev)}>Admin</Link>
                    )}
                    <Link to="/myaccout" className="p-2">Trang cá nhân</Link>
                    
                    <button className="btn btn-danger mt-3" onClick={handleLogout}>Logout</button>
                  </nav>
                </div>
              )}
            </li>
            <li className="cart_icon">
              <Link to="/wishlist">
                <i className="flaticon-heart"></i>
                <span className="count text-white rounded-circle text-center">
                {wishlistCount}
                </span>
              </Link>
            </li>
            <li className="search_icon" onClick={toggleDropdown}>
        <a href="#">
          <i className="flaticon-magnifying-glass"></i>
        </a>
      </li>

      {isOpen && (
        <div className="search-dropdown">
          <input
            type="text"
            placeholder="Tìm kiếm sản phẩm..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            onKeyDown={(e) => e.key === "Enter" && handleSearch()} // Tìm kiếm khi nhấn Enter
          />
          <button onClick={handleSearch}>Tìm</button>
        </div>
      )}
            <li className="cart_icon">
              <Link to="/cart">
                <i className="flaticon-shopping-bag"></i>
                <span className="count text-white rounded-circle text-center">
                  {totalItems}
                </span>
              </Link>
            </li>
          </ul>
        </div>
      </header>
    </>
  );
};

export default Header;
