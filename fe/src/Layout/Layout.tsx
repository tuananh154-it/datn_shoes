import { Outlet } from "react-router-dom";
import Header from "./Header";
import Footer from "./Footer";
import { useEffect, useState } from "react";
import Pusher from "pusher-js";

// Định nghĩa kiểu dữ liệu cho Order
interface Order {
  id: number;
  username: string;
  voucher_id: number | null;
  status: string;
  deliver_fee: string;
  user_id: number;
  payment_status: string;
  payment_method: string;
  address: string;
  phone_number: string;
  email: string;
  total_price: string;
  note: string;
  created_at: string;
  updated_at: string;
  deleted_at: string | null;
  voucher: any;
}

const Layout = () => {
  const [newOrder, setNewOrder] = useState<Order | null>(null);
  const [showNotification, setShowNotification] = useState<boolean>(false);

  useEffect(() => {
    // Kết nối Pusher
    const pusher = new Pusher("ee494af10a7f4a6e48b6", {
      cluster: "mt1",
      encrypted: true,
    });

    const channel = pusher.subscribe("orders");

    // Lắng nghe sự kiện order.placed
    channel.bind("order.placed", (data: Order) => {
      console.log("Received order.placed event:", data); // Debug log
      if (data) {
        setNewOrder(data); // Dữ liệu là đơn hàng trực tiếp
        setShowNotification(true);

        setTimeout(() => {
          setShowNotification(false);
        }, 10000);
      } else {
        console.warn("Dữ liệu từ Pusher không hợp lệ:", data);
      }
    });

    // Cleanup khi component unmount
    return () => {
      channel.unbind_all();
      channel.unsubscribe();
      pusher.disconnect();
    };
  }, []);

  // RealTimeNotification Component
  const RealTimeNotification = ({ order }: { order: Order }) => {
    return (
      <div
        style={{
          position: "fixed",
          bottom: "20px",
          left: "20px",
          backgroundColor: "#86ca30",
          color: "white",
          padding: "10px 16px",
          borderRadius: "6px",
          boxShadow: "0 3px 8px rgba(0, 0, 0, 0.15)",
          zIndex: "1000",
          minWidth: "220px",
          fontSize: "13px",
          lineHeight: "1.4",
          display: "flex",
          alignItems: "center",
          gap: "8px",
          animation: "fadeIn 0.5s ease-out, fadeOut 0.5s ease-out 4.5s",
        }}
      >
        <span style={{ fontSize: "16px", marginRight: "4px" }}>📦</span>
        <strong>{order.username}</strong>
        <br />
        <span>
          Đơn #{order.id} vừa được đặt<br />
          Tổng: {parseFloat(order.total_price).toLocaleString()} VND
        </span>
      </div>
    );
  };

  return (
    <div>
      <Header />
      <Outlet />
      {showNotification && newOrder && <RealTimeNotification order={newOrder} />}
      <Footer />
    </div>
  );
};

export default Layout;