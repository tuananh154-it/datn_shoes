// import { useEffect, useState } from "react";
// import { getAllOrders, getDetailOrder, Order } from "../services/Orders";
// import toast from "react-hot-toast";
// import { Link, useParams } from "react-router-dom";

// const OrderPage = () => {
//     const [orders, setOrders] = useState<Order[]>([]);
//     const [loading, setLoading] = useState(true);
//     const [error, setError] = useState("");

//     // useEffect(() => {
//     //     const fetchOrders = async () => {
//     //         try {
//     //             const response = await getAllOrders();
//     //             setOrders(response.data);
//     //             setLoading(false);
//     //             console.log("Dữ liệu đơn hàng:", response.data);
//     //             console.log("order_detail", response.data?.order_details?.quantity);
//     //         } catch (err) {
//     //             console.error("Lỗi khi lấy đơn hàng:", err);
//     //             setError("Không thể lấy danh sách đơn hàng.");
//     //             toast.error("Lỗi khi lấy đơn hàng");
//     //             setLoading(false);
//     //         }
//     //     };

//     //     fetchOrders();
//     // }, []);
//     useEffect(()=>{
//         getAllOrders()
//         .then(({data})=>{
//             setOrders(data)
//             console.log(data);
//             setLoading(false);
//         })
//         .catch (err) 
//                         console.error("Lỗi khi lấy đơn hàng:", err);
//                         setError("Không thể lấy danh sách đơn hàng.");
//                         toast.error("Lỗi khi lấy đơn hàng");
//                         setLoading(false);
                    
//     },[])

    
//     if (loading) return <p>Đang tải...</p>;
//     if (error) return <p className="error">{error}</p>;

//     return (
//         <div className="orders-container">
//             <h2>Danh sách đơn hàng</h2>
//             {orders.length > 0 ? (
//                 <div className="order-list">

//                     {orders.map((order) => (
//                         <div key={order.id} className="order-card">
//                             <h3>Đơn #{order.id}</h3>
//                             <p><strong>Người nhận:</strong> {order.username}</p>
//                             <p><strong>Địa chỉ:</strong> {order.address}</p>
//                             <p><strong>Điện thoại:</strong> {order.phone_number}</p>
//                             <p><strong>Trạng thái:</strong> {order.status}</p>
//                             <p><strong>Tổng tiền:</strong> ${parseFloat(order.total_price).toFixed(2)}</p>

//                             <button className="details-btn">Xem chi tiết</button>
//                             {/* {order.order_details.map((item) => (
//                                 <div key={item.id} className="order-item">
//                                     <div>

//                                         <span className="item-quantity"> x{item.quantity}</span>
//                                     </div>
                                   
//                                 </div>
//                             ))} */}
//                         </div>

//                     ))}
//                 </div>
//             ) : (
//                 <p>Chưa có đơn hàng nào.</p>
//             )}
//         </div>
//     );
// };

// export default OrderPage;