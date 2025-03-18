
const Header = () => {
  return (
  <>
    <header className="shoes_header shoes_home_header">
    <div className="header_logo col_4 visible-lg d-none">
        <a href="/"><img src="src/images/shoes_header_logo.png" className="img-fluid" alt="logo" /></a>
    </div>
  
    <div className="col_6 visible-lg d-none">
        <nav className="navbar-dark navbar-expand-lg navbar">
            <div className="navbar-collapse collapse" id="collapseNavbar">
                <ul className="navbar-nav">
                    <li className="nav-item active">
                        <a className="nav-link text-uppercase" href="/">Home </a>
                    </li>
                    <li className="nav-item dropdown mega-dropdown">
                        <a className="nav-link text-uppercase dropdown-toggle" href="/shop">Shop</a>
                        <span className="menu_arrow flaticon-down-arrow-1"></span>
                        <ul className="dropdown-menu megamenu_full_screen">
                            <li className="w-100 text-capitalize">
                                <div className="container">
                                    <div className="row">
                                        <div className="col-lg-4">
                                            <ul>
                                                <li className="title_h5 w-100">products</li>
                                                <li className="w-100"><a href="javascript:void(0);">Men</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">Women</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">Accessories</a></li>
                                            </ul>
                                        </div>
                                        <div className="col-lg-4">
                                            <ul>
                                                <li className="title_h5 w-100">Category-1</li>
                                                <li className="w-100"><a href="javascript:void(0);">Dresses</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">Skirts</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">shirts</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">jeans</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">sweaters</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">sweatshirts</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">pants short</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">cords</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">tracks & joggers</a></li>
                                            </ul>
                                        </div>
                                        <div className="col-lg-4">
                                            <ul>
                                                <li className="title_h5 w-100">category-2</li>
                                                <li className="w-100"><a href="javascript:void(0);">winter wear</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">summer specials</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">inner wears</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">tops</a></li>
                                                <li className="w-100"><a href="javascript:void(0);">jackets</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li className="nav-item dropdown mega-dropdown">
                        <a className="nav-link text-uppercase" href="/blog">Blog</a>
                        <span className="menu_arrow flaticon-down-arrow-1"></span>
                        <ul className="dropdown-menu mega-dropdown-menu">                                    
                            <li className="w-100"><a href="grid_blog_list_with_sidebar.html">1 grid blog list with sidebar </a></li>
                            <li className="w-100"><a href="grids_blog_list.html">2 grids blog list</a></li>                                     
                        </ul>
                    </li>
                    <li className="nav-item dropdown mega-dropdown">
                        <a className="nav-link text-uppercase  dropdown-toggle" href="/contacts">Other Pages</a>
                        <span className="menu_arrow flaticon-down-arrow-1"></span>
                        <ul className="dropdown-menu mega-dropdown-menu">
                            <li className="w-100"><a href="earthyellow.html">Mega menu full screen</a></li>
                            <li className="w-100"><a href="categories_menu.html">Categories menu</a></li>
                            <li className="w-100"><a href="menu_with_sale_section.html">Menu with sale section</a></li>
                            <li className="w-100"><a href="collection_list.html">Collection list</a></li>
                            <li className="w-100"><a href="product_list_with_filter.html">Product list with filter</a></li>
                            <li className="w-100"><a href="product_list_with_sidebar.html">Product list with sidebar</a></li>
                            <li className="w-100"><a href="lookbook.html">Lookbook</a></li>                                    
                            <li className="w-100"><a href="coming_soon.html">Coming soon page</a></li>
                            <li className="w-100"><a href="my_account.html">My account</a></li>
                            <li className="w-100"><a href="404.html">404 page</a></li>
                        </ul>
                    </li>
                    <li className="nav-item">
                        <a className="nav-link text-uppercase" href="javascript:void(0);">sale</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <div className="col_3 visible-lg d-none">
        <ul className="social_icons float-md-right">
            <li className="login_icon">
                <a href="/login"><i className="flaticon-social"></i></a>
            </li>
            <li className="wishlist_icon">
                <a href="/wishlist"><i className="flaticon-heart"></i></a>
            </li>
            <li className="search_icon">
                <a href="javascript:void(0);"><i className="flaticon-magnifying-glass"></i></a>
                <div className="search_form">
                    <form>
                        <input type="text"  placeholder="search" className="text-capitalize" />
                        <button type="submit" className="vertical_middle"><i className="flaticon-magnifying-glass"></i></button>
                    </form>
                </div>
            </li>
            <li className="cart_icon">
                <a href="/cart">
                    <i className="flaticon-shopping-bag"></i>
                    <span className="count text-white rounded-circle text-center">0 </span>
                </a>
            </li>
        </ul>
    </div>
    <div className="header_currency col_4 text-right visible-lg d-none">
        <div className="select_language">
            <select className="selectpicker" data-width="fit">
                <option data-content='<img src="src/images/eng_flag.png" alt="eng_flag" className="img-fluid"/> Eng'>Eng</option>
                <option  data-content='<img src="src/images/spain_flag.png" alt="spain_flag" className="img-fluid"/> spa'>spa</option>
            </select>
        </div>
        <div className="currencies_select">
            <select className="selectpicker" data-width="fit">
                <option data-content='usd'> Usd</option>
                <option  data-content='eur'>Eur</option>
            </select>
        </div>
    </div>
    <div className="header_mobile hidden-lg d-block">
        <div className="header_currency text-right">
            <div className="select_language">
                <select className="selectpicker" data-width="fit">
                    <option data-content='<img src="src/images/eng_flag.png" alt="eng_flag" className="img-fluid" /> Eng'>Eng</option>
                    <option  data-content='<img src="src/images/spain_flag.png" alt="spain_flag" className="img-fluid"/> spa'>spa</option>
                </select>
            </div>
            <div className="currencies_select">
                <select className="selectpicker" data-width="fit">
                    <option data-content='usd'> Usd</option>
                    <option  data-content='eur'>Eur</option>
                </select>
            </div>
        </div>
        <div className="social_icons_content">
            <ul className="social_icons float-md-right">
                <li className="login_icon">
                    <a href="login.html"><i className="flaticon-social"></i></a>
                </li>
                <li className="wishlist_icon">
                    <a href="wishlist.html"><i className="flaticon-heart"></i></a>
                </li>
                <li className="search_icon">
                    <a href="javascript:void(0);"><i className="flaticon-magnifying-glass"></i></a>
                    <div className="search_form">
                        <form>
                            <input type="text"  placeholder="search" className="text-capitalize" />
                            <button type="submit" className="vertical_middle"><i className="flaticon-magnifying-glass"></i></button>
                        </form>
                    </div>
                </li>
                <li className="cart_icon">
                    <a href="/cart">
                        <i className="flaticon-shopping-bag"></i>
                        <span className="count text-white rounded-circle text-center">0 </span>
                    </a>
                </li>
            </ul>
        </div>
        <div className="header_logo col_4">
            <a href="shoes.html"><img src="src/images/shoes_header_logo.png" className="img-fluid" alt="logo" /></a>
        </div>
        <nav className="navbar-dark navbar-expand-lg navbar">
            <button type="button" data-toggle="collapse" className="navbar-toggler">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="26" viewBox="0 0 28 26">      
                <path id="menu" className="cls-1" fill="#333" d="M0,0H28V2H0V0ZM0,8H20v2H0V8Zm0,8H28v2H0V16Zm0,8H17v2H0V24Z"></path>       
                </svg>
            </button>
            <div className="navbar-collapse collapse ">
                <div className="close_icon"><a className="menu_colse" href="javascript:void(0);"><i className="flaticon-close"></i></a></div>
                <ul className="navbar-nav">
                    <li className="nav-item active">
                        <a className="nav-link text-uppercase" href="shoes.html">Home </a>
                    </li>
                    <li className="nav-item dropdown mega-dropdown">
                        <a className="nav-link text-uppercase dropdown-toggle" href="product_list_with_sidebar.html">Shop</a>
                        <span className="menu_arrow flaticon-down-arrow-1"></span>
                        <ul className="dropdown-menu megamenu_mobile">
                            <li className="w-100 nav-item">
                                <a href="javascript:void(0);" className="nav-link">products</a>
                                <span className="menu_arrow flaticon-down-arrow-1"></span>
                                <ul>
                                    <li className="w-100"><a href="javascript:void(0);">Men</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">Women</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">Accessories</a></li>
                                </ul>
                            </li>
                            <li className="w-100 nav-item">
                                <a href="javascript:void(0);" className="nav-link">Category-1</a>
                                <span className="menu_arrow flaticon-down-arrow-1"></span>
                                <ul>
                                    <li className="w-100"><a href="javascript:void(0);">Dresses</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">Skirts</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">shirts</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">jeans</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">sweaters</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">sweatshirts</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">pants short</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">cords</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">tracks & joggers</a></li>
                                </ul>
                            </li>
                            <li className="w-100 nav-item">
                                <a href="javascript:void(0);" className="nav-link">category-2</a>
                                <span className="menu_arrow flaticon-down-arrow-1"></span>
                                <ul>
                                    <li className="w-100"><a href="javascript:void(0);">winter wear</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">summer specials</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">inner wears</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">tops</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">jackets</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li className="nav-item dropdown mega-dropdown">
                        <a className="nav-link text-uppercase" href="grids_blog_list.html">Blog</a>
                        <span className="menu_arrow flaticon-down-arrow-1"></span>
                        <ul className="dropdown-menu mega-dropdown-menu">                                    
                            <li className="w-100"><a href="grid_blog_list_with_sidebar.html">1 grid blog list with sidebar </a></li>
                            <li className="w-100"><a href="grids_blog_list.html">2 grids blog list</a></li>                                     
                        </ul>
                    </li>
                    <li className="nav-item dropdown mega-dropdown">
                        <a className="nav-link text-uppercase  dropdown-toggle" href="javascript:void(0);">Other Pages</a>
                        <span className="menu_arrow flaticon-down-arrow-1"></span>
                        <ul className="dropdown-menu mega-dropdown-menu">
                            <li className="w-100"><a href="earthyellow.html">Mega menu full screen</a></li>
                            <li className="w-100"><a href="categories_menu.html">Categories menu</a></li>
                            <li className="w-100"><a href="menu_with_sale_section.html">Menu with sale section</a></li>
                            <li className="w-100"><a href="collection_list.html">Collection list</a></li>
                            <li className="w-100"><a href="product_list_with_filter.html">Product list with filter</a></li>
                            <li className="w-100"><a href="product_list_with_sidebar.html">Product list with sidebar</a></li>
                            <li className="w-100"><a href="lookbook.html">Lookbook</a></li>                                   
                            <li className="w-100"><a href="coming_soon.html">Coming soon page</a></li>
                            <li className="w-100"><a href="my_account.html">My account</a></li>
                            <li className="w-100"><a href="404.html">404 page</a></li>
                        </ul>
                    </li>
                    <li className="nav-item">
                        <a className="nav-link text-uppercase" href="javascript:void(0);">sale</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
   </header>
  </>
  )
}

export default Header
