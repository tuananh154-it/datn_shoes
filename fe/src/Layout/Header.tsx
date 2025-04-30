import MegaMenu from "./MegaMenu";
import { useDispatch, useSelector } from "react-redux";
import { RootState } from "../store/store";
import { useEffect, useState } from "react";
import { Link, NavLink, useNavigate } from "react-router-dom";
import { logout, refreshUser } from "../store/useSlice";
import { useCart } from "../context/CartContext";
import { Product } from "../types/Product";
import { getAllProduct } from "../services/product";
import toast from "react-hot-toast";

const Header = () => {
  const dispatch = useDispatch();
  const userId = useSelector((state: RootState) => state.user.user);
  const { totalItems } = useCart();
  const [hasRefreshedUser, setHasRefreshedUser] = useState(false); // Sử dụng state thay vì biến toàn cục

  useEffect(() => {
    let checkTokenInterval: number | null = null;
    let refreshInterval: number | null = null;

    const handleVisibilityChange = () => {
      if (document.hidden) {
        if (checkTokenInterval) clearInterval(checkTokenInterval);
        if (refreshInterval) clearInterval(refreshInterval);
      } else {
        // Only call refreshUser if it hasn't been called before
        if (localStorage.getItem("token") && !hasRefreshedUser) {
          dispatch(refreshUser());
          setHasRefreshedUser(true); // Mark that refreshUser has been called
        }

        checkTokenInterval = setInterval(() => {
          if (!localStorage.getItem("token") && userId) {
            dispatch(logout());
            toast.error("Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.");
            window.location.href = "/login";
          }
        }, 5 * 1000);

        refreshInterval = setInterval(() => {
          if (localStorage.getItem("token")) {
            dispatch(refreshUser());
          }
        }, 15 * 60 * 1000); // 15 minutes
      }
    };

    document.addEventListener("visibilitychange", handleVisibilityChange);
    handleVisibilityChange();

    return () => {
      document.removeEventListener("visibilitychange", handleVisibilityChange);
      if (checkTokenInterval) clearInterval(checkTokenInterval);
      if (refreshInterval) clearInterval(refreshInterval);
    };
  }, [dispatch, userId]);

  const handleLogout = () => {
    dispatch(logout());
    toast.success("Đăng xuất thành công!");
    window.location.href = "/login";
  };

  const [wishlistCount, setWishlistCount] = useState(0);

  const updateWishlistCount = () => {
    const wishlist = JSON.parse(localStorage.getItem("wishlist") || "[]");
    setWishlistCount(wishlist.length);
  };

  useEffect(() => {
    updateWishlistCount();
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
    getAllProduct().then(({ data }) => {
      setProducts(data.data);
    });
  }, []);

  const [isOpen, setIsOpen] = useState(false);
  const [searchTerm, setSearchTerm] = useState("");

  const navigate = useNavigate();
  const handleSearch = () => {
    if (searchTerm.trim()) {
      navigate(`/shop?search=${encodeURIComponent(searchTerm)}`);
      setIsOpen(false);
    }
  };

  const toggleDropdown = () => {
    setIsOpen(!isOpen);
  };

  return (
    <>
      <header className="shoes_header">
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
                <li className="nav-item">
                  <NavLink
                    to="/"
                    className={({ isActive }) =>
                      isActive
                        ? "nav-link text-uppercase active"
                        : "nav-link text-uppercase"
                    }
                  >
                    Trang chủ
                  </NavLink>
                </li>
                <MegaMenu />
                <li className="nav-item dropdown mega-dropdown">
                  <NavLink
                    to="/blog"
                    className={({ isActive }) =>
                      isActive
                        ? "nav-link text-uppercase active"
                        : "nav-link text-uppercase"
                    }
                  >
                    Bài viết
                  </NavLink>
                </li>
                <li className="nav-item dropdown mega-dropdown">
                  <NavLink
                    to="/contacts"
                    className={({ isActive }) =>
                      isActive
                        ? "nav-link text-uppercase active"
                        : "nav-link text-uppercase"
                    }
                  >
                    Liên hệ
                  </NavLink>
                </li>
              </ul>
            </div>
          </nav>
        </div>

        <div className="visible-lg d-none">
          <ul className="social_icons float-md-right">
            <li className="login_icon drop">
              {userId?.id ? (
                <div className="text-3xl cursor-pointer flex justify-center">
                  <a style={{ cursor: "pointer" }}>{userId.name}</a>
                  {userId?.id && (
                    <div
                      className="hover-bridge"
                      style={{
                        position: "absolute",
                        top: "100%",
                        left: 0,
                        width: "100%",
                        height: "10px",
                        zIndex: 9998,
                      }}
                    ></div>
                  )}
                </div>
              ) : (
                <Link to="/login">
                  <i className="flaticon-social"></i>
                </Link>
              )}

              {userId?.id && (
                <div className="dropdownUser">
                  <nav>
                    {userId?.role === "admin" || userId?.role === "superadmin" ? (
                      <Link
                        to={"http://127.0.0.1:8000/admin/dashboards"}
                        className="dropdown-item"
                      >
                        Admin
                      </Link>
                    ) : null}
                    <Link to="/myaccout" className="dropdown-item">
                      Trang cá nhân
                    </Link>
                    <button
                      className="dropdown-item dropdown-button"
                      onClick={handleLogout}
                    >
                      Đăng xuất
                    </button>
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
                  onKeyDown={(e) => e.key === "Enter" && handleSearch()}
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