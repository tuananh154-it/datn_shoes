// import { Outlet } from "react-router-dom"
// import Header from "./Header"
// import Footer from "./Footer"
// import { useEffect, useState } from "react"
// import { getAllOrders } from "../services/Orders"

// const Layout = () => {
//   const [order,setOrder]= useState([])
//   useEffect(()=>{
//     getAllOrders().then(({data})=>{
//       setOrder(data)
//     })
//   })
//   return (
//     <div>
//         <Header/>
//         <Outlet/>
//         <Footer/>
//     </div>
//   )
// }

// export default Layout

import { Outlet } from "react-router-dom"
import Header from "./Header"
import Footer from "./Footer"
import { useEffect, useState } from "react"
import { getAllOrders, Order } from "../services/Orders"


const Layout = () => {
  
  const [order, setOrder] = useState<Order[]>([])
  const [showNotification, setShowNotification] = useState<boolean>(false)

  useEffect(() => {
    getAllOrders().then(({ data }) => {
      setOrder(data)
    })

    // Set up an interval or socket listener for new orders if required
    const interval = setInterval(() => {
      getAllOrders().then(({ data }) => {
        if (data.length > order.length) {
          const newOrder = data[data.length - 1]
          setOrder(data)
          setShowNotification(true) // Hiển thị thông báo khi có đơn hàng mới

          // Tắt thông báo sau 5 giây
          setTimeout(() => {
            setShowNotification(false)
          }, 5000)
        }
      })
    }, 5000); // Check for new orders every 5 seconds

    return () => clearInterval(interval) // Clean up the interval when the component is unmounted
  }, [order])

  // RealTimeNotification Component
  const RealTimeNotification = ({ order }: { order: Order }) => {
    return (
      <div
        style={{
          position: 'fixed',
          bottom: '20px',
          left: '20px',
          backgroundColor: '#FF9800',
          color: 'white',
          padding: '12px 20px',
          borderRadius: '8px',
          boxShadow: '0 4px 10px rgba(0, 0, 0, 0.2)',
          zIndex: '1000',
          minWidth: '250px',
          fontSize: '14px',
          animation: 'fadeIn 0.5s ease-out, fadeOut 0.5s ease-out 4.5s',
        }}
      >
        <strong>{order.username}</strong><br />
        Đơn hàng #{order.id} của {order.username} vừa được đặt với tổng giá trị{" "}
        {parseFloat(order.total_price).toLocaleString()} VND
      </div>
    )
  }

  return (
    <div>
      <Header />
      <Outlet />
      {showNotification && order.length > 0 && <RealTimeNotification order={order[order.length - 1]} />}
      <Footer />
    </div>
  )
}

export default Layout;
