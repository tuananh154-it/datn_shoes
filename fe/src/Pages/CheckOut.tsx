// import { useEffect, useState } from "react";
// import { getCheckOut, getOrder, Momopayment } from "../services/Order";
// import toast from "react-hot-toast";
// import { useNavigate } from "react-router-dom";
// import {  FaMoneyBillWave, FaMobileAlt } from "react-icons/fa";
// // import { string } from "zod";
// import { AxiosError } from "axios";
// import { api } from "../config/axios";
// interface Address {
//   _id: string;
//   name: string;
//   slug: string;
//   type: string;
//   name_with_type: string;
//   code: number;
// }

// interface CartItem {
//   id: number;
//   product_name: string;
//   image: string; // JSON string chứa danh sách ảnh
//   size: string;
//   color: string;
//   price: string;
//   quantity: number;
// }

// interface User {
//   id: number;
//   name: string;
//   email: string;
//   phone_number: string | null;
//   address: string | null;
//   date_of_birth: string | null;
//   gender: string | null;
// }

// interface CheckoutData {
//   cart_items: CartItem[];
//   deliver_fee: number;
//   discount: number;
//   subtotal: number;
//   total: number;
//   user: User;
//   voucher: string | null;
//   note: string;
//   selected_items: CartItem[]; // Add this property to the interface
// }
// const CheckOut = () => {
//   const [provinces, setProvinces] = useState<Address[]>([]);
//   const [districts, setDistricts] = useState<Address[]>([]);
//   const [wards, setWards] = useState<Address[]>([]);

//   const [selectedProvince, setSelectedProvince] = useState<string>("");
//   const [selectedDistrict, setSelectedDistrict] = useState<string>("");
//   const [selectedWard, setSelectedWard] = useState<string>("");

//   // const [address, setAddress] = useState<string>("");
//   // const [note, setNote] = useState<string>("");
//   // const [voucher, setVoucher] = useState<string>("");
//   const [paymentMethod, setPaymentMethod] = useState<string>("COD");
//   // const [phone, setPhone] = useState<string>("");

//   const [checkout, setCheckout] = useState<CheckoutData | null>(null);
//   const [selectedItems, setSelectedItems] = useState<number[]>([]);
//   console.log("checkout", checkout);
//   useEffect(() => {
//     const storedSelectedItems = localStorage.getItem("selectedItems");
//     if (storedSelectedItems) {
//       const parsedItems = JSON.parse(storedSelectedItems);
//       setSelectedItems(parsedItems);
//     }
//   }, []);
//   console.log("checkout", checkout);

//   // Fetch checkout data when selectedItems are updated
//   useEffect(() => {
//     if (selectedItems.length > 0) {
//       const userId = 1; // Replace with dynamic userId from state or context

//       getCheckOut(userId, selectedItems)
//         .then(({ data }) => {
//           setCheckout(data);
//         })
//         .catch((error) => {
//           console.error("API Error:", error);
//           toast.error("Failed to load checkout data.");
//         });
//     }
//   }, [selectedItems]);
//   const nav = useNavigate();
//   const handleOrder = async (e: React.FormEvent) => {
//     e.preventDefault();

//     if (!checkout) {
//       alert("Không có dữ liệu đơn hàng!");
//       return;
//     }
//     if (paymentMethod === "momo" && !agreeTerms) {
//       setErrorMessage("⚠️ Bạn chưa chọn điều khoản thanh toán❌");
//       return;
//     }
//     // Handle MoMo payment
//     if (paymentMethod === "momo") {
//       const uniqueOrderId = `ORDER_${new Date().getTime()}`;

//       const momoData = {
//         amount: checkout.total,
//         orderId: uniqueOrderId, // Temporary order ID
//         redirectUrl: window.location.origin + "/momo-success", // MoMo success redirect URL
//         username: checkout.user.name, // Username field
//         address: checkout.user.address, // Address field
//         email: checkout.user.email, // Email field
//         phone_number: checkout.user.phone_number, // Phone number field
//         selected_items: checkout.selected_items.map((item) => item.id), // List of selected item IDs
//       };

//       console.log("📦 Dữ liệu gửi API MoMo:", momoData);

//       try {
//         // Call MoMo API to initiate payment
//         const momoResponse = await api.post("/momo-payment", momoData);
//         console.log("✅ MoMo API Response:", momoResponse.data);

//         // Check if payment URL is returned from MoMo
//         if (momoResponse.data?.payUrl) {
//           localStorage.setItem("pendingOrder", JSON.stringify(checkout)); // Save pending order
//           window.location.href = momoResponse.data.payUrl; // Redirect to MoMo payment
//         } else {
//           alert("⚠️ Không nhận được URL thanh toán từ MoMo!");
//         }
//       } catch (error) {
//         if (error instanceof AxiosError) {
//           console.error("Lỗi từ MoMo API:", error.response?.data);
//           alert("Lỗi khi gọi MoMo API!");
//         } else {
//           console.error("Lỗi kết nối với MoMo:", (error as Error).message);
//           alert("Lỗi kết nối với MoMo!");
//         }
//       }
//     }

//     // Handle Cash on Delivery
//     if (paymentMethod === "cash_on_delivery") {
//       processOrder();
//     }

//     // Save address to localStorage if it exists
//     if (checkout?.user.address) {
//       const storedAddresses = JSON.parse(
//         localStorage.getItem("savedAddresses") || "[]"
//       );

//       // Update the addresses in localStorage, ensuring no duplicates and a maximum of 2 addresses
//       const updatedAddresses = [checkout.user.address, ...storedAddresses]
//         .filter((addr, index, self) => self.indexOf(addr) === index) // Remove duplicates
//         .slice(0, 2); // Keep a maximum of 2 addresses

//       localStorage.setItem("savedAddresses", JSON.stringify(updatedAddresses));
//     }
//   };

//   // Simulate delay for API response (useful for testing purposes)
//   const delay = (ms: number) =>
//     new Promise((resolve) => setTimeout(resolve, ms));

//   // Process the order if Cash on Delivery is selected
//   const processOrder = async () => {
//     const savedOrder = localStorage.getItem("pendingOrder");

//     if (!checkout) {
//       console.error("Checkout data is missing.");
//       alert("Không có dữ liệu đơn hàng!");
//       return;
//     }

//     console.log("Checkout Data:", checkout);

//     // Prepare order data
//     const orderData = {
//       user_id: checkout?.user.id,
//       username: checkout?.user.name,
//       phone_number: checkout?.user.phone_number,
//       email: checkout?.user.email,
//       address: checkout?.user.address,
//       note: (document.getElementById("note") as HTMLInputElement)?.value || "",
//       payment_method: paymentMethod,
//       selected_items: (checkout?.selected_items || []).map((item) => item.id),
//       voucher_id: checkout?.voucher || null,
//     };

//     console.log("🚀 Sending Order Data:", orderData);

//     try {
//       await delay(2000); // Simulate a delay

//       // Call API to process the order
//       const orderResponse = await getOrder(orderData);

//       console.log("✅ Order API Response:", orderResponse);

//       if (orderResponse.status !== 201) {
//         console.error(`Order failed with status: ${orderResponse.status}`);
//         alert(`Đặt hàng thất bại! Mã lỗi: ${orderResponse.status}`);
//         return;
//       }

//       // Handle successful order
//       toast.success("🎉 Đã đặt hàng thành công!");
//       localStorage.removeItem("pendingOrder"); // Clear pending order
//       nav("/"); // Redirect to homepage or order confirmation page
//     } catch (error: unknown) {
//       if (error instanceof AxiosError) {
//         const errorMessage = error.response?.data?.message || "Có lỗi xảy ra!";
//         if (errorMessage.includes("Too Many Attempts")) {
//           alert("Bạn đã thử quá nhiều lần, vui lòng chờ một chút rồi thử lại!");
//         } else {
//           alert(errorMessage);
//         }
//       } else {
//         console.error("❌ Unexpected error:", error);
//         alert("Có lỗi xảy ra, vui lòng thử lại!");
//       }
//     }
//   };
//   const applyVoucher = async () => {
//     const code = checkout?.voucher;
//     if (!code) {
//       alert('Vui lòng nhập mã giảm giá');
//       return;
//     }

//     try {
//       // Gọi API getVoucherById() bằng axios
//       const response = await api.get(`/api/vouchers/id`, {
//         params: { id: code },
//       });

//       const data = response.data;

//       if (data) {
//         const {
//           discount_percent,
//           discount_amount,
//           max_discount_amount,
//           min_purchase_amount,
//         } = data;

//         // Kiểm tra điều kiện voucher có thể áp dụng cho đơn hàng không
//         const totalPrice = checkout?.total || 0;
//         if (totalPrice < min_purchase_amount) {
//           alert(`Tổng đơn hàng phải lớn hơn ${min_purchase_amount.toLocaleString()}đ để áp dụng voucher`);
//           return;
//         }

//         // Tính toán giảm giá
//         let discount = 0;
//         if (discount_percent) {
//           discount = (totalPrice * discount_percent) / 100;
//         } else if (discount_amount) {
//           discount = discount_amount;
//         }

//         // Áp dụng giới hạn giảm giá tối đa
//         if (max_discount_amount && discount > max_discount_amount) {
//           discount = max_discount_amount;
//         }

//         // Cập nhật lại checkout với voucher và giảm giá
//         setCheckout((prev) => 
//           prev ? { 
//             ...prev, 
//             voucher: code, 
//             discount: discount 
//           } : null
//         );

//         alert(`Voucher '${code}' đã được áp dụng! Giảm giá: ${discount.toLocaleString()}đ`);
//       } else {
//         alert('Voucher không hợp lệ hoặc đã hết hạn');
//       }
//     } catch (err) {
//       console.error("Lỗi khi kiểm tra voucher:", err);
//       alert('Lỗi khi áp dụng voucher');
//     }
//   };
//   // Kiểm tra nếu MoMo thanh toán xong
//   useEffect(() => {
//     const urlParams = new URLSearchParams(window.location.search);
//     const momoStatus = urlParams.get("momoStatus");

//     if (momoStatus === "success") {
//       processOrder()
//         .then(() => {
//           toast.success("🎉 Đơn hàng của bạn đã được đặt thành công!");
//         })
//         .catch(() => {
//           toast.error("❌ Đặt hàng thất bại, vui lòng thử lại!");
//         });
//     } // Đặt hàng sau khi thanh toán MoMo thành công
//   }, []);

//   useEffect(() => {
//     fetch(`https://vn-public-apis.fpo.vn/provinces/getAll?limit=-1`)
//       .then((res) => res.json())
//       .then((data) => {
//         setProvinces(data.data.data);
//       })
//       .catch((error) => console.error("Lỗi khi tải tỉnh/thành phố:", error));
//   }, []);

//   useEffect(() => {
//     if (selectedProvince) {
//       fetch(
//         `https://vn-public-apis.fpo.vn/districts/getByProvince?provinceCode=${selectedProvince}&limit=-1`
//       )
//         .then((res) => res.json())
//         .then((data) => {
//           setDistricts(data.data.data);
//           setSelectedDistrict("");
//           setWards([]);
//           setSelectedWard("");
//         })
//         .catch((error) => console.error("Lỗi khi tải huyện:", error));
//     }
//   }, [selectedProvince]);

//   useEffect(() => {
//     if (selectedDistrict) {
//       fetch(
//         `https://vn-public-apis.fpo.vn/wards/getByDistrict?districtCode=${selectedDistrict}&limit=-1`
//       )
//         .then((res) => res.json())
//         .then((data) => {
//           setWards(data.data.data);
//           setSelectedWard("");
//         })
//         .catch((error) => console.error("Lỗi khi tải xã/phường:", error));
//     }
//   }, [selectedDistrict]);
//   const [specificAddress, setSpecificAddress] = useState("");

//   const updateAddress = (newValue?: string) => {
//     setCheckout((prev) =>
//       prev
//         ? {
//             ...prev,
//             user: {
//               ...prev.user,
//               address: `${newValue || specificAddress}, ${
//                 wards.find((w) => String(w.code) === selectedWard)?.name || ""
//               }, ${
//                 districts.find((d) => String(d.code) === selectedDistrict)
//                   ?.name || ""
//               }, ${
//                 provinces.find((p) => String(p.code) === selectedProvince)
//                   ?.name || ""
//               }`,
//             },
//           }
//         : null
//     );
//   };
//   const [savedAddresses, setSavedAddresses] = useState<string[]>([]);

//   useEffect(() => {
//     const storedAddresses = JSON.parse(
//       localStorage.getItem("savedAddresses") || "[]"
//     );
//     setSavedAddresses(storedAddresses);
//   }, []);

//   const [agreeTerms, setAgreeTerms] = useState(false);
//   const [errorMessage, setErrorMessage] = useState("");
//   // const handleOrder = () => {
//   //   if (paymentMethod === "momo" && !agreeTerms) {
//   //     setErrorMessage("Bạn chưa chọn điều khoản thanh toán.");
//   //     return;
//   //   }
//   // };
//   return (
//     <>
//       <div className="menu_overlay"></div>
//       <div className="main_section">
//         {/* START Breadcrumb */}
//         <section className="breadcrumb_section nav">
//           <div className="container">
//             <nav aria-label="breadcrumb">
//               <ol className="breadcrumb">
//                 <li className="breadcrumb-item text-capitalize">
//                   <a href="earthyellow.html">Trang chủ</a>
//                   <i className="flaticon-arrows-4"></i>
//                 </li>
//                 <li className="breadcrumb-item active text-capitalize">
//                   Thanh toán
//                 </li>
//               </ol>
//             </nav>
//             <h1 className="title_h1 font-weight-normal text-capitalize">
//               Thanh toán
//             </h1>
//           </div>
//         </section>
//       </div>

//       <div className="checkout-container">
//         <div className="checkout-left">
//           <h2>Thanh toán & Vận chuyển</h2>

//           <form>
//             <label>Họ và tên *</label>
//             <input
//               type="text"
//               id="name"
//               value={checkout?.user.name || ""}
//               onChange={(e) =>
//                 setCheckout((prev) =>
//                   prev
//                     ? { ...prev, user: { ...prev.user, name: e.target.value } }
//                     : null
//                 )
//               }
//             />

//             <label>Số điện thoại *</label>
//             <input
//               type="text"
//               id="phone_number"
//               value={checkout?.user.phone_number || ""}
//               onChange={(e) =>
//                 setCheckout((prev) =>
//                   prev
//                     ? {
//                         ...prev,
//                         user: { ...prev.user, phone_number: e.target.value },
//                       }
//                     : null
//                 )
//               }
//             />

//             <label>Email *</label>
//             <input
//               type="email"
//               id="email"
//               value={checkout?.user.email || ""}
//               onChange={(e) =>
//                 setCheckout((prev) =>
//                   prev
//                     ? { ...prev, user: { ...prev.user, email: e.target.value } }
//                     : null
//                 )
//               }
//             />


//             <label>Tỉnh/Thành phố *</label>
//             <select
//               value={selectedProvince}
//               onChange={(e) => {
//                 setSelectedProvince(e.target.value);
//                 updateAddress();
//               }}
//             >
//               <option value="">Chọn tỉnh/thành phố</option>
//               {provinces?.map((p) => (
//                 <option key={p.code} value={p.code}>
//                   {p.name}
//                 </option>
//               ))}
//             </select>

//             <label>Quận/Huyện *</label>
//             <select
//               value={selectedDistrict}
//               onChange={(e) => {
//                 setSelectedDistrict(e.target.value);
//                 updateAddress();
//               }}
//               disabled={!selectedProvince}
//             >
//               <option value="">Chọn quận/huyện</option>
//               {districts?.map((d) => (
//                 <option key={d.code} value={d.code}>
//                   {d.name}
//                 </option>
//               ))}
//             </select>

//             <label>Phường/Xã *</label>
//             <select
//               value={selectedWard}
//               onChange={(e) => {
//                 setSelectedWard(e.target.value);
//                 updateAddress();
//               }}
//               disabled={!selectedDistrict}
//             >
//               <option value="">Chọn phường/xã</option>
//               {wards?.map((w) => (
//                 <option key={w.code} value={w.code}>
//                   {w.name}
//                 </option>
//               ))}
//             </select>

//             <label>Địa chỉ cụ thể *</label>
//             <input
//               type="text"
//               placeholder="Nhập số nhà, tên đường..."
//               value={specificAddress}
//               onChange={(e) => {
//                 setSpecificAddress(e.target.value); // Giữ nguyên chuỗi người dùng nhập
//                 updateAddress(e.target.value); // Cập nhật lại địa chỉ đầy đủ
//               }}
//             />

//             <label>Địa chỉ đầy đủ *</label>
//             <input
//               type="text"
//               id="address"
//               value={checkout?.user.address || ""}
//               onChange={(e) =>
//                 setCheckout((prev) =>
//                   prev
//                     ? {
//                         ...prev,
//                         user: { ...prev.user, address: e.target.value },
//                       }
//                     : null
//                 )
//               }
//             />
//             {savedAddresses.length > 0 && (
//               <div style={{ marginTop: "5px" }}>
//                 {savedAddresses.length > 0 && (
//                   <div style={{ marginTop: "5px" }}>
//                     {savedAddresses.map((addr, index) => (
//                       <p
//                         key={index}
//                         onClick={() =>
//                           setCheckout((prev) =>
//                             prev
//                               ? {
//                                   ...prev,
//                                   user: { ...prev.user, address: addr },
//                                 }
//                               : null
//                           )
//                         }
//                         style={{
//                           cursor: "pointer",
//                           color: "#888",
//                           marginBottom: "3px",
//                         }}
//                       >
//                         {addr}
//                       </p>
//                     ))}
//                   </div>
//                 )}
//               </div>
//             )}

//             <label>Ghi chú</label>
//             <input
//               type="text"
//               id="note"
//               value={checkout?.note || ""}
//               onChange={(e) =>
//                 setCheckout((prev) =>
//                   prev ? { ...prev, note: e.target.value } : null
//                 )
//               }
//             />
//           </form>
//         </div>
//         <div className="checkout-right">
//           <h2>Đơn hàng của bạn</h2>
//           {checkout?.selected_items && checkout.selected_items.length > 0 ? (
//             checkout.selected_items.map((item) => {
//               let images: string[] = [];

//               try {
//                 images =
//                   typeof item.image === "string" ? JSON.parse(item.image) : [];
//               } catch (error) {
//                 console.error(
//                   "❌ JSON.parse error:",
//                   error,
//                   "| Data:",
//                   item.image
//                 );
//                 images = [item.image]; // Giữ nguyên nếu không parse được
//               }

//               const productImage = images[0] || "fallback-image.jpg"; // Ảnh mặc định nếu không có ảnh hợp lệ

//               return (
//                 <div className="order-summary" key={item.id}>
//                   <div className="product">
//                     <img
//                       src={productImage}
//                       alt={item.product_name}
//                       className="product-image"
//                     />
//                     <div className="product-details">
//                       <p className="product-name">{item.product_name}</p>
//                       <p className="product-quantity">x{item.quantity}</p>
//                       <p className="product-price">
//                         {(
//                           parseFloat(item.price) * item.quantity
//                         ).toLocaleString()}
//                         đ
//                       </p>
//                     </div>
//                   </div>
//                   <hr />
//                   <div className="voucher-section">
//                     <label className="voucher-label">Mã giảm giá</label>
//                     <div className="voucher-input-wrapper">
//                       <input
//                         type="text"
//                         id="voucher"
//                         value={checkout?.voucher || ""}
//                         onChange={(e) =>  
//                           setCheckout((prev) =>
//                             prev ? { ...prev, voucher: e.target.value } : null
//                           )
//                         }
//                         className="voucher-input"
//                         placeholder="Nhập mã giảm giá"
//                       />
//                       <button className="voucher-button" onClick={applyVoucher}>Áp mã</button>
//                     </div>
//                   </div>
//                 </div>
//               );
//             })
//           ) : (
//             <p>No items in the cart.</p>
//           )}

//           <div className="price-details">
//             <p>
//               Tổng: <span>{checkout?.subtotal?.toLocaleString()}đ</span>
//             </p>
//             <p>
//               Phí ship: <span>{checkout?.deliver_fee?.toLocaleString()}đ</span>
//             </p>
//             <p className="total">
//               Tổng cộng: <strong>{checkout?.total?.toLocaleString()}đ</strong>
//             </p>
//           </div>

//           <div className="payment-method">
//             <label
//               className={`payment-card ${
//                 paymentMethod === "cash_on_delivery" ? "active" : ""
//               }`}
//             >
//               <input
//                 type="radio"
//                 name="payment"
//                 value="cash_on_delivery"
//                 checked={paymentMethod === "cash_on_delivery"}
//                 onChange={() => setPaymentMethod("cash_on_delivery")}
//               />
//               <FaMoneyBillWave className="payment-icon cod" />
//               <span>Thanh toán khi nhận hàng</span>
//             </label>

//             <label
//               className={`payment-card ${
//                 paymentMethod === "momo" ? "active" : ""
//               }`}
//             >
//               <input
//                 type="radio"
//                 name="payment"
//                 value="momo"
//                 checked={paymentMethod === "momo"}
//                 onChange={() => setPaymentMethod("momo")}
//               />
//               <FaMobileAlt className="payment-icon momo" />
//               <span>Thanh toán bằng Ví MoMo</span>
//             </label>
//             {paymentMethod === "momo" && (
//               <div className="terms-and-conditions">
//                 <label>
//                   <input
//                     type="checkbox"
//                     checked={agreeTerms}
//                     onChange={(e) => setAgreeTerms(e.target.checked)}
//                   />
//                   Tôi đồng ý với điều khoản thanh toán qua Ví MoMo.
//                 </label>
//                 {errorMessage && (
//                   <p className="error-message">{errorMessage}</p>
//                 )}
//               </div>
//             )}
//           </div>

//           <button type="submit" className="order-button" onClick={handleOrder}>
//             ĐẶT HÀNG
//           </button>
//         </div>
//       </div>
//     </>
//   );
// };

// export default CheckOut;
import { useEffect, useState } from "react";
import { getCheckOut, getOrder, Momopayment } from "../services/Order";
import toast from "react-hot-toast";
import { useNavigate } from "react-router-dom";
import { FaMoneyBillWave, FaMobileAlt } from "react-icons/fa";
// import { string } from "zod";
import { AxiosError } from "axios";
import { api } from "../config/axios";
interface Address {
  _id: string;
  name: string;
  slug: string;
  type: string;
  name_with_type: string;
  code: number;
}

interface CartItem {
  id: number;
  product_name: string;
  image: string; // JSON string chứa danh sách ảnh
  size: string;
  color: string;
  price: string;
  quantity: number;
}

interface User {
  id: number;
  name: string;
  email: string;
  phone_number: string | null;
  address: string | null;
  date_of_birth: string | null;
  gender: string | null;
}

interface CheckoutData {
  cart_items: CartItem[];
  deliver_fee: number;
  discount: number;
  subtotal: number;
  total: number;
  user: User;
  voucher: string | null;
  note: string;
  selected_items: CartItem[]; // Add this property to the interface
}
const CheckOut = () => {
  const [provinces, setProvinces] = useState<Address[]>([]);
  const [districts, setDistricts] = useState<Address[]>([]);
  const [wards, setWards] = useState<Address[]>([]);

  const [selectedProvince, setSelectedProvince] = useState<string>("");
  const [selectedDistrict, setSelectedDistrict] = useState<string>("");
  const [selectedWard, setSelectedWard] = useState<string>("");
  const [isLoading, setIsLoading] = useState(false);
  // const [address, setAddress] = useState<string>("");
  // const [note, setNote] = useState<string>("");
  // const [voucher, setVoucher] = useState<string>("");
  const [paymentMethod, setPaymentMethod] = useState<string>("COD");
  // const [phone, setPhone] = useState<string>("");

  const [checkout, setCheckout] = useState<CheckoutData | null>(null);
  const [selectedItems, setSelectedItems] = useState<number[]>([]);
  // console.log("checkout", checkout);
  useEffect(() => {
    const storedSelectedItems = localStorage.getItem("selectedItems");
    if (storedSelectedItems) {
      const parsedItems = JSON.parse(storedSelectedItems);
      setSelectedItems(parsedItems);
    }
  }, []);
  // console.log("checkout", checkout);

  // Fetch checkout data when selectedItems are updated
  useEffect(() => {
    if (selectedItems.length > 0) {
      const userId = 1; // Replace with dynamic userId from state or context

      getCheckOut(userId, selectedItems)
        .then(({ data }) => {
          setCheckout(data);
        })
        .catch((error) => {
          console.error("API Error:", error);
          toast.error("Failed to load checkout data.");
        });
    }
  }, [selectedItems]);
  const nav = useNavigate();
  const handleOrder = async (e: React.FormEvent) => {
    setIsLoading(true); // Bắt đầu loading
    e.preventDefault();

    if (!checkout) {
      alert("Không có dữ liệu đơn hàng!");
      return;
    }
    if (paymentMethod === "momo" && !agreeTerms) {
      setErrorMessage("⚠️ Bạn chưa chọn điều khoản thanh toán❌");
      return;
    }
    // Handle MoMo payment
    if (paymentMethod === "momo") {
      const uniqueOrderId = `ORDER_${new Date().getTime()}`;

      const momoData = {
        amount: checkout.total,
        orderId: uniqueOrderId, // Temporary order ID
        redirectUrl: window.location.origin + "/momo-success", // MoMo success redirect URL
        username: checkout.user.name, // Username field
        address: checkout.user.address, // Address field
        email: checkout.user.email, // Email field
        phone_number: checkout.user.phone_number, // Phone number field
        selected_items: checkout.selected_items.map((item) => item.id), // List of selected item IDs
      };

      console.log("📦 Dữ liệu gửi API MoMo:", momoData);

      try {
        // Call MoMo API to initiate payment
        const momoResponse = await api.post("/momo-payment", momoData);
        console.log("✅ MoMo API Response:", momoResponse.data);

        // Check if payment URL is returned from MoMo
        if (momoResponse.data?.payUrl) {
          localStorage.setItem("pendingOrder", JSON.stringify(checkout)); // Save pending order
          window.location.href = momoResponse.data.payUrl; // Redirect to MoMo payment
        } else {
          alert("⚠️ Không nhận được URL thanh toán từ MoMo!");
        }
      } catch (error) {
        if (error instanceof AxiosError) {
          console.error("Lỗi từ MoMo API:", error.response?.data);
          alert("Lỗi khi gọi MoMo API!");
        } else {
          console.error("Lỗi kết nối với MoMo:", (error as Error).message);
          alert("Lỗi kết nối với MoMo!");
        }
      } finally {
        setIsLoading(false); // Kết thúc loading
      }
    }

    // Handle Cash on Delivery
    if (paymentMethod === "cash_on_delivery") {
      processOrder();
    }

    // Save address to localStorage if it exists
    if (checkout?.user.address) {
      const storedAddresses = JSON.parse(
        localStorage.getItem("savedAddresses") || "[]"
      );

      // Update the addresses in localStorage, ensuring no duplicates and a maximum of 2 addresses
      const updatedAddresses = [checkout.user.address, ...storedAddresses]
        .filter((addr, index, self) => self.indexOf(addr) === index) // Remove duplicates
        .slice(0, 2); // Keep a maximum of 2 addresses

      localStorage.setItem("savedAddresses", JSON.stringify(updatedAddresses));
    }
  };

  // Simulate delay for API response (useful for testing purposes)
  const delay = (ms: number) =>
    new Promise((resolve) => setTimeout(resolve, ms));

  // Process the order if Cash on Delivery is selected
  const processOrder = async () => {
    const savedOrder = localStorage.getItem("pendingOrder");

    if (!checkout) {
      console.error("Checkout data is missing.");
      alert("Không có dữ liệu đơn hàng!");
      return;
    }

    console.log("Checkout Data:", checkout);

    // Prepare order data
    const orderData = {
      user_id: checkout?.user.id,
      username: checkout?.user.name,
      phone_number: checkout?.user.phone_number,
      email: checkout?.user.email,
      address: checkout?.user.address,
      note: (document.getElementById("note") as HTMLInputElement)?.value || "",
      payment_method: paymentMethod,
      selected_items: (checkout?.selected_items || []).map((item) => item.id),
      voucher_id: checkout?.voucher || null,
    };

    console.log("🚀 Sending Order Data:", orderData);

    try {
      await delay(2000); // Simulate a delay

      // Call API to process the order
      const orderResponse = await getOrder(orderData);

      console.log("✅ Order API Response:", orderResponse);

      if (orderResponse.status !== 201) {
        console.error(`Order failed with status: ${orderResponse.status}`);
        alert(`Đặt hàng thất bại! Mã lỗi: ${orderResponse.status}`);
        return;
      }

      // Handle successful order
      toast.success("🎉 Đã đặt hàng thành công!");
      localStorage.removeItem("pendingOrder"); // Clear pending order
      nav("/myaccout?tab=orders"); // Redirect to homepage or order confirmation page
    } catch (error: unknown) {
      if (error instanceof AxiosError) {
        const errorMessage = error.response?.data?.message || "Có lỗi xảy ra!";
        if (errorMessage.includes("Too Many Attempts")) {
          alert("Bạn đã thử quá nhiều lần, vui lòng chờ một chút rồi thử lại!");
        } else {
          alert(errorMessage);
        }
      } else {
        console.error("❌ Unexpected error:", error);
        alert("Có lỗi xảy ra, vui lòng thử lại!");
      }
    }
  };
  const applyVoucher = async () => {
    const code = checkout?.voucher;
    if (!code) {
      alert('Vui lòng nhập mã giảm giá');
      return;
    }

    try {
      // Gọi API getVoucherById() bằng axios
      const response = await api.get(`/api/vouchers/id`, {
        params: { id: code },
      });

      const data = response.data;

      if (data) {
        const {
          discount_percent,
          discount_amount,
          max_discount_amount,
          min_purchase_amount,
        } = data;

        // Kiểm tra điều kiện voucher có thể áp dụng cho đơn hàng không
        const totalPrice = checkout?.total || 0;
        if (totalPrice < min_purchase_amount) {
          alert(`Tổng đơn hàng phải lớn hơn ${min_purchase_amount.toLocaleString()}đ để áp dụng voucher`);
          return;
        }

        // Tính toán giảm giá
        let discount = 0;
        if (discount_percent) {
          discount = (totalPrice * discount_percent) / 100;
        } else if (discount_amount) {
          discount = discount_amount;
        }

        // Áp dụng giới hạn giảm giá tối đa
        if (max_discount_amount && discount > max_discount_amount) {
          discount = max_discount_amount;
        }

        // Cập nhật lại checkout với voucher và giảm giá
        setCheckout((prev) =>
          prev ? {
            ...prev,
            voucher: code,
            discount: discount
          } : null
        );

        alert(`Voucher '${code}' đã được áp dụng! Giảm giá: ${discount.toLocaleString()}đ`);
      } else {
        alert('Voucher không hợp lệ hoặc đã hết hạn');
      }
    } catch (err) {
      console.error("Lỗi khi kiểm tra voucher:", err);
      alert('Lỗi khi áp dụng voucher');
    }
  };
  // Kiểm tra nếu MoMo thanh toán xong
  useEffect(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const momoStatus = urlParams.get("momoStatus");

    if (momoStatus === "success") {
      processOrder()
        .then(() => {
          toast.success("🎉 Đơn hàng của bạn đã được đặt thành công!");
        })
        .catch(() => {
          toast.error("❌ Đặt hàng thất bại, vui lòng thử lại!");
        });
    } // Đặt hàng sau khi thanh toán MoMo thành công
  }, []);
  const [editingAddress, setEditingAddress] = useState(false);
  const [showMoMoTerms, setShowMoMoTerms] = useState(false);
  useEffect(() => {
    fetch(`https://vn-public-apis.fpo.vn/provinces/getAll?limit=-1`)
      .then((res) => res.json())
      .then((data) => {
        setProvinces(data.data.data);
      })
      .catch((error) => console.error("Lỗi khi tải tỉnh/thành phố:", error));
  }, []);

  useEffect(() => {
    if (selectedProvince) {
      fetch(
        `https://vn-public-apis.fpo.vn/districts/getByProvince?provinceCode=${selectedProvince}&limit=-1`
      )
        .then((res) => res.json())
        .then((data) => {
          setDistricts(data.data.data);
          setSelectedDistrict("");
          setWards([]);
          setSelectedWard("");
        })
        .catch((error) => console.error("Lỗi khi tải huyện:", error));
    }
  }, [selectedProvince]);

  useEffect(() => {
    if (selectedDistrict) {
      fetch(
        `https://vn-public-apis.fpo.vn/wards/getByDistrict?districtCode=${selectedDistrict}&limit=-1`
      )
        .then((res) => res.json())
        .then((data) => {
          setWards(data.data.data);
          setSelectedWard("");
        })
        .catch((error) => console.error("Lỗi khi tải xã/phường:", error));
    }
  }, [selectedDistrict]);
  const [specificAddress, setSpecificAddress] = useState("");

  const updateAddress = (newValue?: string) => {
    setCheckout((prev) =>
      prev
        ? {
          ...prev,
          user: {
            ...prev.user,
            address: `${newValue || specificAddress}, ${wards.find((w) => String(w.code) === selectedWard)?.name || ""
              }, ${districts.find((d) => String(d.code) === selectedDistrict)
                ?.name || ""
              }, ${provinces.find((p) => String(p.code) === selectedProvince)
                ?.name || ""
              }`,
          },
        }
        : null
    );
  };
  const [savedAddresses, setSavedAddresses] = useState<string[]>([]);

  useEffect(() => {
    const storedAddresses = JSON.parse(
      localStorage.getItem("savedAddresses") || "[]"
    );
    setSavedAddresses(storedAddresses);
  }, []);

  const [agreeTerms, setAgreeTerms] = useState(false);
  const [errorMessage, setErrorMessage] = useState("");
  // const handleOrder = () => {
  //   if (paymentMethod === "momo" && !agreeTerms) {
  //     setErrorMessage("Bạn chưa chọn điều khoản thanh toán.");
  //     return;
  //   }
  // };
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
                  <a href="earthyellow.html">Trang chủ</a>
                  <i className="flaticon-arrows-4"></i>
                </li>
                <li className="breadcrumb-item active text-capitalize">
                  Thanh toán
                </li>
              </ol>
            </nav>
            <h1 className="title_h1 font-weight-normal text-capitalize">
              Thanh toán
            </h1>
          </div>
        </section>
      </div>

      <div className="checkout-container">
        <div className="checkout-left">
          <h2>Thanh toán & Vận chuyển</h2>

          <form>
            <label>Họ và tên *</label>
            <input
              type="text"
              id="name"
              value={checkout?.user.name || ""}
              onChange={(e) =>
                setCheckout((prev) =>
                  prev
                    ? { ...prev, user: { ...prev.user, name: e.target.value } }
                    : null
                )
              }
            />

            <label>Số điện thoại *</label>
            <input
              type="text"
              id="phone_number"
              value={checkout?.user.phone_number || ""}
              onChange={(e) =>
                setCheckout((prev) =>
                  prev
                    ? {
                      ...prev,
                      user: { ...prev.user, phone_number: e.target.value },
                    }
                    : null
                )
              }
            />

            <label>Email *</label>
            <input
              type="email"
              id="email"
              value={checkout?.user.email || ""}
              onChange={(e) =>
                setCheckout((prev) =>
                  prev
                    ? { ...prev, user: { ...prev.user, email: e.target.value } }
                    : null
                )
              }
            />

            {!editingAddress ? (
              <>
                <label>Địa chỉ đầy đủ *</label>
                <input
                  type="text"
                  id="address"
                  value={checkout?.user.address || ""}
                  readOnly
                />
                <button
                  type="button"
                  className="change-address-btn"
                  onClick={() => setEditingAddress(true)}
                >
                  Đổi địa chỉ
                </button>
              </>
            ) : (
              <>
                <label>Tỉnh/Thành phố *</label>
                <select
                  value={selectedProvince}
                  onChange={(e) => {
                    setSelectedProvince(e.target.value);
                    updateAddress();
                  }}
                >
                  <option value="">Chọn tỉnh/thành phố</option>
                  {provinces?.map((p) => (
                    <option key={p.code} value={p.code}>
                      {p.name}
                    </option>
                  ))}
                </select>

                <label>Quận/Huyện *</label>
                <select
                  value={selectedDistrict}
                  onChange={(e) => {
                    setSelectedDistrict(e.target.value);
                    updateAddress();
                  }}
                  disabled={!selectedProvince}
                >
                  <option value="">Chọn quận/huyện</option>
                  {districts?.map((d) => (
                    <option key={d.code} value={d.code}>
                      {d.name}
                    </option>
                  ))}
                </select>
                <label>Phường/Xã *</label>
                <select
                  value={selectedWard}
                  onChange={(e) => {
                    setSelectedWard(e.target.value);
                    updateAddress();
                  }}
                  disabled={!selectedDistrict}
                >
                  <option value="">Chọn phường/xã</option>
                  {wards?.map((w) => (
                    <option key={w.code} value={w.code}>
                      {w.name}
                    </option>
                  ))}
                </select>

                <label>Địa chỉ cụ thể *</label>
                <input
                  type="text"
                  placeholder="Nhập số nhà, tên đường..."
                  value={specificAddress}
                  onChange={(e) => {
                    setSpecificAddress(e.target.value);
                    updateAddress(e.target.value);
                  }}
                />
                <label>Địa chỉ đầy đủ *</label>
                <input
                  type="text"
                  id="address"
                  value={checkout?.user.address || ""}
                  onChange={(e) =>
                    setCheckout((prev) =>
                      prev
                        ? {
                          ...prev,
                          user: { ...prev.user, address: e.target.value },
                        }
                        : null
                    )
                  }
                />
                <button
                  type="button"
                  className="cancel-edit-btn"
                  onClick={() => setEditingAddress(false)}
                >
                  Huỷ
                </button>
              </>
            )}

            {savedAddresses.length > 0 && (
              <div style={{ marginTop: "5px" }}>
                {savedAddresses.length > 0 && (
                  <div style={{ marginTop: "5px" }}>
                    {savedAddresses.map((addr, index) => (
                      <p
                        key={index}
                        onClick={() =>
                          setCheckout((prev) =>
                            prev
                              ? {
                                ...prev,
                                user: { ...prev.user, address: addr },
                              }
                              : null
                          )
                        }
                        style={{
                          cursor: "pointer",
                          color: "#888",
                          marginBottom: "3px",
                        }}
                      >
                        {addr}
                      </p>
                    ))}
                  </div>
                )}
              </div>
            )}

            <label>Ghi chú</label>
            <input
              type="text"
              id="note"
              value={checkout?.note || ""}
              onChange={(e) =>
                setCheckout((prev) =>
                  prev ? { ...prev, note: e.target.value } : null
                )
              }
            />
          </form>
        </div>
        <div className="checkout-right">
          <h2>Đơn hàng của bạn</h2>
          {checkout?.selected_items && checkout.selected_items.length > 0 ? (
            checkout.selected_items.map((item) => {
              let images: string[] = [];

              try {
                images =
                  typeof item.image === "string" ? JSON.parse(item.image) : [];
              } catch (error) {
                console.error(
                  "❌ JSON.parse error:",
                  error,
                  "| Data:",
                  item.image
                );
                images = [item.image]; // Giữ nguyên nếu không parse được
              }

              const productImage = images[0] || "fallback-image.jpg"; // Ảnh mặc định nếu không có ảnh hợp lệ

              return (
                <div className="order-summary" key={item.id}>
                  <div className="product">
                    <img
                      src={productImage}
                      alt={item.product_name}
                      className="product-image"
                    />
                    <div className="product-details">
                      <p className="product-name">{item.product_name}</p>
                      <p className="product-quantity">x{item.quantity}</p>
                      <p className="product-price">
                        {(
                          parseFloat(item.price) * item.quantity
                        ).toLocaleString()}
                        đ
                      </p>
                    </div>
                  </div>
                  <hr />
                </div>
              );
            })
          ) : (
            <p>No items in the cart.</p>
          )}
          <div className="voucher-section">
            <label className="voucher-label">Mã giảm giá</label>
            <div className="voucher-input-wrapper">
              <input
                type="text"
                id="voucher"
                value={checkout?.voucher || ""}
                onChange={(e) =>
                  setCheckout((prev) =>
                    prev ? { ...prev, voucher: e.target.value } : null
                  )
                }
                className="voucher-input"
                placeholder="Nhập mã giảm giá"
              />
              <button className="voucher-button" onClick={applyVoucher}>Áp mã</button>
            </div>
          </div>
          <div className="price-details">
            <p>
              Tổng: <span>{checkout?.subtotal?.toLocaleString()}đ</span>
            </p>
            <p>
              Phí ship: <span>{checkout?.deliver_fee?.toLocaleString()}đ</span>
            </p>
            <p className="total">
              Tổng cộng: <strong>{checkout?.total?.toLocaleString()}đ</strong>
            </p>
          </div>

          <div className="payment-method">
            <label
              className={`payment-card ${paymentMethod === "cash_on_delivery" ? "active" : ""
                }`}
            >
              <input
                type="radio"
                name="payment"
                value="cash_on_delivery"
                checked={paymentMethod === "cash_on_delivery"}
                onChange={() => setPaymentMethod("cash_on_delivery")}
              />
              <FaMoneyBillWave className="payment-icon cod" />
              <span>Thanh toán khi nhận hàng</span>
            </label>

            <label
              className={`payment-card ${paymentMethod === "momo" ? "active" : ""
                }`}
            >
              <input
                type="radio"
                name="payment"
                value="momo"
                checked={paymentMethod === "momo"}
                onChange={() => {
                  setPaymentMethod("momo");
                  setShowMoMoTerms(true); // ← hiện điều khoản khi chọn
                }}
              />
              <FaMobileAlt className="payment-icon momo" />
              <span>Thanh toán bằng Ví MoMo</span>
            </label>
            {paymentMethod === "momo" && (
              <div className="terms-and-conditions">
                <label>
                  <input
                    type="checkbox"
                    checked={agreeTerms}
                    onChange={(e) => setAgreeTerms(e.target.checked)}
                  />
                  Tôi đồng ý với điều khoản thanh toán qua Ví MoMo.
                </label>
                {errorMessage && (
                  <p className="error-message">{errorMessage}</p>
                )}
              </div>
            )}
            {showMoMoTerms && (
              <div className="momo-modal_dieukhoan">
                <div className="momo-modal-overlay">
                  <div className="momo-modal">
                    <button className="close-btn" onClick={() => setShowMoMoTerms(false)}>×</button>
                    <h3>🔒 Điều Khoản Thanh Toán Qua MoMo</h3>
                    <p><strong>1. Phương thức thanh toán</strong><br />
                      Khách hàng có thể thanh toán đơn hàng thông qua Ví MoMo bằng cách quét mã QR hoặc thanh toán trực tiếp trên ứng dụng MoMo. Giao dịch được xử lý qua cổng thanh toán MoMo tích hợp trên website và đảm bảo an toàn theo quy trình bảo mật của MoMo.
                    </p>
                    <p><strong>2. Phí giao dịch</strong><br />
                      Chúng tôi không thu bất kỳ khoản phí nào khi khách hàng thanh toán bằng MoMo. Tuy nhiên, khách hàng cần đảm bảo số dư trong ví MoMo đủ để thực hiện thanh toán.
                    </p>
                    <p><strong>3. Xác nhận giao dịch</strong><br />
                      Sau khi hoàn tất thanh toán, đơn hàng sẽ được ghi nhận tự động. Hệ thống sẽ gửi xác nhận qua email hoặc giao diện đặt hàng trên website. Nếu giao dịch không thành công, đơn hàng sẽ không được xử lý.
                    </p>
                    <p><strong>4. Bảo mật thông tin</strong><br />
                      Thông tin thanh toán của khách hàng được xử lý hoàn toàn bởi hệ thống của MoMo. Chúng tôi không lưu trữ bất kỳ thông tin nào liên quan đến ví điện tử, tài khoản ngân hàng hay mã OTP của khách hàng.
                    </p>
                    <p><strong>5. Chính sách hủy đơn & hoàn tiền</strong><br />
                      Đơn hàng thanh toán bằng MoMo sẽ không được hoàn tiền dưới bất kỳ hình thức nào.
                    </p>
                    <p>Quý khách vui lòng kiểm tra kỹ thông tin đơn hàng và xác nhận trước khi tiến hành thanh toán.</p>
                    <p>Trong trường hợp đơn hàng bị hủy (bởi khách hàng hoặc vì lý do khác), số tiền đã thanh toán sẽ không được hoàn lại, do hệ thống không liên kết với ví MoMo để xử lý hoàn tiền tự động.</p>
                    <p><strong>6. Hỗ trợ và khiếu nại</strong><br />
                      <p>Mọi thắc mắc hoặc sự cố liên quan đến thanh toán qua MoMo, vui lòng liên hệ bộ phận hỗ trợ khách hàng của chúng tôi qua:</p>
                      📧 Email: [email hỗ trợ]<br />
                      📞 Hotline: [số điện thoại]
                    </p>
                  </div>
                </div>
              </div>
            )}
          </div>

          <button
            type="submit"
            className="order-button"
            onClick={handleOrder}
            disabled={isLoading} // Disable nút khi đang loading
          >
            {isLoading ? "Đang xử lý..." : "ĐẶT HÀNG"}
          </button>
        </div>
      </div>
    </>
  );
};

export default CheckOut;