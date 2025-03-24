import MegaMenu from "./MegaMenu";
import { useDispatch, useSelector } from "react-redux";
import { RootState } from "../store/store";
import { useState } from "react";
import { Link } from "react-router-dom";
import { logout } from "../store/useSlice";
// import { RootState } from './store/store';
const Header = () => {
  const dispatch = useDispatch();
  const [menuDisplay, setMenuDisplay] = useState(false);
  const userId = useSelector((state: RootState) => state.user.user);
  //   console.log("iduser", userId);
  const handleLogout = () => {
    dispatch(logout());
    window.location.href = "/login";
  };
  return (
    <>
      <header className="shoes_header shoes_home_header">
        <div className="header_logo col_4 visible-lg d-none">
          <a href="/">
            <img
              src="../src/images/shoes_header_logo.png"
              className="img-fluid"
              alt="logo"
            />
          </a>
        </div>

        <div className="col_6 visible-lg d-none">
          <nav className="navbar-dark navbar-expand-lg navbar">
            <div className="navbar-collapse collapse" id="collapseNavbar">
              <ul className="navbar-nav">
                <li className="nav-item active">
                  <a className="nav-link text-uppercase" href="/">
                    Home{" "}
                  </a>
                </li>
                <MegaMenu/>
                <li className="nav-item dropdown mega-dropdown">
                  <a className="nav-link text-uppercase" href="/blog">
                    Blog
                  </a>
                  <span className="menu_arrow flaticon-down-arrow-1"></span>
                  <ul className="dropdown-menu mega-dropdown-menu">
                    <li className="w-100">
                      <a href="grid_blog_list_with_sidebar.html">
                        1 grid blog list with sidebar{" "}
                      </a>
                    </li>
                    <li className="w-100">
                      <a href="grids_blog_list.html">2 grids blog list</a>
                    </li>
                  </ul>
                </li>
                <li className="nav-item dropdown mega-dropdown">
                  <a
                    className="nav-link text-uppercase  dropdown-toggle"
                    href="/contacts"
                  >
                    Other Pages
                  </a>
                  <span className="menu_arrow flaticon-down-arrow-1"></span>
                  <ul className="dropdown-menu mega-dropdown-menu">
                    <li className="w-100">
                      <a href="earthyellow.html">Mega menu full screen</a>
                    </li>
                    <li className="w-100">
                      <a href="categories_menu.html">Categories menu</a>
                    </li>
                    <li className="w-100">
                      <a href="menu_with_sale_section.html">
                        Menu with sale section
                      </a>
                    </li>
                    <li className="w-100">
                      <a href="collection_list.html">Collection list</a>
                    </li>
                    <li className="w-100">
                      <a href="product_list_with_filter.html">
                        Product list with filter
                      </a>
                    </li>
                    <li className="w-100">
                      <a href="product_list_with_sidebar.html">
                        Product list with sidebar
                      </a>
                    </li>
                    <li className="w-100">
                      <a href="lookbook.html">Lookbook</a>
                    </li>
                    <li className="w-100">
                      <a href="coming_soon.html">Coming soon page</a>
                    </li>
                    <li className="w-100">
                      <a href="my_account.html">My account</a>
                    </li>
                    <li className="w-100">
                      <a href="404.html">404 page</a>
                    </li>
                  </ul>
                </li>
                <li className="nav-item">
                  <a
                    className="nav-link text-uppercase"
                    href="javascript:void(0);"
                  >
                    sale
                  </a>
                </li>
              </ul>
            </div>
          </nav>
        </div>
        <div className="col_3 visible-lg d-none">
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
                  {
                      userId?.role === "admin" && (
                              <Link to={"http://127.0.0.1:8000/admin/dashboards"} className='whitespace-nowrap hidden md:block hover:bg-slate-100 p-2' onClick={()=>setMenuDisplay(preve => !preve)}>Admin</Link>
                            )
                          }
                    <p className="p-2">Trang cá nhân</p>
                    <p>Đơn hàng</p>
                    <button className="btn btn-danger" onClick={handleLogout}>Logout</button>
                  </nav>
                </div>
              )}
            </li>
            <li className="wishlist_icon">
              <a href="/wishlist">
                <i className="flaticon-heart"></i>
              </a>
            </li>
            <li className="search_icon">
              <a href="javascript:void(0);">
                <i className="flaticon-magnifying-glass"></i>
              </a>
              <div className="search_form">
                <form>
                  <input
                    type="text"
                    placeholder="search"
                    className="text-capitalize"
                  />
                  <button type="submit" className="vertical_middle">
                    <i className="flaticon-magnifying-glass"></i>
                  </button>
                </form>
              </div>
            </li>
            <li className="cart_icon">
              <a href="/cart">
                <i className="flaticon-shopping-bag"></i>
                <span className="count text-white rounded-circle text-center">
                  0{" "}
                </span>
              </a>
            </li>
          </ul>
        </div>
      </header>
    </>
  );
};
export default Header;
