import { User, Package, Home, Settings } from "lucide-react";
import { useEffect, useState, useCallback } from "react";
import { Link, useLocation } from "react-router-dom";
import OrderDetail from "./OrderDetail";
import { getAllOrders, getStatusLabel, getStatusColor, Order } from "../services/Orders";
import toast from "react-hot-toast";
import { getUser, updateUser, Users } from "../services/user";
import { useDispatch } from "react-redux";
import { refreshUser } from "../store/useSlice";
import Pagination from "./Pagination";
import styles from './MyAccount.module.css';
import { api } from "../config/axios";
import Pusher from "pusher-js";

// Định nghĩa kiểu cho hàm debounce
type DebounceFunction<T extends (...args: any[]) => void> = (...args: Parameters<T>) => void;

// Hàm debounce với TypeScript
const debounce = <T extends (...args: any[]) => void>(func: T, delay: number): DebounceFunction<T> => {
  let timeoutId: NodeJS.Timeout;
  return (...args: Parameters<T>) => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => func(...args), delay);
  };
};

const MyAccount = () => {
  const dispatch = useDispatch();
  const location = useLocation();
  const queryParams = new URLSearchParams(location.search);
  const tab = queryParams.get("tab");
  const orderId = queryParams.get("orderId");

  const validTabs = ["profile", "orders", "orderDetail", "settings", "addresses"];
  const [activeTab, setActiveTab] = useState<string>(() => {
    return validTabs.includes(tab) ? tab! : "profile";
  });
  const [selectedOrderId, setSelectedOrderId] = useState<string | null>(orderId || null);

  // State cho phân trang và danh sách đơn hàng
  const [allOrders, setAllOrders] = useState<Order[]>([]);
  const [displayedOrders, setDisplayedOrders] = useState<Order[]>([]);
  const [currentPage, setCurrentPage] = useState<number>(1);
  const [totalPages, setTotalPages] = useState<number>(1);
  const [itemsPerPage] = useState<number>(3);
  const [isLoading, setIsLoading] = useState<boolean>(false);
  const [orderError, setOrderError] = useState<string | null>(null);

  // State cho tab trạng thái đơn hàng và tìm kiếm
  const [orderStatusTab, setOrderStatusTab] = useState<string>("all");
  const [searchQuery, setSearchQuery] = useState<string>("");
  const [debouncedSearchQuery, setDebouncedSearchQuery] = useState<string>("");

  // Kết nối Pusher để lắng nghe cập nhật trạng thái
  useEffect(() => {
    const pusher = new Pusher("ee494af10a7f4a6e48b6", {
      cluster: "mt1",
      encrypted: true,
    });

    const channel = pusher.subscribe("orders");

    channel.bind("order.placed", (data: Order) => {
      console.log("Received order.placed event in MyAccount:", data);
      if (data && data.status !== "pending") {
        // Cập nhật trạng thái đơn hàng
        setAllOrders((prevOrders) =>
          prevOrders.map((order) =>
            order.id === data.id ? { ...order, status: data.status } : order
          )
        );
        toast.success(`Đơn hàng #FV-HN-${data.id} đã cập nhật trạng thái: ${getStatusLabel(data.status)}`, {
          position: "top-right",
          duration: 3000,
        });
      }
    });

    return () => {
      channel.unbind_all();
      channel.unsubscribe();
      pusher.disconnect();
    };
  }, []);

  // Xử lý confirm order
  useEffect(() => {
    const tempOrderId = localStorage.getItem('temp_order_id');
    if (tempOrderId) {
      api.post('/confirm-order', { temp_order_id: tempOrderId })
        .then(response => {
          console.log(response.data.message);
          localStorage.removeItem('temp_order_id');
        })
        .catch(error => {
          console.error('Error confirming order:', error);
        });
    }
  }, []);
  // xử lý xác nhận hoàn tất đơn hàng 
  const handleConfirmReceipt = async (orderId: number) => {
    try {
      const response = await api.post(`/orders/${orderId}/confirm-receipt`);

      if (response.status === 200) {
       toast.success("đơn hàng đã được hoàn tất")
        // Ẩn nút sau khi hoàn tất
        window.location.href = '/myaccout?tab=orders';
        const button = document.querySelector(`.view-cancel[data-order-id="${orderId}"]`) as HTMLButtonElement;
        if (button) button.style.display = 'none';

      } else {
        alert('Có lỗi xảy ra khi xác nhận đơn hàng');
      }
    } catch (error) {
      alert('Lỗi kết nối đến máy chủ');
    }
  };
  // Lấy toàn bộ đơn hàng khi component mount
  useEffect(() => {
    const fetchOrders = async () => {
      setIsLoading(true);
      try {
        const { data } = await getAllOrders();
        setAllOrders(data);
      } catch (error) {
        console.error("Lỗi khi lấy đơn hàng:", error);
        setOrderError("Không thể tải danh sách đơn hàng. Vui lòng thử lại sau.");
        toast.error("Lỗi khi tải đơn hàng!");
      } finally {
        setIsLoading(false);
      }
    };

    fetchOrders();
  }, []);

  // Hàm chuẩn hóa từ khóa tìm kiếm
  const normalizeString = (str: string): string => {
    return str
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");
  };

  // Debounce tìm kiếm
  const debouncedSetSearchQuery = useCallback(
    debounce((value: string) => {
      setDebouncedSearchQuery(value);
      setCurrentPage(1);
    }, 300),
    []
  );

  // Xử lý tìm kiếm
  const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const value = e.target.value;
    setSearchQuery(value);
    debouncedSetSearchQuery(value);
  };

  // Lọc và phân trang đơn hàng
  useEffect(() => {
    let filtered = allOrders;

    if (orderStatusTab !== "all") {
      filtered = allOrders.filter((order) => order.status.toLowerCase() === orderStatusTab);
    }

    if (debouncedSearchQuery) {
      const normalizedQuery = normalizeString(debouncedSearchQuery);
      filtered = filtered.filter((order) => {
        const matchesId = `#fv-hn-${order.id}`.includes(normalizedQuery);
        const matchesProductName = order.order_details.some((item) =>
          normalizeString(item.product_name).includes(normalizedQuery)
        );
        return matchesId || matchesProductName;
      });
    }

    const totalItems = filtered.length;
    setTotalPages(Math.ceil(totalItems / itemsPerPage));

    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedOrders = filtered.slice(startIndex, endIndex);

    setDisplayedOrders(paginatedOrders);
  }, [allOrders, orderStatusTab, debouncedSearchQuery, currentPage, itemsPerPage]);

  const handlePageChange = (page: number) => {
    setCurrentPage(page);
  };

  const toggleOrderDetails = (orderId: number) => {
    setSelectedOrderId(String(orderId));
    setActiveTab("orderDetail");
  };

  // Reset trang khi thay đổi tab trạng thái
  useEffect(() => {
    setCurrentPage(1);
  }, [orderStatusTab]);

  const [user, setUser] = useState<Users | null>(null);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);
  const [name, setName] = useState<string>("");
  const [phoneNumber, setPhoneNumber] = useState<string>("");
  const [gender, setGender] = useState<string>("");
  const [dateOfBirth, setDateOfBirth] = useState<string>("");
  const [password, setPassword] = useState<string>("");
  const [newPassword, setNewPassword] = useState<string>("");
  const [confirmPassword, setConfirmPassword] = useState<string>("");
  const [isEditingAddress, setIsEditingAddress] = useState<boolean>(false);
  const [address, setAddress] = useState<string>("");

  useEffect(() => {
    getUser()
      .then(({ data }) => {
        console.log("User data from API:", data);
        setUser(data);
        setName(data?.name || "");
        setPhoneNumber(data?.phone_number || "");
        setGender(data?.gender || "");
        setAddress(data?.address || "");
        setDateOfBirth(data?.date_of_birth || "");
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
      setAddress(user.address || "");
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
      dispatch(refreshUser());
    } catch (error) {
      alert("Có lỗi xảy ra khi cập nhật thông tin!");
    }
  };

  const handleUpdateAddress = async () => {
    if (!user) return alert("Không có thông tin người dùng!");
    if (!address.trim()) return alert("Vui lòng nhập địa chỉ!");

    try {
      const updatedData = {
        ...user,
        address: address.trim(),
      };
      const response = await updateUser(user.id, updatedData);
      alert(response.data.message);
      setUser(response.data.data);
      setIsEditingAddress(false);
      toast.success("Cập nhật địa chỉ thành công");
      dispatch(refreshUser());
    } catch (error) {
      alert("Có lỗi xảy ra khi cập nhật địa chỉ!");
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
        <section className="wishlist_section padding-bottom-60">
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
                        placeholder="Bạn có thể tìm kiếm theo Tên Sản phẩm hoặc Mã đơn hàng"
                        value={searchQuery}
                        onChange={handleSearchChange}
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
                    {!isLoading && !orderError && displayedOrders.length > 0 ? (
                      <>
                        <div className="order-list">
                          {displayedOrders.map((order) => (
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
                                    data-status={order.status.toLowerCase()}
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
                                    {order.status === 'delivered' && (
                                      <button
                                        className="action-btn view-cancel"
                                        onClick={() => handleConfirmReceipt(order.id)}
                                        data-order-id={order.id}
                                      >
                                        Hoàn tất
                                      </button>
                                    )}
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
                          <h3 className="order-empty-title">
                            {debouncedSearchQuery
                              ? "Không tìm thấy đơn hàng"
                              : "Chưa có đơn hàng nào"}
                          </h3>
                          <p className="order-empty-text">
                            {debouncedSearchQuery
                              ? "Không có đơn hàng nào khớp với tìm kiếm của bạn."
                              : "Bạn chưa có đơn hàng nào."}
                          </p>
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
                    {isEditingAddress ? (
                      <div className="address-form">
                        <div className="form-group">
                          <label htmlFor="name">Họ và tên</label>
                          <input
                            type="text"
                            id="name"
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                            placeholder="Nhập họ và tên"
                          />
                        </div>
                        <div className="form-group">
                          <label htmlFor="phone">Số điện thoại</label>
                          <input
                            type="text"
                            id="phone"
                            value={phoneNumber}
                            onChange={(e) => setPhoneNumber(e.target.value)}
                            placeholder="Nhập số điện thoại"
                          />
                        </div>
                        <div className="form-group">
                          <label htmlFor="address">Địa chỉ</label>
                          <textarea
                            id="address"
                            value={address}
                            onChange={(e) => setAddress(e.target.value)}
                            placeholder="Nhập địa chỉ của bạn"
                            rows={3}
                          />
                        </div>
                        <div className="form-actions">
                          <button
                            className="cancel-address-btn"
                            onClick={() => {
                              setIsEditingAddress(false);
                              setAddress(user?.address || "");
                              setName(user?.name || "");
                              setPhoneNumber(user?.phone_number || "");
                            }}
                          >
                            Hủy
                          </button>
                          <button className="save-address-btn" onClick={handleUpdateAddress}>
                            Hoàn tất
                          </button>
                        </div>
                      </div>
                    ) : user?.address ? (
                      <div className="address-card">
                        <div className="address-actions">
                          <button className="edit-btn" onClick={() => setIsEditingAddress(true)}>
                            Cập nhật
                          </button>
                        </div>
                        <h3 className="address-heading">
                          {user.name} (+84) {user.phone_number}
                        </h3>
                        <p className="address-details">{user.address}</p>
                      </div>
                    ) : (
                      <div className="address-empty">
                        <p>Chưa có địa chỉ nào được thêm.</p>
                        <button className="add-address-btn" onClick={() => setIsEditingAddress(true)}>
                          <span>+</span> Thêm địa chỉ mới
                        </button>
                      </div>
                    )}
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
                    ) : (
                      <>
                        {allOrders.find((o) => String(o.id) === selectedOrderId) ? (
                          <OrderDetail
                            order={allOrders.find((o) => String(o.id) === selectedOrderId)!}
                          />
                        ) : (
                          <div>Không tìm thấy đơn hàng với ID: {selectedOrderId}</div>
                        )}
                        <div className="back-link" onClick={() => setActiveTab("orders")}>
                          {"<<"} Quay lại đơn hàng của tôi
                        </div>
                      </>
                    )}
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