import { User, Package, Home, CreditCard, Settings } from "lucide-react";
import { useEffect, useState } from "react";
import { Link, useLocation } from "react-router-dom";
import OrderDetail from "./OrderDetail";
import { getAllOrders, getStatusLabel, getStatusColor, Order } from "../services/Orders";
import toast from "react-hot-toast";
import { getUser, updateUser, Users } from "../services/user";
import Pagination from "./Pagination";
import styles from './MyAccount.module.css'; // Import CSS Modules

const MyAccount = () => {
  const location = useLocation();
  const queryParams = new URLSearchParams(location.search);
  const tab = queryParams.get("tab");
  const orderId = queryParams.get("orderId");

  const validTabs = ["profile", "orders", "orderDetail", "addresses", "payment", "settings"];
  const [activeTab, setActiveTab] = useState(() => {
    return validTabs.includes(tab) ? tab : "profile";
  });
  const [selectedOrderId, setSelectedOrderId] = useState<string | null>(orderId || null);

  // State cho phân trang và danh sách đơn hàng
  const [orders, setOrders] = useState<Order[]>([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [itemsPerPage] = useState(3);
  const [isLoading, setIsLoading] = useState(false);
  const [orderError, setOrderError] = useState<string | null>(null);

  // State cho tab trạng thái đơn hàng
  const [orderStatusTab, setOrderStatusTab] = useState("all");
  const [searchQuery, setSearchQuery] = useState("");

  useEffect(() => {
    const fetchOrders = async () => {
      setIsLoading(true);
      try {
        const { data } = await getAllOrders();
        let filteredOrders = data;

        // Lọc theo trạng thái đơn hàng
        if (orderStatusTab !== "all") {
          filteredOrders = data.filter((order) => order.status.toLowerCase() === orderStatusTab);
        }

        // Lọc theo tìm kiếm
        if (searchQuery) {
          filteredOrders = filteredOrders.filter((order) =>
            order.order_details.some((item) =>
              item.product_name.toLowerCase().includes(searchQuery.toLowerCase())
            )
          );
        }

        const totalItems = filteredOrders.length;
        setTotalPages(Math.ceil(totalItems / itemsPerPage));

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedOrders = filteredOrders.slice(startIndex, endIndex);

        setOrders(paginatedOrders);
      } catch (error) {
        console.error("Lỗi khi lấy đơn hàng:", error);
        setOrderError("Không thể tải danh sách đơn hàng. Vui lòng thử lại sau.");
        toast.error("Lỗi khi tải đơn hàng!");
      } finally {
        setIsLoading(false);
      }
    };

    fetchOrders();
  }, [currentPage, orderStatusTab, searchQuery]);

  const handlePageChange = (page: number) => {
    setCurrentPage(page);
  };

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
      .catch((err) => {
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
                  <a href="earthyellow.html">Trang Chủ</a>{" "}
                  <i className="flaticon-arrows-4"></i>
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
                    <button
                      onClick={() => setActiveTab("profile")}
                      className={activeTab === "profile" ? "active" : ""}
                    >
                      <User className="icon" /> Thông tin cá nhân
                    </button>
                    <button
                      onClick={() => setActiveTab("orders")}
                      className={activeTab === "orders" || activeTab === "orderDetail" ? "active" : ""}
                    >
                      <Package className="icon" /> Đơn mua
                    </button>
                    <button
                      onClick={() => setActiveTab("addresses")}
                      className={activeTab === "addresses" ? "active" : ""}
                    >
                      <Home className="icon" /> Địa chỉ
                    </button>
                    <button
                      onClick={() => setActiveTab("payment")}
                      className={activeTab === "payment" ? "active" : ""}
                    >
                      <CreditCard className="icon" /> Phương thức thanh toán
                    </button>
                    <button
                      onClick={() => setActiveTab("settings")}
                      className={activeTab === "settings" ? "active" : ""}
                    >
                      <Settings className="icon" /> Cài đặt
                    </button>
                  </nav>
                </div>
              </aside>
              <div className="content">
                {activeTab === "orders" && (
                  <div className="card">
                    <div className="order-search">
                      <input
                        type="text"
                        placeholder="Bạn có thể tìm kiếm theo Tên Sản phẩm hoặc ID đơn hàng"
                        value={searchQuery}
                        onChange={(e) => setSearchQuery(e.target.value)}
                      />
                    </div>
                    <div className="order-status-tabs">
                      <button
                        className={orderStatusTab === "all" ? "active" : ""}
                        onClick={() => setOrderStatusTab("all")}
                      >
                        Tất cả
                      </button>
                      <button
                        className={orderStatusTab === "pending" ? "active" : ""}
                        onClick={() => setOrderStatusTab("pending")}
                      >
                        Chờ xác nhận
                      </button>
                      <button
                        className={orderStatusTab === "confirmed" ? "active" : ""}
                        onClick={() => setOrderStatusTab("confirmed")}
                      >
                        Đã xác nhận
                      </button>
                      <button
                        className={orderStatusTab === "processing" ? "active" : ""}
                        onClick={() => setOrderStatusTab("processing")}
                      >
                        Đang xử lý
                      </button>
                      <button
                        className={orderStatusTab === "shipping" ? "active" : ""}
                        onClick={() => setOrderStatusTab("shipping")}
                      >
                        Đang giao hàng
                      </button>
                      <button
                        className={orderStatusTab === "delivered" ? "active" : ""}
                        onClick={() => setOrderStatusTab("delivered")}
                      >
                        Đã giao hàng
                      </button>
                      <button
                        className={orderStatusTab === "completed" ? "active" : ""}
                        onClick={() => setOrderStatusTab("completed")}
                      >
                        Hoàn tất
                      </button>
                      <button
                        className={orderStatusTab === "returned" ? "active" : ""}
                        onClick={() => setOrderStatusTab("returned")}
                      >
                        Đã trả hàng
                      </button>
                      <button
                        className={orderStatusTab === "refunded" ? "active" : ""}
                        onClick={() => setOrderStatusTab("refunded")}
                      >
                        Đã hoàn tiền
                      </button>
                      <button
                        className={orderStatusTab === "cancelled" ? "active" : ""}
                        onClick={() => setOrderStatusTab("cancelled")}
                      >
                        Đã hủy
                      </button>
                    </div>
                    {isLoading && <div>Đang tải...</div>}
                    {orderError && <div>{orderError}</div>}
                    {!isLoading && !orderError && orders.length > 0 ? (
                      <>
                        <div className="order-list">
                          {orders.map((order) => (
                            <div key={order.id} className="order-card">
                              <div className="order-header">
                                <div className="order-shop">
                                  <button className="chat-btn">Mã đơn: #FV-HN-{order.id}</button>
                                  <button className="view-shop-btn">
                                    Ngày đặt: {new Date(order.created_at).toLocaleDateString()}
                                  </button>
                                </div>
                                <div className="order-status-wrapper">
                                  <span
                                    className="order-status"
                                    data-status={order.status.toLowerCase()} // Thêm data-status
                                    style={{ color: getStatusColor(order.status) }}
                                  >
                                    {getStatusLabel(order.status)}
                                  </span>
                                </div>
                              </div>
                              <div className="order-content">
                                <div className="order-items">
                                  {order.order_details?.length > 0 ? (
                                    order.order_details.map((item, index) => (
                                      <div key={index} className="order-item">
                                        <img
                                          src={item.image}
                                          alt={item.product_name}
                                          className="item-image"
                                        />
                                        <div className="item-details">
                                          <span className="item-name">
                                            {item.product_name || "Tên sản phẩm không có"}
                                          </span>
                                          <span className="item-quantity">x{item.quantity}</span>
                                          {/* <span className="item-price">
                                            {parseFloat(item.price).toLocaleString()} VND
                                          </span> */}
                                        </div>
                                      </div>
                                    ))
                                  ) : (
                                    <div>Không có chi tiết đơn hàng</div>
                                  )}
                                </div>
                                <div className="order-footer">
                                  <div className="order-total">
                                    <span>Tổng tiền:</span>
                                    <span className="total-price">
                                      {parseFloat(order.total_price).toLocaleString()} VND
                                    </span>
                                  </div>
                                  <div className="order-actions">
                                    <button
                                      className="action-btn view-cancel"
                                      onClick={() => toggleOrderDetails(order.id)}
                                    >
                                      Xem chi tiết
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          ))}
                        </div>
                        <div className="pagination-container" style={{ marginTop: "15px" }}>
                          <Pagination
                            currentPage={currentPage}
                            totalPages={totalPages}
                            onPageChange={handlePageChange}
                          />
                        </div>
                      </>
                    ) : (
                      !isLoading &&
                      !orderError && (
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
                {activeTab === "profile" && (
                  <div className="card">
                    <div className={styles.profileForm}>
                      <div className={styles.profileFormContent}>
                        <h2 className={styles.sectionTitle}>Thông tin cá nhân</h2>
                        <div className={styles.formGroupProfile}>
                          <label htmlFor="name">Họ Tên</label>
                          <input
                            type="text"
                            id="name"
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                            autoComplete="off"
                          />
                        </div>
                        <div className={styles.formGroupProfile}>
                          <label htmlFor="email">Email</label>
                          <input
                            type="text"
                            id="email"
                            defaultValue={user?.email}
                            disabled
                          />
                        </div>
                        <div className={styles.formGroupProfile}>
                          <label htmlFor="phoneNumber">Số điện thoại</label>
                          <input
                            type="tel"
                            id="phoneNumber"
                            value={phoneNumber}
                            onChange={(e) => setPhoneNumber(e.target.value)}
                            autoComplete="off"
                          />
                        </div>
                        <div className={styles.formGroupProfile}>
                          <label>Giới tính:</label>
                          <div className={styles.radioGroup}>
                            <label>
                              <input
                                type="radio"
                                name="gender"
                                value="Nam"
                                checked={gender === "Nam"}
                                onChange={(e) => setGender(e.target.value)}
                                autoComplete="off"
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
                                autoComplete="off"
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
                                autoComplete="off"
                              />
                              Khác
                            </label>
                          </div>
                        </div>
                        <div className={styles.formGroupProfile}>
                          <label htmlFor="dateOfBirth">Ngày sinh</label>
                          <input
                            type="date"
                            id="dateOfBirth"
                            value={dateOfBirth}
                            onChange={(e) => setDateOfBirth(e.target.value)}
                            autoComplete="off"
                          />
                        </div>
                      </div>
                      <div className={styles.profileFormContent}>
                        <div className={styles.formGroupProfile}>
                          <label htmlFor="password">Mật khẩu hiện tại</label>
                          <input
                            type="password"
                            id="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            autoComplete="off"
                          />
                        </div>
                        <div className={styles.formGroupProfile}>
                          <label htmlFor="newPassword">Mật khẩu mới</label>
                          <input
                            type="password"
                            id="newPassword"
                            value={newPassword}
                            onChange={(e) => setNewPassword(e.target.value)}
                            autoComplete="off"
                          />
                        </div>
                        <div className={styles.formGroupProfile}>
                          <label htmlFor="confirmPassword">Xác nhận mật khẩu</label>
                          <input
                            type="password"
                            id="confirmPassword"
                            value={confirmPassword}
                            onChange={(e) => setConfirmPassword(e.target.value)}
                            autoComplete="off"
                          />
                        </div>
                      </div>
                    </div>
                    <button className={styles.btnSaveProflie} onClick={handleUpdate}>
                      Lưu thay đổi
                    </button>
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
                    <h2 className="section-title">Cài đặt tài khoản</h2>
                    <button className="btn-logout">Đăng xuất</button>
                  </div>
                )}
                {activeTab === "orderDetail" && selectedOrderId && (
                  <div className="card">
                    {isLoading ? (
                      <div>Đang tải...</div>
                    ) : orders.find((o) => String(o.id) === selectedOrderId) ? (
                      <OrderDetail
                        order={orders.find((o) => String(o.id) === selectedOrderId) || ({} as Order)}
                      />
                    ) : (
                      <div>Không tìm thấy đơn hàng với ID: {selectedOrderId}</div>
                    )}
                    <div className="back-link" onClick={() => setActiveTab("orders")}>
                      &lt;&lt; Quay lại đơn hàng của tôi
                    </div>
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