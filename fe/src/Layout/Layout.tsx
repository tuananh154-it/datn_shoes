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
          backgroundColor: "#FF9800",
          color: "white",
          padding: "12px 20px",
          borderRadius: "8px",
          boxShadow: "0 4px 10px rgba(0, 0, 0, 0.2)",
          zIndex: "1000",
          minWidth: "250px",
          fontSize: "14px",
          animation: "fadeIn 0.5s ease-out, fadeOut 0.5s ease-out 4.5s",
        }}
      >
        <strong>{order.username}</strong>
        Đơn hàng #{order.id} của {order.username} vừa được đặt với tổng giá trị{" "}
        {parseFloat(order.total_price).toLocaleString()} VND
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