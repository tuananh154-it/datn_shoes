import { useState } from "react";
import { Link } from "react-router-dom";

const MegaMenu = () => {
    const [isOpen, setIsOpen] = useState(false);
    console.log("MegaMenu isOpen:", isOpen); // Kiểm tra xem state có thay đổi không
    return (
        <li
            className="nav-item dropdown mega-dropdown relative"
            onMouseEnter={() => setIsOpen(true)}
            onMouseLeave={() => setIsOpen(false)}
        >
            <Link to="/shop" className="nav-link text-uppercase dropdown-toggle hover:text-blue-500">
                Cửa hàng
            </Link>
            <span className="menu_arrow flaticon-down-arrow-1"></span>
            <ul className={`dropdown-menu megamenu_full_screen ${isOpen ? "block" : "hidden"}`}>
                <li className="w-100 text-capitalize">
                    <div className="container">
                        <div className="row">
                            <div className="col-lg-4">
                                <ul>
                                    <li className="title_h5 w-100">Sản phẩm</li>
                                    <li className="w-100"><a href="#">Nam</a></li>
                                    <li className="w-100"><a href="#">Nữ</a></li>
                                    <li className="w-100"><a href="#">Phụ kiện</a></li>
                                </ul>
                            </div>
                            <div className="col-lg-4">
                                <ul>
                                    <li className="title_h5 w-100">Danh mục</li>
                                    <li className="w-100"><a href="#">Giày chạy bộ</a></li>
                                    <li className="w-100"><a href="#">Giày leo núi</a></li>
                                    <li className="w-100"><a href="#">Giày thời trang</a></li>
                                    <li className="w-100"><a href="#">Giày da</a></li>
                                </ul>
                            </div>
                            <div className="col-lg-4">
                                <ul>
                                    <li className="title_h5 w-100">Thương hiệu</li>
                                    <li className="w-100"><a href="javascript:void(0);">Adidas</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">Nike</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">Puma</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">Vanz</a></li>
                                    <li className="w-100"><a href="javascript:void(0);">Balenciaga</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </li>
    );
};

export default MegaMenu;


