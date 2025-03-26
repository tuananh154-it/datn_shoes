

// import { useState } from "react"
// import { User, Package, CreditCard, Settings, Home } from "lucide-react"
// import { Link } from "react-router-dom"

// // Mock order data
// const mockOrders = [
//   {
//     id: "ORD-12345",
//     date: "March 15, 2024",
//     status: "Delivered",
//     total: 1299.97,
//     items: [
//       { id: "1", name: "Modern Sofa", price: 899.99, quantity: 1 },
//       { id: "2", name: "Wooden Coffee Table", price: 299.99, quantity: 1 },
//       { id: "7", name: "Bedside Table", price: 129.99, quantity: 1 },
//     ],
//   },
//   {
//     id: "ORD-12344",
//     date: "February 28, 2024",
//     status: "Delivered",
//     total: 449.99,
//     items: [{ id: "9", name: "Standing Desk", price: 449.99, quantity: 1 }],
//   },
// ]

// // Mock user data
// const mockUser = {
//   name: "John Doe",
//   email: "john.doe@example.com",
//   phone: "(123) 456-7890",
//   address: "123 Furniture Lane, Design District, CA 90210",
// }

// export default function MyAccount() {
//   const [activeTab, setActiveTab] = useState("profile")

//   return (
//     <main className="container mx-auto px-4 py-8 nav">
//       <h1 className="text-3xl font-bold mb-8">My Account</h1>

//       <div className="grid md:grid-cols-4 gap-8">
//         {/* Sidebar */}
//         <aside>
//         <div className="md:col-span-1">
//           <div className="bg-white rounded-lg shadow-sm p-6">
//             <div className="flex flex-col items-center mb-6">
//               <div className="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mb-3">
//                 <User className="w-10 h-10 text-primary" />
//               </div>
//               <h2 className="text-xl font-bold">{mockUser.name}</h2>
//               <p className="text-gray-500 text-sm">{mockUser.email}</p>
//             </div>

//             <nav className="space-y-1">
//               <button
//                 onClick={() => setActiveTab("profile")}
//                 className={`w-full flex items-center px-4 py-2 rounded-md ${
//                   activeTab === "profile" ? "bg-primary text-white" : "text-gray-700 hover:bg-gray-100"
//                 }`}
//               >
//                 <User className="w-5 h-5 mr-3" />
//                 Profile
//               </button>
//               <button
//                 onClick={() => setActiveTab("orders")}
//                 className={`w-full flex items-center px-4 py-2 rounded-md ${
//                   activeTab === "orders" ? "bg-primary text-white" : "text-gray-700 hover:bg-gray-100"
//                 }`}
//               >
//                 <Package className="w-5 h-5 mr-3" />
//                 Orders
//               </button>
//               <button
//                 onClick={() => setActiveTab("addresses")}
//                 className={`w-full flex items-center px-4 py-2 rounded-md ${
//                   activeTab === "addresses" ? "bg-primary text-white" : "text-gray-700 hover:bg-gray-100"
//                 }`}
//               >
//                 <Home className="w-5 h-5 mr-3" />
//                 Addresses
//               </button>
//               <button
//                 onClick={() => setActiveTab("payment")}
//                 className={`w-full flex items-center px-4 py-2 rounded-md ${
//                   activeTab === "payment" ? "bg-primary text-white" : "text-gray-700 hover:bg-gray-100"
//                 }`}
//               >
//                 <CreditCard className="w-5 h-5 mr-3" />
//                 Payment Methods
//               </button>
//               <button
//                 onClick={() => setActiveTab("settings")}
//                 className={`w-full flex items-center px-4 py-2 rounded-md ${
//                   activeTab === "settings" ? "bg-primary text-white" : "text-gray-700 hover:bg-gray-100"
//                 }`}
//               >
//                 <Settings className="w-5 h-5 mr-3" />
//                 Settings
//               </button>
//             </nav>
//           </div>
//         </div>
//         </aside>


//         {/* Main Content */}
//         <div className="md:col-span-3">
//           <div className="bg-white rounded-lg shadow-sm p-6">
//             {/* Profile Tab */}
//             {activeTab === "profile" && (
//               <div>
//                 <h2 className="text-2xl font-bold mb-6">Profile Information</h2>
//                 <div className="space-y-6">
//                   <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
//                     <div>
//                       <label className="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
//                       <input
//                         type="text"
//                         defaultValue={mockUser.name}
//                         className="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
//                       />
//                     </div>
//                     <div>
//                       <label className="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
//                       <input
//                         type="email"
//                         defaultValue={mockUser.email}
//                         className="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
//                       />
//                     </div>
//                     <div>
//                       <label className="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
//                       <input
//                         type="tel"
//                         defaultValue={mockUser.phone}
//                         className="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
//                       />
//                     </div>
//                   </div>
//                   <div>
//                     <h3 className="text-lg font-medium mb-3 mt-6">Change Password</h3>
//                     <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
//                       <div>
//                         <label className="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
//                         <input
//                           type="password"
//                           className="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
//                         />
//                       </div>
//                       <div>
//                         <label className="block text-sm font-medium text-gray-700 mb-1">New Password</label>
//                         <input
//                           type="password"
//                           className="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
//                         />
//                       </div>
//                       <div>
//                         <label className="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
//                         <input
//                           type="password"
//                           className="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
//                         />
//                       </div>
//                     </div>
//                   </div>
//                   <div className="pt-4">
//                     <button className="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary/90 transition">
//                       Save Changes
//                     </button>
//                   </div>
//                 </div>
//               </div>
//             )}

//             {/* Orders Tab */}
//             {activeTab === "orders" && (
//               <div>
//                 <h2 className="text-2xl font-bold mb-6">Order History</h2>
//                 {mockOrders.length > 0 ? (
//                   <div className="space-y-6">
//                     {mockOrders.map((order) => (
//                       <div key={order.id} className="border border-gray-200 rounded-lg p-4">
//                         <div className="flex flex-wrap justify-between items-center mb-4">
//                           <div>
//                             <h3 className="font-bold">Order #{order.id}</h3>
//                             <p className="text-sm text-gray-500">Placed on {order.date}</p>
//                           </div>
//                           <div className="flex items-center">
//                             <span
//                               className={`px-3 py-1 rounded-full text-xs font-medium ${
//                                 order.status === "Delivered"
//                                   ? "bg-green-100 text-green-800"
//                                   : order.status === "Processing"
//                                     ? "bg-blue-100 text-blue-800"
//                                     : "bg-yellow-100 text-yellow-800"
//                               }`}
//                             >
//                               {order.status}
//                             </span>
//                             <button className="ml-4 text-primary hover:underline text-sm">View Details</button>
//                           </div>
//                         </div>
//                         <div className="border-t border-gray-200 pt-4">
//                           <div className="space-y-3">
//                             {order.items.map((item) => (
//                               <div key={item.id} className="flex justify-between">
//                                 <div>
//                                   <span className="font-medium">{item.name}</span>
//                                   <span className="text-gray-500 ml-2">x{item.quantity}</span>
//                                 </div>
//                                 <span>${(item.price * item.quantity).toFixed(2)}</span>
//                               </div>
//                             ))}
//                           </div>
//                           <div className="border-t border-gray-200 mt-4 pt-4 flex justify-between font-bold">
//                             <span>Total</span>
//                             <span>${order.total.toFixed(2)}</span>
//                           </div>
//                         </div>
//                       </div>
//                     ))}
//                   </div>
//                 ) : (
//                   <div className="text-center py-12">
//                     <Package className="w-16 h-16 mx-auto text-gray-300 mb-4" />
//                     <h3 className="text-xl font-medium mb-2">No Orders Yet</h3>
//                     <p className="text-gray-600 mb-4">You haven't placed any orders yet.</p>
//                     <Link to="/products" className="text-primary hover:underline">
//                       Start Shopping
//                     </Link>
//                   </div>
//                 )}
//               </div>
//             )}

//             {/* Addresses Tab */}
//             {activeTab === "addresses" && (
//               <div>
//                 <h2 className="text-2xl font-bold mb-6">My Addresses</h2>
//                 <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
//                   <div className="border border-gray-200 rounded-lg p-4 relative">
//                     <div className="absolute top-4 right-4 space-x-2">
//                       <button className="text-gray-500 hover:text-primary">Edit</button>
//                       <button className="text-red-500 hover:text-red-700">Delete</button>
//                     </div>
//                     <h3 className="font-bold mb-2">Default Address</h3>
//                     <p className="text-gray-700">
//                       {mockUser.name}
//                       <br />
//                       {mockUser.address}
//                       <br />
//                       {mockUser.phone}
//                     </p>
//                   </div>
//                   <div className="border border-dashed border-gray-300 rounded-lg p-4 flex items-center justify-center">
//                     <button className="text-primary hover:underline flex items-center">
//                       <span className="text-2xl mr-2">+</span> Add New Address
//                     </button>
//                   </div>
//                 </div>
//               </div>
//             )}

//             {/* Payment Methods Tab */}
//             {activeTab === "payment" && (
//               <div>
//                 <h2 className="text-2xl font-bold mb-6">Payment Methods</h2>
//                 <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
//                   <div className="border border-gray-200 rounded-lg p-4 relative">
//                     <div className="absolute top-4 right-4 space-x-2">
//                       <button className="text-gray-500 hover:text-primary">Edit</button>
//                       <button className="text-red-500 hover:text-red-700">Delete</button>
//                     </div>
//                     <h3 className="font-bold mb-2">Credit Card</h3>
//                     <div className="flex items-center">
//                       <div className="w-10 h-6 bg-blue-600 rounded mr-2"></div>
//                       <p className="text-gray-700">
//                         **** **** **** 4567
//                         <br />
//                         <span className="text-sm text-gray-500">Expires 05/25</span>
//                       </p>
//                     </div>
//                   </div>
//                   <div className="border border-dashed border-gray-300 rounded-lg p-4 flex items-center justify-center">
//                     <button className="text-primary hover:underline flex items-center">
//                       <span className="text-2xl mr-2">+</span> Add New Payment Method
//                     </button>
//                   </div>
//                 </div>
//               </div>
//             )}

//             {/* Settings Tab */}
//             {activeTab === "settings" && (
//               <div>
//                 <h2 className="text-2xl font-bold mb-6">Account Settings</h2>
//                 <div className="space-y-6">
//                   <div>
//                     <h3 className="text-lg font-medium mb-3">Notifications</h3>
//                     <div className="space-y-3">
//                       <div className="flex items-center">
//                         <input type="checkbox" id="order-updates" defaultChecked className="mr-3" />
//                         <label htmlFor="order-updates">Order updates and shipping notifications</label>
//                       </div>
//                       <div className="flex items-center">
//                         <input type="checkbox" id="promotions" defaultChecked className="mr-3" />
//                         <label htmlFor="promotions">Promotions and special offers</label>
//                       </div>
//                       <div className="flex items-center">
//                         <input type="checkbox" id="newsletter" defaultChecked className="mr-3" />
//                         <label htmlFor="newsletter">Newsletter</label>
//                       </div>
//                     </div>
//                   </div>
//                   <div>
//                     <h3 className="text-lg font-medium mb-3">Privacy</h3>
//                     <div className="space-y-3">
//                       <div className="flex items-center">
//                         <input type="checkbox" id="data-sharing" className="mr-3" />
//                         <label htmlFor="data-sharing">Share my purchase history for personalized recommendations</label>
//                       </div>
//                     </div>
//                   </div>
//                   <div className="pt-4">
//                     <button className="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary/90 transition">
//                       Save Preferences
//                     </button>
//                   </div>
//                   <div className="border-t border-gray-200 pt-6 mt-6">
//                     <h3 className="text-lg font-medium text-red-600 mb-3">Danger Zone</h3>
//                     <button className="text-red-600 border border-red-600 px-6 py-2 rounded-md hover:bg-red-50 transition">
//                       Delete Account
//                     </button>
//                   </div>
//                 </div>
//               </div>
//             )}
//           </div>
//         </div>
//       </div>
//     </main>
//   )
// }


// giao dien myaccount

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

    return (
        <main className="container">
            <div className="menu_overlay"></div>
            <div className="grid-container nav">
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
                                                        <button className="order-details">Chi tiết</button>
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
    );
};

export default MyAccount;
