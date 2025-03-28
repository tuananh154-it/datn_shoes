
import { User, Package, Home, CreditCard, Settings } from "lucide-react";
import { useState } from "react";
import { Link } from "react-router-dom";

const mockUser = {
    name: "John Doe",
    email: "john.doe@example.com",
    phone: "123-456-7890",
    password: "123456",
    address: "123 Main St, City, Country",
};

const mockOrders = [
    {
        id: "ORD-12345",
        date: "March 15, 2024",
        status: "Delivered",
        total: 1299.97,
        items: [
            { id: "1", name: "Modern Sofa", price: 899.99, quantity: 1 },
            { id: "2", name: "Wooden Coffee Table", price: 299.99, quantity: 1 },
            { id: "7", name: "Bedside Table", price: 129.99, quantity: 1 },
        ],
    },
    {
        id: "ORD-12344",
        date: "February 28, 2024",
        status: "Delivered",
        total: 449.99,
        items: [{ id: "9", name: "Standing Desk", price: 449.99, quantity: 1 }],
    },
]
const MyAccount = () => {
    const [activeTab, setActiveTab] = useState("profile");
    const [selectedOrder, setSelectedOrder] = useState(null);

const toggleOrderDetails = (orderId) => {
    setSelectedOrder(selectedOrder === orderId ? null : orderId);
};
    return (
        <>
            <div className="menu_overlay"></div>
            <div className="main_section">
                {/* START Breadcrumb */}
                <section className="breadcrumb_section nav">
                    <div className="container">
                        <nav aria-label="breadcrumb">
                            <ol className="breadcrumb">
                                <li className="breadcrumb-item text-capitalize">
                                    <a href="earthyellow.html">Trang Chủ</a> <i className="flaticon-arrows-4"></i>
                                </li>
                                <li className="breadcrumb-item active text-capitalize">Trang cá nhân</li>
                            </ol>
                        </nav>
                        <h1 className="title_h1 font-weight-normal text-capitalize">Trang cá nhân</h1>
                    </div>
                </section>
                {/* END Breadcrumb */}
                {/* START Wishlist Section */}
                <section className="wishlist_section padding-top-60 padding-bottom-60">

                    <main className="container">
                        <div className="menu_overlay"></div>
                        <div className="grid-container ">
                            <aside className="sidebar">
                                <div className="card">
                                    <div className="profile-section">
                                        <div className="avatar">
                                            <User className="icon" />
                                        </div>
                                        <h2 className="profile-name">{mockUser.name}</h2>
                                        <p className="profile-email">{mockUser.email}</p>
                                    </div>
                                    <nav className="nav-menu">
                                        <button onClick={() => setActiveTab("profile")} className={activeTab === "profile" ? "active" : ""}>
                                            <User className="icon" /> Thông tin cá nhân
                                        </button>
                                        <button onClick={() => setActiveTab("orders")} className={activeTab === "orders" ? "active" : ""}>
                                            <Package className="icon" /> Đơn mua
                                        </button>
                                        <button onClick={() => setActiveTab("addresses")} className={activeTab === "addresses" ? "active" : ""}>
                                            <Home className="icon" /> Dịa chỉ
                                        </button>
                                        <button onClick={() => setActiveTab("payment")} className={activeTab === "payment" ? "active" : ""}>
                                            <CreditCard className="icon" /> Phương thức thanh toán
                                        </button>
                                        <button onClick={() => setActiveTab("settings")} className={activeTab === "settings" ? "active" : ""}>
                                            <Settings className="icon" /> Cài đặt
                                        </button>
                                    </nav>
                                </div>
                            </aside>
                            <div className="content">
                                {activeTab === "profile" && (
                                    <div className="card">
                                        <h2 className="section-title">Thông tin cá nhân</h2>
                                        <div className="form-group">
                                            <label>Họ Tên</label>
                                            <input type="text" defaultValue={mockUser.name} />
                                        </div>
                                        <div className="form-group">
                                            <label>Email</label>
                                            <input type="email" defaultValue={mockUser.email} />
                                        </div>
                                        <div className="form-group">
                                            <label>Số điện thoại</label>
                                            <input type="tel" defaultValue={mockUser.phone} />
                                        </div>
                                        <h3 className="section-title">Thay đổi mật khẩu</h3>
                                        <div className="form-group">
                                            <label htmlFor="password">Mật khẩu</label>
                                            <input type="password" defaultValue={mockUser.password} />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="password">Mật khẩu mới</label>
                                            <input type="password" defaultValue="" />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="password">Xác nhận mật khẩu</label>
                                            <input type="password" defaultValue="" />
                                        </div>
                                        <button className="btn-save">Lưu thay đổi</button>
                                    </div>
                                )}
                                {activeTab === "orders" && (
                                    <div className="card">
                                        <div>
                                            <h2 className="section-title">Lịch sử mua hàng</h2>
                                            {mockOrders.length > 0 ? (
                                                <div className="order-list">
                                                    {mockOrders.map((order) => (
                                                        <div key={order.id} className="order-card">
                                                            <div className="order-header">
                                                                <div>
                                                                    <h3 className="order-id">Đơn #{order.id}</h3>
                                                                    <p className="order-date">Ngày mua {order.date}</p>
                                                                </div>
                                                                <div className="order-status-wrapper">
                                                                    <span
                                                                        className={`order-status ${order.status === "Delivered"
                                                                            ? "delivered"
                                                                            : order.status === "Processing"
                                                                                ? "processing"
                                                                                : "pending"
                                                                            }`}
                                                                    >
                                                                        {order.status}
                                                                    </span>
                                                                    {/* <button  className="order-details">Chi tiết</button> */}
                                                                    <Link to="/order_detail" className="order-details">
                                                                        Chi tiết
                                                                    </Link>
                                                                </div>
                                                            </div>
                                                            <div className="order-content">
                                                                <div className="order-items">
                                                                    {order.items.map((item) => (
                                                                        <div key={item.id} className="order-item">
                                                                            <div>
                                                                                <span className="item-name">{item.name}</span>
                                                                                <span className="item-quantity"> x{item.quantity}</span>
                                                                            </div>
                                                                            <span className="item-price">${(item.price * item.quantity).toFixed(2)}</span>
                                                                        </div>
                                                                    ))}
                                                                </div>
                                                                <div className="order-total">
                                                                    <span>Tổng</span>
                                                                    <span>${order.total.toFixed(2)}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    ))}
                                                </div>
                                            ) : (
                                                <div className="order-empty">
                                                    <Package className="order-empty-icon" />
                                                    <h3 className="order-empty-title">No Orders Yet</h3>
                                                    <p className="order-empty-text">You haven't placed any orders yet.</p>
                                                    <Link to="/shop" className="order-empty-link">
                                                        Tiếp tục mua sắm
                                                    </Link>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                )}
                                {activeTab === "addresses" && (
                                    <div className="card">
                                        <h2 className="address-title">Địa chỉ của tôi</h2>
                                        <div className="address-grid">
                                            <div className="address-card">
                                                <div className="address-actions">
                                                    <button className="edit-btn">Cập nhật</button>
                                                    <button className="delete-btn">Xóa</button>
                                                </div>
                                                <h3 className="address-heading">Địa chỉ</h3>
                                                <p className="address-details">
                                                    {mockUser.name}
                                                    <br />
                                                    {mockUser.address}
                                                    <br />
                                                    {mockUser.phone}
                                                </p>
                                            </div>
                                            <div className="address-add-card">
                                                <button className="add-address-btn">
                                                    <span>+</span> Thêm địa chỉ mới
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {activeTab === "payment" && (
                                    <div className="card">
                                        <h2 className="section-title">Phương thức thanh toán</h2>
                                        <div className="payment-grid">
                                            <div className="payment-card">
                                                <div className="address-actions">
                                                    <button className="edit-btn">Cập nhật</button>
                                                    <button className="delete-btn">Xóa</button>
                                                </div>
                                                <h3 className="payment-title">Credit Card</h3>
                                                <div className="card-info">
                                                    <div className="card-logo"></div>
                                                    <p className="card-details">
                                                        **** **** **** 4567
                                                        <br />
                                                        <span className="card-expiry">Expires 05/25</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div className="add-payment-card">
                                                <button className="add-payment-btn">
                                                    <span className="plus-icon">+</span> Thêm phương thức thanh toán mới
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {activeTab === "settings" && (
                                    <div className="card">
                                        <h2 className="section-title">Account Settings</h2>
                                        <button className="btn-logout">Logout</button>
                                    </div>
                                )}
                            </div>
                        </div>
                    </main>
                </section>
                {/* END Wishlist Section */}
            </div>
        </>
    );
};

export default MyAccount;
