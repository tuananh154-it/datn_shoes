import { useState } from "react";

const MegaMenu = () => {
    const [isOpen, setIsOpen] = useState(false);
    console.log("MegaMenu isOpen:", isOpen); // Kiểm tra xem state có thay đổi không
    return (
        <li
            className="nav-item dropdown mega-dropdown relative"
            onMouseEnter={() => setIsOpen(true)}
            onMouseLeave={() => setIsOpen(false)}
        >
            <a className="nav-link text-uppercase dropdown-toggle hover:text-blue-500" href="/shop">
                Sản Phẩm
            </a>
            <span className="menu_arrow flaticon-down-arrow-1"></span>
            <ul className={`dropdown-menu megamenu_full_screen ${isOpen ? "block" : "hidden"}`}>
                <li className="w-100 text-capitalize">
                    <div className="container">
                        <div className="row">
                            <div className="col-lg-4">
                                <ul>
                                    <li className="title_h5 w-100">products</li>
                                    <li className="w-100"><a href="#">Men</a></li>
                                    <li className="w-100"><a href="#">Women</a></li>
                                    <li className="w-100"><a href="#">Accessories</a></li>
                                </ul>
                            </div>
                            <div className="col-lg-4">
                                <ul>
                                    <li className="title_h5 w-100">Category-1</li>
                                    <li className="w-100"><a href="#">Dresses</a></li>
                                    <li className="w-100"><a href="#">Skirts</a></li>
                                    <li className="w-100"><a href="#">Shirts</a></li>
                                    <li className="w-100"><a href="#">Jeans</a></li>
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
    );
};

export default MegaMenu;
