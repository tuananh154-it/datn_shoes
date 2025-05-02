import { Outlet } from "react-router-dom";
import Header from "./Header";
import Footer from "./Footer";
import { useEffect, useState } from "react";
import Pusher from "pusher-js";

// Định nghĩa kiểu dữ liệu cho Order
interface Order {
  id: number;
  username: string;
  total_price: string;
  status: string;
  created_at: string;
  updated_at: string;
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
      console.log("Received order.placed event:", data);
      if (data && data.status === "pending") {
        // Chỉ hiển thị thông báo cho đơn hàng mới (status: pending)
        setNewOrder(data);
        setShowNotification(true);

        setTimeout(() => {
          setShowNotification(false);
        }, 10000);
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
          Đơn #FV-HN-{order.id} vừa được đặt<br />
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