
const Shop = () => {
  return (
     <> 
    <div className="menu_overlay"></div>
     <div className="main_section">
     <section className="breadcrumb_section nav">
                <div className="container">
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb">
                            <li className="breadcrumb-item text-capitalize"><a href="earthyellow.html">Home</a> <i className="flaticon-arrows-4"></i></li>
                            <li className="breadcrumb-item active text-capitalize">shop</li>
                        </ol>
                    </nav>
                    <h1 className="title_h1 font-weight-normal text-capitalize">shop</h1>
                </div>
            </section>
    <section className="padding-top-text-60 padding-bottom-60 featured_section product_list_section product_list_filter_section ">
        <div className="container">
            <div className="row">
                <div className="col-lg-3">
                    <div className="collection_sidebar">
                        <div className="sidebar_title padding-bottom-60 hidden-lg">
                            <h3 className="title_h3">Filter</h3>
                            <a className="filter_colse" href="javascript:void(0);"><i className="flaticon-close"></i></a>
                        </div>
                        <div className="filter_content mCustomScrollbar">
                            <div className="category_list">
                                <div className="category_list_title">
                                    <h5 className="title_h5">Shopping by</h5>
                                    <span className="category_close_icon flaticon-down-arrow float-right"></span>
                                </div>
                                <div className="layer-filter shopping_by_select">
                                    <div className="select_category">
                                        <div className="fill_type">
                                            <p className="fill_name">Women</p>
                                            <a href="javascript:void(0);" className="clear text-right text-capitalize" >Clear</a>
                                        </div>
                                        <div className="fill_value">
                                            <p>Tops <i className="flaticon-close"></i></p>
                                            <p>Outerwear <i className="flaticon-close"></i></p>
                                            <p>Bottoms <i className="flaticon-close"></i></p>
                                            <p>Activewear <i className="flaticon-close"></i></p>
                                            <a href="javascript:void(0);"  className="flaticon-close"></a>
                                        </div>
                                    </div>
                                    <div className="select_category">
                                        <div className="fill_type">
                                            <p className="fill_name">Price</p>
                                            <a href="javascript:void(0);" className="clear text-right text-capitalize" >Clear</a>
                                        </div>
                                        <div className="fill_value">
                                            <p>$0 - $100</p>
                                            <a href="javascript:void(0);"  className="flaticon-close"></a>
                                        </div>
                                    </div>
                                    <div className="select_category">
                                        <div className="fill_type">
                                            <p className="fill_name">Size</p>
                                            <a href="javascript:void(0);" className="clear text-right text-capitalize" >Clear</a>
                                        </div>
                                        <div className="fill_value">
                                            <p className="text-uppercase">xs <i className="flaticon-close"></i></p>
                                            <p className="text-uppercase">s <i className="flaticon-close"></i></p>
                                            <p className="text-uppercase">m <i className="flaticon-close"></i></p>
                                            <p className="text-uppercase">l <i className="flaticon-close"></i></p>
                                            <p className="text-uppercase">xl <i className="flaticon-close"></i></p>
                                            <a href="javascript:void(0);"  className="flaticon-close"></a>
                                        </div>

                                    </div>
                                    <div className="select_category">
                                        <div className="fill_type">
                                            <p className="fill_name">Color</p>
                                            <a href="javascript:void(0);" className="clear text-right text-capitalize" >Clear</a>
                                        </div>
                                        <div className="fill_value">
                                            <p><span className="color1"></span> <i className="flaticon-close"></i></p>
                                            <p><span className="color2"></span> <i className="flaticon-close"></i></p>
                                            <p><span className="color3"></span> <i className="flaticon-close"></i></p>
                                            <a href="javascript:void(0);"  className="flaticon-close"></a>
                                        </div>
                                        <a href="javascript:void(0);" className="claerall text-uppercase" >Clear All</a>
                                    </div>
                                </div>
                            </div>
                            <div className="category_list">
                                <div className="category_list_title">
                                    <h5 className="title_h5">Availability</h5>
                                    <span className="category_close_icon flaticon-down-arrow float-right"></span>
                                </div>
                                <div className="layer-filter">
                                    <ul>
                                        <li><a href="javascript:void(0);"> On Sale</a></li>
                                        <li><a href="javascript:void(0);">In Stock</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div className="category_list">
                                <div className="category_list_title">
                                    <h5 className="title_h5">Women</h5>
                                    <span className="category_close_icon flaticon-down-arrow float-right"></span>
                                </div>
                                <div className="layer-filter">
                                    <div className="search_tag">
                                        <input type="text" placeholder="search" className="text-capitalize"/>
                                        <button type="submit" className="flaticon-magnifying-glass"></button>
                                    </div>
                                    <ul className="women">
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box1" id="box1" checked={true}/>
                                                <label htmlFor="box1">Tops</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box2" id="box2" checked={true}/>
                                                <label htmlFor="box2">Outerwear</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box3" id="box3" checked={true}/>
                                                <label htmlFor="box3">Bottoms</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box4" id="box4" checked={true}/>
                                                <label htmlFor="box4">Activewear</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box5" id="box5"/>
                                                <label htmlFor="box5">Sleepwear</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box6" id="box6"/>
                                                <label htmlFor="box6">Swimwear</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box7" id="box7"/>
                                                <label htmlFor="box7">Shoes & Accessories</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box8" id="box8"/>
                                                <label htmlFor="box8">Sale</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div className="loadMore text-uppercase">4 more</div>
                                </div>
                            </div>
                            <div className="category_list">
                                <div className="category_list_title">
                                    <h5 className="title_h5">Men</h5>
                                    <span className="category_close_icon flaticon-down-arrow float-right"></span>
                                </div>
                                <div className="layer-filter">
                                    <div className="search_tag">
                                        <input type="text" placeholder="search" className="text-capitalize"/>
                                        <button type="submit" className="flaticon-magnifying-glass"></button>
                                    </div>
                                    <ul className="men">
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box9" id="box9" checked={true}/>
                                                <label htmlFor="box9">T-Shirts</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box10" id="box10" checked={true}/>
                                                <label htmlFor="box10">Outerwear</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box11" id="box11" checked={true}/>
                                                <label htmlFor="box11">Basics</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box12" id="box12" checked={true}/>
                                                <label htmlFor="box12">Activewear</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box13" id="box13"/>
                                                <label htmlFor="box13">Sleepwear</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box14" id="box14"/>
                                                <label htmlFor="box14">Swimwear</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box15" id="box15"/>
                                                <label htmlFor="box15">Shoes & Accessories</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box16" id="box16"/>
                                                <label htmlFor="box16">Sale</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div className="loadMore text-uppercase">4 more</div>
                                </div>
                            </div>
                            <div className="category_list">
                                <div className="category_list_title">
                                    <h5 className="title_h5">Accessories</h5>
                                    <span className="category_close_icon flaticon-down-arrow float-right"></span>
                                </div>
                                <div className="layer-filter">
                                    <ul>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box17" id="box17"/>
                                                <label htmlFor="box17">Glasses</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box18" id="box18"/>
                                                <label htmlFor="box18">Handbags</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box19" id="box19" />
                                                <label htmlFor="box19">Apparel</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox">
                                                <input type="checkbox" name="box20" id="box20" />
                                                <label htmlFor="box20">Shoes</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div className="category_list">
                                <div className="category_list_title">
                                    <h5 className="title_h5">Our Shops</h5>
                                    <span className="category_close_icon flaticon-down-arrow float-right"></span>
                                </div>
                                <div className="layer-filter">
                                    <ul>
                                        <li><a href="javascript:void(0);">New Arrivals</a></li>
                                        <li><a href="javascript:void(0);">Cozy Shop</a></li>
                                        <li><a href="javascript:void(0);">All That Sparkles</a></li>
                                        <li><a href="javascript:void(0);">Online Exclusives </a></li>
                                        <li><a href="javascript:void(0);">Outfit Shop</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div className="category_list">
                                <div className="category_list_title">
                                    <h5 className="title_h5">Collections</h5>
                                    <span className="category_close_icon flaticon-down-arrow float-right"></span>
                                </div>
                                <div className="layer-filter">
                                    <ul>
                                        <li><a href="javascript:void(0);">Ice Dancer NEW</a></li>
                                        <li><a href="javascript:void(0);">North Pole Party</a></li>
                                        <li><a href="javascript:void(0);">Holiday Dressed Up</a></li>
                                        <li><a href="javascript:void(0);">Woodland Weekend </a></li>
                                        <li><a href="javascript:void(0);">Ready, Jet, Go</a></li>
                                        <li><a href="javascript:void(0);">Mix 'n' Match Playwear</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div className="category_list">
                                <div className="category_list_title">
                                    <h5 className="title_h5">Price</h5>
                                    <span className="category_close_icon flaticon-down-arrow float-right"></span>
                                </div>
                                <div className="layer-filter">
                                    <div className="range_slider">
                                        <div id="slider"></div>
                                    </div>
                                </div>
                            </div>
                            <div className="category_list">
                                <div className="category_list_title">
                                    <h5 className="title_h5">Size</h5>
                                    <span className="category_close_icon flaticon-down-arrow float-right"></span>
                                </div>
                                <div className="layer-filter">
                                    <div className="search_tag">
                                        <input type="text" placeholder="search" className="text-capitalize"/>
                                        <button type="submit" className="flaticon-magnifying-glass"></button>
                                    </div>
                                    <ul className="size">
                                        <li>
                                            <div className="checkbox text-uppercase">
                                                <input type="checkbox" name="box21" id="box21" checked={true}/>
                                                <label htmlFor="box21">xl</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox text-uppercase">
                                                <input type="checkbox" name="box22" id="box22" checked={true}/>
                                                <label htmlFor="box22">s</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox text-uppercase">
                                                <input type="checkbox" name="box23" id="box23" checked={true}/>
                                                <label htmlFor="box23">m</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox text-uppercase">
                                                <input type="checkbox" name="box24" id="box24" checked={true}/>
                                                <label htmlFor="box24">l</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox text-uppercase">
                                                <input type="checkbox" name="box25" id="box25"/>
                                                <label htmlFor="box25">xl</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox text-uppercase">
                                                <input type="checkbox" name="box26" id="box26"/>
                                                <label htmlFor="box26">xxl</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox text-uppercase">
                                                <input type="checkbox" name="box27" id="box27"/>
                                                <label htmlFor="box27">xxxl</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox text-uppercase">
                                                <input type="checkbox" name="box28" id="box28"/>
                                                <label htmlFor="box28">uk 6</label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div className="loadMore text-uppercase">4 more</div>
                                </div>
                            </div>

                            <div className="category_list color_box">
                                <div className="category_list_title">
                                    <h5 className="title_h5">Color</h5>
                                    <span className="category_close_icon flaticon-down-arrow float-right"></span>
                                </div>
                                <div className="layer-filter">
                                    <div className="search_tag">
                                        <input type="text" placeholder="search" className="text-capitalize"/>
                                        <button type="submit" className="flaticon-magnifying-glass"></button>
                                    </div>
                                    <ul className="color">
                                        <li>
                                            <div className="checkbox text-uppercase text-center">
                                                <input type="checkbox" name="box29" id="box29" checked={true}/>
                                                <label htmlFor="box29" className="color1"></label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox text-uppercase text-center">
                                                <input type="checkbox" name="box30" id="box30" checked={true}/>
                                                <label htmlFor="box30" className="color2"></label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox text-uppercase text-center">
                                                <input type="checkbox" name="box31" id="box31" checked={true}/>
                                                <label htmlFor="box31" className="color3"></label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox text-uppercase text-center">
                                                <input type="checkbox" name="box32" id="box32"/>
                                                <label htmlFor="box32" className="color4"></label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox text-uppercase text-center">
                                                <input type="checkbox" name="box33" id="box33"/>
                                                <label htmlFor="box33" className="color5"></label>
                                            </div>
                                        </li>
                                        <li>
                                            <div className="checkbox text-uppercase text-center">
                                                <input type="checkbox" name="box34" id="box34"/>
                                                <label htmlFor="box34" className="color6"></label>
                                            </div>
                                        </li>
                                    </ul>
                                    <div className="loadMore text-uppercase">2 more</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-lg-9">
                    <div className="collection-sorting-row">
                        <div className="filter_menu hidden-lg ">
                            <a className="title_h5 text-capitalize">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="16" viewBox="0 0 21 16">
                                <path id="Filter_Icon" data-name="Filter Icon" fill="#f74f2e" className="cls-1" d="M0,13H5v2H0V13Zm5-1h6v4H5V12Zm6,1H21v2H11V13Zm7-6h3V9H18V7ZM12,6h6v4H12V6ZM0,7H12V9H0V7ZM0,1H3V3H0V1ZM3,0H9V4H3V0ZM9,1H21V3H9V1Z"/>
                                </svg>                          filter
                            </a>
                        </div>
                        <div className="short_by">
                            <form>
                                <div className="htmlForm-group">
                                    <label htmlFor="short_by" className="title_h5">Sort By :</label>
                                    <select className="htmlForm-control" id="short_by" name="short_by"><option>Featured Proucts</option></select>
                                </div>
                            </form>
                        </div>
                        <div className="product_grid visible-lg d-none">
                            <ul>
                                <li className="grid_2 grid-list" data-column="column2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="30" viewBox="0 0 20 30">                                               
                                    <path id="Rectangle_21_copy_20" data-name="Rectangle 21 copy 20" fill="#aaa" className="cls-1" d="M12.008,11.006h7.986v8H12.008v-8Zm-12,0H7.994v8H0.008v-8Zm0,11H7.994v8H0.008v-8Zm12,0h7.986v8H12.008v-8Zm-12-22H8v8H0.009v-8ZM12,0.006h7.991v8H12v-8Z"/>
                                    </svg>
                                </li>
                                <li className="grid_3 active grid-list" data-column="column3 product">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="30" viewBox="0 0 32 30">                                                
                                    <path id="Rectangle_21_copy_22" data-name="Rectangle 21 copy 22" fill="#aaa" className="cls-1" d="M12.008,11.006h7.986v8H12.008v-8Zm-12,0H7.994v8H0.008v-8Zm0,11H7.994v8H0.008v-8Zm12,0h7.986v8H12.008v-8Zm-12-22H8v8H0.009v-8ZM12,0.006h7.991v8H12v-8Zm12,11h7.986v8H24.008v-8Zm0,11h7.986v8H24.008v-8Zm0-22h7.991v8H24v-8Z"/>
                                    </svg>
                                </li>
                                <li className="grid_4 grid-list" data-column="column4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="41" height="30" viewBox="0 0 41 30">
                                    <path id="Rectangle_21_copy_29" data-name="Rectangle 21 copy 29" fill="#aaa" className="cls-1" d="M11.008,11.006h7.986v8H11.008v-8Zm-11,0H7.994v8H0.008v-8Zm0,11H7.994v8H0.008v-8Zm11,0h7.986v8H11.008v-8Zm-11-22H8v8H0.008v-8ZM11,0.006h7.991v8H11v-8Zm11,11h7.986v8H22.008v-8Zm0,11h7.986v8H22.008v-8Zm0-22h7.991v8H22v-8Zm11,11h7.986v8H33.008v-8Zm0,11h7.986v8H33.008v-8Zm0-22h7.991v8H33v-8Z"/>
                                    </svg>
                                </li>
                            </ul>
                        </div>
                        <div className="short_by show_product text-right">
                            <form>
                                <div className="htmlForm-group">
                                    <label htmlFor="show"  className="title_h5">show :</label>
                                    <select className="htmlForm-control" id="show" name="show"><option>24</option></select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <ul className=" category-products  wow fadeIn row">
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInLeft animated" data-wow-duration="1300ms" >
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product1.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Silk Dress</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$59.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio1"/>
                                            <label htmlFor="radio1">xs</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio2"/>
                                            <label htmlFor="radio2">s</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio3"/>
                                            <label htmlFor="radio3">m</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio4"/>
                                            <label htmlFor="radio4">l</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio5"/>
                                            <label htmlFor="radio5">xl</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInLeft animated" data-wow-duration="1300ms" data-wow-delay="0.2s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product2.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Premium Party Suit</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$79.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio6"/>
                                            <label htmlFor="radio6">s</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio7"/>
                                            <label htmlFor="radio7">m</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio8"/>
                                            <label htmlFor="radio8">l</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInLeft animated" data-wow-duration="1300ms" data-wow-delay="0.4s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product3.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase  new-label ">new<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Silk Party Dress</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$99.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio9"/>
                                            <label htmlFor="radio9">l</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInRight animated" data-wow-duration="1300ms">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product5.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase  new-label ">new<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Man T-Shirt</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$19.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio11"/>
                                            <label htmlFor="radio11">m</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio12"/>
                                            <label htmlFor="radio12">l</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInRight animated" data-wow-duration="1300ms" data-wow-delay="0.2s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product6.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <span className="text-uppercase background-btn sold_out_btn">Sold Out</span>
                                    </div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Flower Floral Dupioni Dress</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$79.95</span></p>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInRight animated" data-wow-duration="1300ms" data-wow-delay="0.4s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product7.png" alt="f_product" className="img-fluid"/>
                                    <ul className="product-date">
                                        <li className="day background-btn">
                                            <span className="no">12</span>
                                            <span className="text text-capitalize">days</span>
                                        </li>
                                    </ul>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase  sale-label ">sale<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>

                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Check Shirt</p></a>
                                    <p className="featured_price title_h5  text-center"><span className="compare_price">$39.95</span><span>$29.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio13"/>
                                            <label htmlFor="radio13">m</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInLeft animated" data-wow-duration="1300ms">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product4.png" alt="f_product" className="img-fluid"/>
                                    <ul className="product-date">
                                        <li className="day background-btn">
                                            <span className="no">12</span>
                                            <span className="text text-capitalize">days</span>
                                        </li>
                                        <li className="hours background-btn">
                                            <span className="no">07</span>
                                            <span className="text text-capitalize">Hrs</span>
                                        </li>
                                        <li className="min background-btn">
                                            <span className="no">30</span>
                                            <span className="text text-capitalize">Mins</span>
                                        </li>
                                        <li className="second background-btn">
                                            <span className="no">15</span>
                                            <span className="text text-capitalize">Secs</span>
                                        </li>
                                    </ul>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase sale-label ">sale<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>

                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Jeans Pant</p></a>
                                    <p className="featured_price title_h5  text-center"><span className="compare_price">$59.95</span><span>$39.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio14"/>
                                            <label htmlFor="radio14">M</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInLeft animated" data-wow-duration="1300ms" data-wow-delay="0.2s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product8.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Black Dotted Dress</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$29.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio15"/>
                                            <label htmlFor="radio15">l</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInLeft animated" data-wow-duration="1300ms" data-wow-delay="0.4s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product1.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Silk Dress</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$59.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio16"/>
                                            <label htmlFor="radio16">xs</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio17"/>
                                            <label htmlFor="radio17">s</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio18"/>
                                            <label htmlFor="radio18">m</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio19"/>
                                            <label htmlFor="radio19">l</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio20"/>
                                            <label htmlFor="radio20">xl</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInRight animated" data-wow-duration="1300ms">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product5.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase  new-label ">new<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Man T-Shirt</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$19.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio21"/>
                                            <label htmlFor="radio21">m</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio22"/>
                                            <label htmlFor="radio22">l</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInRight animated" data-wow-duration="1300ms" data-wow-delay="0.2s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product3.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase  new-label ">new<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Silk Party Dress</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$99.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio23"/>
                                            <label htmlFor="radio23">l</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInRight animated" data-wow-duration="1300ms" data-wow-delay="0.4s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product7.png" alt="f_product" className="img-fluid"/>
                                    <ul className="product-date">
                                        <li className="day background-btn">
                                            <span className="no">12</span>
                                            <span className="text text-capitalize">days</span>
                                        </li>
                                    </ul>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase  sale-label ">sale<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>

                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Check Shirt</p></a>
                                    <p className="featured_price title_h5  text-center"><span className="compare_price">$39.95</span><span>$29.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio24"/>
                                            <label htmlFor="radio24">m</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInLeft animated" data-wow-duration="1300ms">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product1.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Silk Dress</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$59.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio25"/>
                                            <label htmlFor="radio25">xs</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio26"/>
                                            <label htmlFor="radio26">s</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio27"/>
                                            <label htmlFor="radio27">m</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio28"/>
                                            <label htmlFor="radio28">l</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio29"/>
                                            <label htmlFor="radio29">xl</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInLeft animated" data-wow-duration="1300ms" data-wow-delay="0.2s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product2.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Premium Party Suit</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$79.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio30"/>
                                            <label htmlFor="radio30">s</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio31"/>
                                            <label htmlFor="radio31">m</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio32"/>
                                            <label htmlFor="radio32">l</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInLeft animated" data-wow-duration="1300ms" data-wow-delay="0.4s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product3.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase  new-label ">new<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Silk Party Dress</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$99.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio33"/>
                                            <label htmlFor="radio33">l</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInRight animated" data-wow-duration="1300ms">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product5.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase  new-label ">new<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Man T-Shirt</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$19.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio34"/>
                                            <label htmlFor="radio34">m</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio35"/>
                                            <label htmlFor="radio35">l</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInRight animated" data-wow-duration="1300ms" data-wow-delay="0.2s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product6.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <span className="text-uppercase background-btn sold_out_btn">Sold Out</span>
                                    </div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Flower Floral Dupioni Dress</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$79.95</span></p>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInRight animated" data-wow-duration="1300ms" data-wow-delay="0.4s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product7.png" alt="f_product" className="img-fluid"/>
                                    <ul className="product-date">
                                        <li className="day background-btn">
                                            <span className="no">12</span>
                                            <span className="text text-capitalize">days</span>
                                        </li>
                                    </ul>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase  sale-label ">sale<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>

                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Check Shirt</p></a>
                                    <p className="featured_price title_h5  text-center"><span className="compare_price">$39.95</span><span>$29.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio36"/>
                                            <label htmlFor="radio36">m</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInLeft animated" data-wow-duration="1300ms">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product4.png" alt="f_product" className="img-fluid"/>
                                    <ul className="product-date">
                                        <li className="day background-btn">
                                            <span className="no">12</span>
                                            <span className="text text-capitalize">days</span>
                                        </li>
                                    </ul>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase sale-label ">sale<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>

                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Jeans Pant</p></a>
                                    <p className="featured_price title_h5  text-center">
                                        <span className="compare_price">$59.95</span><span>$39.95</span>
                                    </p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio10"/>
                                            <label htmlFor="radio10">M</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInLeft animated" data-wow-duration="1300ms" data-wow-delay="0.2s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product8.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Black Dotted Dress</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$29.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio37"/>
                                            <label htmlFor="radio37">l</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInLeft animated" data-wow-duration="1300ms" data-wow-delay="0.4s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product1.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Silk Dress</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$59.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio38"/>
                                            <label htmlFor="radio38">xs</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio39"/>
                                            <label htmlFor="radio39">s</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio40"/>
                                            <label htmlFor="radio40">m</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio41"/>
                                            <label htmlFor="radio41">l</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio42"/>
                                            <label htmlFor="radio42">xl</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInRight animated" data-wow-duration="1300ms">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product5.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase  new-label ">new<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Man T-Shirt</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$19.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio43"/>
                                            <label htmlFor="radio43">m</label>
                                        </div>
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio44"/>
                                            <label htmlFor="radio44">l</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product  wow fadeInRight animated" data-wow-duration="1300ms" data-wow-delay="0.2s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product3.png" alt="f_product" className="img-fluid"/>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase  new-label ">new<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>
                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Silk Party Dress</p></a>
                                    <p className="featured_price title_h5  text-center"><span>$99.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio46"/>
                                            <label htmlFor="radio46">l</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li className="col-lg-3 col-md-4 col-6 column3 product product  wow fadeInRight animated" data-wow-duration="1300ms" data-wow-delay="0.4s">
                            <div className="featured_content">
                                <div className="featured_img_content">
                                    <img src="src/images/f_product7.png" alt="f_product" className="img-fluid"/>
                                    <ul className="product-date">
                                        <li className="day background-btn">
                                            <span className="no">12</span>
                                            <span className="text text-capitalize">days</span>
                                        </li>
                                    </ul>
                                    <div className="featured_btn vertical_middle">
                                        <a href="cart.html" className="text-uppercase background-btn add_to_bag_btn">Add To Bag</a>
                                        <a href="javascript:void(0);" className="text-uppercase border-btn popup_btn" data-modal="#modalone">Quick View</a>
                                    </div>
                                    <div className="product-label  text-uppercase  sale-label ">sale<span className="diamond_shape"></span></div>
                                    <a href="javascript:void(0);" className="heart  rounded-circle text-center "><i className="flaticon-heart vertical_middle"></i></a>

                                </div>
                                <div className="featured_detail_content">
                                    <a href="product_list_detail.html"><p className="featured_title  text-capitalize  text-center">Check Shirt</p></a>
                                    <p className="featured_price title_h5  text-center"><span className="compare_price">$39.95</span><span>$29.95</span></p>
                                    <div className="featured_variyant  text-center">
                                        <div className="radio text-uppercase  text-center">
                                            <input type="radio" name="size" id="radio47"/>
                                            <label htmlFor="radio47">m</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div className="align-self-center">
                        <ul className="pagination text-center justify-content-center">
                            <li className="page-item"><a className="page-link" href="javascript:void(0);"><i className="flaticon-arrows-1"></i></a></li>
                            <li className="page-item"><a className="page-link" href="javascript:void(0);">1</a></li>
                            <li className="page-item active"><a className="page-link" href="javascript:void(0);">2</a></li>
                            <li className="page-item"><a className="page-link" href="javascript:void(0);">3</a></li>
                            <li className="page-item"><a className="page-link" href="javascript:void(0);"><i className="flaticon-arrows"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
     </>    
  )
}

export default Shop
