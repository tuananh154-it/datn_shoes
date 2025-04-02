import { User, Package, Home, CreditCard, Settings } from "lucide-react";
import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import OrderDetail from "./OrderDetail";
import { getAllOrders, getDetailOrder, getStatusLabel, Order, OrdersDetail } from "../services/Orders";
import toast from "react-hot-toast";
import { getProductDetail } from "../services/product";

const mockUser = {
    name: "John Doe",
    email: "john.doe@example.com",
    phone: "123-456-7890",
    password: "123456",
    address: "123 Main St, City, Country",
};

const MyAccount = () => {
    const [activeTab, setActiveTab] = useState("profile");
    // const [isOpen, setIsOpen] = useState(false);
    const [orders, setOrders] = useState<Order[]>([]);
    // const [ordersDetail, setOrdersDetail] = useState<OrdersDetail[]>([]);
    const [selectedOrderId, setSelectedOrderId] = useState<string | null>(null);
    const toggleOrderDetails = (orderId: number) => {
        console.log("Button clicked! Đang lấy chi tiết cho đơn hàng:", orderId);
        setSelectedOrderId(orderId);
        setActiveTab("orderDetail");
    };

    // useEffect(() => {
    //     getAllOrders()
    //         .then(({ data }) => {
    //             setOrders(data);
    //             console.log("Dữ liệu đơn hàng từ API:", data); // Kiểm tra API trả về gì
    //         })
    //         .catch(() => toast.error("Lỗi khi lấy đơn hàng"));
    // }, []);


    useEffect(() => {
        const fetchOrdersWithDetails = async () => {
            try {
                // 1️⃣ Lấy danh sách đơn hàng
                const response = await getAllOrders();
                if (!response || !response.data) throw new Error("API không trả về dữ liệu hợp lệ");
    
                const ordersData = response.data;
                if (!Array.isArray(ordersData)) throw new Error("Dữ liệu đơn hàng không phải là mảng");
    
                // 2️⃣ Gọi API lấy chi tiết đơn hàng song song (giới hạn 5 requests cùng lúc)
                const orderDetailsResponses = await Promise.allSettled(
                    ordersData.map(order => getDetailOrder(order.id))
                );
    
                // 3️⃣ Lọc đơn hàng hợp lệ
                const ordersWithDetails = ordersData.map((order, index) => ({
                    ...order,
                    order_details: orderDetailsResponses[index].status === "fulfilled"
                        ? orderDetailsResponses[index].value?.data?.order_details || []
                        : []
                }));
    
                // 4️⃣ Lọc danh sách product_id (loại bỏ trùng lặp)
                const productIds = [...new Set(
                    ordersWithDetails.flatMap(order => 
                        order.order_details.map(detail => detail.product_detail.product_id)
                    )
                )];
    
                // 5️⃣ Chia nhỏ danh sách sản phẩm thành nhóm (batch requests)
                const chunkSize = 10; // Giới hạn mỗi lần gọi tối đa 10 sản phẩm
                const productChunks = [];
                for (let i = 0; i < productIds.length; i += chunkSize) {
                    productChunks.push(productIds.slice(i, i + chunkSize));
                }
    
                // 6️⃣ Gọi API lấy chi tiết sản phẩm theo nhóm (tránh quá tải)
                const productResponses = await Promise.all(
                    productChunks.map(chunk =>
                        Promise.all(chunk.map(id => getProductDetail(id)))
                    )
                );
    
                // 7️⃣ Lưu kết quả vào map để tra cứu nhanh
                const productMap = new Map();
                productResponses.flat().forEach(response => {
                    if (response?.data?.data) {
                        productMap.set(response.data.data.id, response.data.data.name);
                    }
                });
    
                // 8️⃣ Gán tên sản phẩm vào order_details
                const finalOrders = ordersWithDetails.map(order => ({
                    ...order,
                    order_details: order.order_details.map(detail => ({
                        ...detail,
                        product_detail: {
                            ...detail.product_detail,
                            product_name: productMap.get(detail.product_detail.product_id) || "Không xác định"
                        }
                    }))
                }));
    
                setOrders(finalOrders);
            } catch (error) {
                console.error("Lỗi khi lấy danh sách đơn hàng:", error);
            }
        };
    
        fetchOrdersWithDetails();
    }, []);
    
    

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
                                        <button
                                            onClick={() => setActiveTab("orders")}
                                            className={activeTab === "orders" || activeTab === "orderDetail" ? "active" : ""}
                                        >
                                            <Package className="icon" /> Đơn mua
                                        </button>
                                        <button onClick={() => setActiveTab("addresses")} className={activeTab === "addresses" ? "active" : ""}>
                                            <Home className="icon" /> Địa chỉ
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
                                        <h2 className="section-title">Lịch sử mua hàng</h2>
                                        {orders.length > 0 ? (
                                            <div className="order-list">
                                                {orders.map((order) => (
                                                    <div key={order.id} className="order-card">
                                                        <div className="order-header">
                                                            <div>
                                                                <h3 className="order-id">Đơn #{order.id}</h3>
                                                                <p className="order-date">Ngày mua {new Date(order.created_at).toLocaleDateString()}</p>
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
                                                                    {getStatusLabel(order.status)}
                                                                </span>
                                                                <button className="order-details" onClick={() => toggleOrderDetails(order.id)}>
                                                                    Chi tiết
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div className="order-content">
                                                            <div className="order-items">
                                                                {order.order_details?.map((item) => (
                                                                    <div key={item.id} className="order-item">
                                                                        <div>
                                                                            <span className="item-name">
                                                                                {item.product_detail.product_name || "Tên sản phẩm không có"}
                                                                            </span>
                                                                            <span className="item-quantity"> x{item.quantity}</span>
                                                                        </div>
                                                                        <span className="item-price">
                                                                            {parseFloat(item.price).toLocaleString()} VND
                                                                        </span>
                                                                    </div>
                                                                ))}
                                                            </div>
                                                            <div className="order-total">
                                                                <span>Tổng</span>
                                                                <span>{parseFloat(order.total_price).toLocaleString()} VND</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ))}
                                            </div>
                                        ) : (
                                            <div className="order-empty">
                                                <Package className="order-empty-icon" />
                                                <h3 className="order-empty-title">Chưa có đơn hàng nào</h3>
                                                <p className="order-empty-text">Bạn chưa có đơn hàng nào.</p>
                                                <Link to="/shop" className="order-empty-link">
                                                    Tiếp tục mua sắm
                                                </Link>
                                            </div>
                                        )}
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
                                {activeTab === "orderDetail" && selectedOrderId && (
                                    <div className="card">
                                        <button className="back-button" onClick={() => setActiveTab("orders")}>
                                            ⬅ Quay lại
                                        </button>
                                        <OrderDetail orderId={selectedOrderId} />
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
