import { User, Package, Home, CreditCard, Settings } from "lucide-react";
import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import OrderDetail from "./OrderDetail";
import { getAllOrders, getDetailOrder, getStatusLabel, Order, OrdersDetail } from "../services/Orders";
import toast from "react-hot-toast";
import { getProductDetail } from "../services/product";
import { getUser, updateUser, Users } from "../services/user";

const MyAccount = () => {
    const [activeTab, setActiveTab] = useState("profile");
    const [orders, setOrders] = useState<Order[]>([]);
    const [selectedOrderId, setSelectedOrderId] = useState<string | null>(null);
    const [page, setPage] = useState(1);
    const limit = 5; // Giới hạn 5 đơn hàng mỗi trang
    const [hasMore, setHasMore] = useState(true);
    const [isLoading, setIsLoading] = useState(false);

    // Cache dùng localStorage
    const getCachedData = (key: string) => JSON.parse(localStorage.getItem(key) || "{}");
    const setCachedData = (key: string, data: any) => localStorage.setItem(key, JSON.stringify(data));

    const orderCache = getCachedData("orderCache");
    const productCache = getCachedData("productCache");

    // Lấy danh sách đơn hàng cơ bản
    useEffect(() => {
        const fetchOrders = async () => {
            setIsLoading(true);
            try {
                const response = await getAllOrders(page, limit); // Giả sử API hỗ trợ phân trang
                const newOrders = response.data;

                // Kiểm tra cache để gán chi tiết nếu có
                const ordersWithCachedDetails = newOrders.map(order => ({
                    ...order,
                    order_details: orderCache[order.id] || []
                }));

                setOrders(prev => [...prev, ...ordersWithCachedDetails]);
                setHasMore(newOrders.length === limit);

                // Tải chi tiết cho các đơn hàng chưa có trong cache
                const uncachedOrders = newOrders.filter(order => !orderCache[order.id]);
                if (uncachedOrders.length > 0) {
                    const detailsPromises = uncachedOrders.map(async order => {
                        const detailResponse = await getDetailOrder(order.id);
                        const details = detailResponse.data.order_details;

                        // Lấy product_ids
                        const productIds = [
                            ...new Set(details.map((d: OrdersDetail) => d.product_detail.product_id))
                        ];

                        // Lấy chi tiết sản phẩm
                        const productPromises = productIds.map(async id => {
                            if (productCache[id]) return productCache[id];
                            const productResponse = await getProductDetail(id);
                            const productData = productResponse.data.data;
                            productCache[id] = productData;
                            setCachedData("productCache", productCache);
                            return productData;
                        });

                        const products = await Promise.all(productPromises);
                        const productMap = new Map(products.map(p => [p.id, p.name]));

                        const enrichedDetails = details.map((detail: OrdersDetail) => ({
                            ...detail,
                            product_detail: {
                                ...detail.product_detail,
                                product_name: productMap.get(detail.product_detail.product_id) || "Không xác định"
                            }
                        }));

                        orderCache[order.id] = enrichedDetails;
                        setCachedData("orderCache", orderCache);

                        return { ...order, order_details: enrichedDetails };
                    });

                    const enrichedOrders = await Promise.all(detailsPromises);
                    setOrders(prev =>
                        prev.map(o => enrichedOrders.find(e => e.id === o.id) || o)
                    );
                }
            } catch (error) {
                console.error("Lỗi khi lấy đơn hàng:", error);
                toast.error("Lỗi khi lấy đơn hàng");
            } finally {
                setIsLoading(false);
            }
        };

        fetchOrders();
    }, [page]);

    const toggleOrderDetails = (orderId: number) => {
        setSelectedOrderId(String(orderId));
        setActiveTab("orderDetail");
    };

    const [user, setUser] = useState<Users | null>(null);
    const [loading, setLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);
    const [name, setName] = useState("");
    const [phoneNumber, setPhoneNumber] = useState("");
    const [gender, setGender] = useState("");
    const [dateOfBirth, setDateOfBirth] = useState("");
    const [password, setPassword] = useState("");
    const [newPassword, setNewPassword] = useState("");
    const [confirmPassword, setConfirmPassword] = useState("");

    useEffect(() => {
        getUser()
            .then(({ data }) => {
                setUser(data);
                setName(data?.name || "");
                setPhoneNumber(data?.phone_number || "");
                setGender(data?.gender || "");
                if (data?.date_of_birth) {
                    const formattedDate = new Date(data.date_of_birth).toISOString().split("T")[0];
                    setDateOfBirth(formattedDate);
                }
                setLoading(false);
            })
            .catch(err => {
                setError("Lỗi khi lấy thông tin user");
                setLoading(false);
            });
    }, []);

    useEffect(() => {
        if (user) {
            setName(user.name || "");
            setPhoneNumber(user.phone_number || "");
            setGender(user.gender || "");
            if (user.date_of_birth) {
                const formattedDate = new Date(user.date_of_birth).toISOString().split("T")[0];
                setDateOfBirth(formattedDate);
            }
        }
    }, [user]);

    const handleUpdate = async () => {
        if (!user) return alert("Không có thông tin người dùng!");
        if (newPassword && newPassword !== confirmPassword) {
            return alert("Mật khẩu mới và xác nhận mật khẩu không khớp!");
        }

        const updatedData = {
            name,
            phone_number: phoneNumber,
            gender,
            date_of_birth: dateOfBirth,
            ...(newPassword && { password: newPassword }),
        };

        try {
            const response = await updateUser(user.id, updatedData);
            alert(response.data.message);
            setUser(response.data.data);
            setPassword("");
            setNewPassword("");
            setConfirmPassword("");
            toast.success("Thay đổi thông tin thành công");
        } catch (error) {
            alert("Có lỗi xảy ra khi cập nhật thông tin!");
        }
    };

    if (loading) return <div className="nav">Đang tải...</div>;
    if (error) return <div>{error}</div>;

    return (
        <>
            <div className="menu_overlay"></div>
            <div className="main_section">
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

                <section className="wishlist_section padding-top-60 padding-bottom-60">
                    <main className="container">
                        <div className="grid-container">
                            <aside className="sidebar">
                                <div className="card">
                                    <div className="profile-section">
                                        <div className="avatar">
                                            <User className="icon" />
                                        </div>
                                        <h2 className="profile-name">{user?.name}</h2>
                                        <p className="profile-email">{user?.email}</p>
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
                                            <input type="text" value={name} onChange={(e) => setName(e.target.value)} />
                                        </div>
                                        <div className="form-group">
                                            <label>Email</label>
                                            <input type="text" defaultValue={user?.email} disabled />
                                        </div>
                                        <div className="form-group">
                                            <label>Số điện thoại</label>
                                            <input type="tel" value={phoneNumber} onChange={(e) => setPhoneNumber(e.target.value)} />
                                        </div>
                                        <div className="form-group">
                                            <label>Giới tính:</label>
                                            <div className="radio-group">
                                                <label>
                                                    <input
                                                        type="radio"
                                                        name="gender"
                                                        value="Nam"
                                                        checked={gender === "Nam"}
                                                        onChange={(e) => setGender(e.target.value)}
                                                    />
                                                    Nam
                                                </label>
                                                <label>
                                                    <input
                                                        type="radio"
                                                        name="gender"
                                                        value="Nữ"
                                                        checked={gender === "Nữ"}
                                                        onChange={(e) => setGender(e.target.value)}
                                                    />
                                                    Nữ
                                                </label>
                                                <label>
                                                    <input
                                                        type="radio"
                                                        name="gender"
                                                        value="Khác"
                                                        checked={gender === "Khác"}
                                                        onChange={(e) => setGender(e.target.value)}
                                                    />
                                                    Khác
                                                </label>
                                            </div>
                                        </div>
                                        <div className="form-group">
                                            <label>Ngày sinh</label>
                                            <input type="date" value={dateOfBirth} onChange={(e) => setDateOfBirth(e.target.value)} />
                                        </div>
                                        <h3 className="section-title">Thay đổi mật khẩu</h3>
                                        <div className="form-group">
                                            <label htmlFor="password">Mật khẩu</label>
                                            <input type="password" value={password} onChange={(e) => setPassword(e.target.value)} />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="password">Mật khẩu mới</label>
                                            <input type="password" value={newPassword} onChange={(e) => setNewPassword(e.target.value)} />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="password">Xác nhận mật khẩu</label>
                                            <input type="password" value={confirmPassword} onChange={(e) => setConfirmPassword(e.target.value)} />
                                        </div>
                                        <button className="btn-save" onClick={handleUpdate}>Lưu thay đổi</button>
                                    </div>
                                )}
                                {activeTab === "orders" && (
                                    <div className="card">
                                        <h2 className="section-title">Lịch sử mua hàng</h2>
                                        {isLoading && <div>Đang tải...</div>}
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
                                                                {order.order_details?.length > 0 ? (
                                                                    order.order_details.map((item) => (
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
                                                                    ))
                                                                ) : (
                                                                    <div>Đang tải chi tiết...</div>
                                                                )}
                                                            </div>
                                                            <div className="order-total">
                                                                <span>Tổng</span>
                                                                <span>{parseFloat(order.total_price).toLocaleString()} VND</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ))}
                                                {hasMore && !isLoading && (
                                                    <button onClick={() => setPage(prev => prev + 1)} className="load-more-btn">
                                                        Xem thêm
                                                    </button>
                                                )}
                                            </div>
                                        ) : (
                                            !isLoading && (
                                                <div className="order-empty">
                                                    <Package className="order-empty-icon" />
                                                    <h3 className="order-empty-title">Chưa có đơn hàng nào</h3>
                                                    <p className="order-empty-text">Bạn chưa có đơn hàng nào.</p>
                                                    <Link to="/shop" className="order-empty-link">
                                                        Tiếp tục mua sắm
                                                    </Link>
                                                </div>
                                            )
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
                                                    {user?.address}
                                                    <br />
                                                    {user?.phone_number}
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
            </div>
        </>
    );
};

export default MyAccount;