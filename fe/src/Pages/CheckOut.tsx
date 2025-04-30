// import { useEffect, useState } from "react";
// import { getCheckOut, getOrder, Momopayment } from "../services/Order";
// import toast from "react-hot-toast";
// import { useNavigate } from "react-router-dom";
// import { FaMoneyBillWave, FaMobileAlt } from "react-icons/fa";
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
//   image: string;
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
//   selected_items: CartItem[];
// }

// const CheckOut = () => {
//   const [provinces, setProvinces] = useState<Address[]>([]);
//   const [districts, setDistricts] = useState<Address[]>([]);
//   const [wards, setWards] = useState<Address[]>([]);

//   const [selectedProvince, setSelectedProvince] = useState<string>("");
//   const [selectedDistrict, setSelectedDistrict] = useState<string>("");
//   const [selectedWard, setSelectedWard] = useState<string>("");
//   const [isLoading, setIsLoading] = useState(false);
//   const [paymentMethod, setPaymentMethod] = useState<string>("COD");

//   const [checkout, setCheckout] = useState<CheckoutData | null>(null);
//   const [selectedItems, setSelectedItems] = useState<number[]>([]);
//   const [voucherInput, setVoucherInput] = useState<string>("");

//   useEffect(() => {
//     const storedSelectedItems = localStorage.getItem("selectedItems");
//     if (storedSelectedItems) {
//       const parsedItems = JSON.parse(storedSelectedItems);
//       setSelectedItems(parsedItems);
//     }
//   }, []);

//   useEffect(() => {
//     if (selectedItems.length > 0) {
//       const userId = 1;
//       console.log("Calling getCheckOut with:", { userId, selectedItems });
//       getCheckOut(userId, selectedItems)
//         .then(({ data }) => {
//           console.log("getCheckOut response:", data);
//           setCheckout(data);
//         })
//         .catch((error) => {
//           console.error("API Error:", error);
//           toast.error("Không thể tải dữ liệu thanh toán.");
//         });
//     }
//   }, [selectedItems]);

//   const nav = useNavigate();

//   const handleOrder = async (e: React.FormEvent) => {
//     setIsLoading(true);
//     e.preventDefault();

//     if (!checkout) {
//       toast.error("Không có dữ liệu đơn hàng!");
//       setIsLoading(false);
//       return;
//     }

//     if (paymentMethod === "momo" && !agreeTerms) {
//       setErrorMessage("⚠️ Bạn chưa chọn điều khoản thanh toán❌");
//       setIsLoading(false);
//       return;
//     }

//     if (paymentMethod === "momo") {
//       const uniqueOrderId = `ORDER_${new Date().getTime()}`;
//       const momoData = {
//         amount: checkout.total,
//         orderId: uniqueOrderId,
//         redirectUrl: window.location.origin + "/momo-success",
//         username: checkout.user.name,
//         address: checkout.user.address,
//         email: checkout.user.email,
//         phone_number: checkout.user.phone_number,
//         selected_items: checkout.selected_items.map((item) => item.id),
//         voucher_code: checkout.voucher || null,
//       };

//       console.log("📦 Dữ liệu gửi API MoMo:", momoData);

//       try {
//         const momoResponse = await Momopayment(momoData);
//         console.log("✅ MoMo API Response:", momoResponse.data);
//         if (momoResponse.data?.payUrl) {
//           if (momoResponse.data.discount > 0) {
//             toast.success(
//               `Voucher '${checkout.voucher}' đã được áp dụng! Giảm giá: ${momoResponse.data.discount.toLocaleString()} VNĐ`
//             );
//           }
//           localStorage.setItem("pendingOrder", JSON.stringify(checkout));
//           window.location.href = momoResponse.data.payUrl;
//         } else {
//           toast.error("⚠️ Không nhận được URL thanh toán từ MoMo!");
//         }
//       } catch (error) {
//         if (error instanceof AxiosError) {
//           console.error("Lỗi từ MoMo API:", error.response?.data);
//           const errorMessage = error.response?.data?.message || "Lỗi không xác định từ MoMo API!";
//           toast.error(`Lỗi: ${errorMessage}`);
//         } else {
//           console.error("Lỗi kết nối với MoMo:", (error as Error).message);
//           toast.error("Lỗi kết nối với MoMo!");
//         }
//       } finally {
//         setIsLoading(false);
//       }
//     }

//     if (paymentMethod === "cash_on_delivery") {
//       processOrder();
//     }

//     if (checkout?.user.address) {
//       const storedAddresses = JSON.parse(
//         localStorage.getItem("savedAddresses") || "[]"
//       );

//       const updatedAddresses = [checkout.user.address, ...storedAddresses]
//         .filter((addr, index, self) => self.indexOf(addr) === index)
//         .slice(0, 2);

//       localStorage.setItem("savedAddresses", JSON.stringify(updatedAddresses));
//     }
//   };

//   const delay = (ms: number) =>
//     new Promise((resolve) => setTimeout(resolve, ms));

//   const processOrder = async () => {
//     const savedOrder = localStorage.getItem("pendingOrder");

//     if (!checkout) {
//       console.error("Checkout data is missing.");
//       toast.error("Không có dữ liệu đơn hàng!");
//       setIsLoading(false);
//       return;
//     }

//     console.log("Checkout Data:", checkout);

//     const orderData = {
//       user_id: checkout?.user.id,
//       username: checkout?.user.name,
//       phone_number: checkout?.user.phone_number,
//       email: checkout?.user.email,
//       address: checkout?.user.address,
//       note: (document.getElementById("note") as HTMLInputElement)?.value || "",
//       payment_method: paymentMethod === "momo" ? "momo" : "cash_on_delivery",
//       selected_items: (checkout?.selected_items || []).map((item) => item.id),
//       voucher_code: checkout?.voucher || null,
//     };

//     console.log("🚀 Sending Order Data:", orderData);

//     try {
//       await delay(2000);
//       const orderResponse = await getOrder(orderData);
//       console.log("✅ Order API Response:", orderResponse);

//       if (orderResponse.status !== 201) {
//         console.error(`Order failed with status: ${orderResponse.status}`);
//         toast.error(`Đặt hàng thất bại! Mã lỗi: ${orderResponse.status}`);
//         setIsLoading(false);
//         return;
//       }

//       if (orderResponse.data.discount > 0) {
//         toast.success(
//           `Voucher '${checkout.voucher}' đã được áp dụng! Giảm giá: ${orderResponse.data.discount.toLocaleString()} VNĐ`
//         );
//       }
//       toast.success("🎉 Đã đặt hàng thành công!");
//       localStorage.removeItem("pendingOrder");
//       nav("/myaccout?tab=orders");
//     } catch (error: unknown) {
//       if (error instanceof AxiosError) {
//         const errorMessage = error.response?.data?.message || "Có lỗi xảy ra!";
//         if (errorMessage.includes("Too Many Attempts")) {
//           toast.error("Bạn đã thử quá nhiều lần, vui lòng chờ một chút rồi thử lại!");
//         } else {
//           toast.error(errorMessage);
//         }
//       } else {
//         console.error("❌ Unexpected error:", error);
//         toast.error("Có lỗi xảy ra, vui lòng thử lại!");
//       }
//       setIsLoading(false);
//     }
//   };

//   const applyVoucher = async () => {
//     const voucher = voucherInput;
//     if (!voucher) {
//       toast.error("Vui lòng nhập mã giảm giá!");
//       return;
//     }

//     try {
//       const userId = 1;
//       console.log("Calling getCheckOut with voucher:", { userId, selectedItems, voucher });
//       const response = await getCheckOut(userId, selectedItems, voucher);
//       const data = response.data;
//       console.log("getCheckOut response with voucher:", data);

//       setCheckout((prev) =>
//         prev
//           ? {
//             ...prev,
//             voucher: data.discount > 0 ? voucher : null,
//             discount: data.discount,
//             total: data.total,
//             selected_items: data.selected_items,
//             subtotal: data.subtotal,
//             deliver_fee: data.deliver_fee,
//           }
//           : null
//       );

//       if (data.discount > 0) {
//         toast.success(
//           `Voucher '${voucher}' đã được áp dụng! Giảm giá: ${data.discount.toLocaleString()} VNĐ`
//         );
//       } else {
//         toast.error("Voucher không hợp lệ hoặc không áp dụng được!");
//       }
//     } catch (err) {
//       console.error("Lỗi khi áp dụng voucher:", err);
//       toast.error("Lỗi khi áp dụng voucher!");
//     }
//   };

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
//     }
//   }, []);

//   const [editingAddress, setEditingAddress] = useState(false);
//   const [showMoMoTerms, setShowMoMoTerms] = useState(false);

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
//           ...prev,
//           user: {
//             ...prev.user,
//             address: `${newValue || specificAddress}, ${wards.find((w) => String(w.code) === selectedWard)?.name || ""
//               }, ${districts.find((d) => String(d.code) === selectedDistrict)?.name ||
//               ""
//               }, ${provinces.find((p) => String(p.code) === selectedProvince)?.name ||
//               ""
//               }`,
//           },
//         }
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

//   return (
//     <>
//       <div className="menu_overlay"></div>
//       <div className="main_section">
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
//                       ...prev,
//                       user: { ...prev.user, phone_number: e.target.value },
//                     }
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

//             {!editingAddress ? (
//               <>
//                 <label>Địa chỉ đầy đủ *</label>
//                 <input
//                   type="text"
//                   id="address"
//                   value={checkout?.user.address || ""}
//                   readOnly
//                 />
//                 <button
//                   type="button"
//                   className="change-address-btn"
//                   onClick={() => setEditingAddress(true)}
//                 >
//                   Đổi địa chỉ
//                 </button>
//               </>
//             ) : (
//               <>
//                 <label>Tỉnh/Thành phố *</label>
//                 <select
//                   value={selectedProvince}
//                   onChange={(e) => {
//                     setSelectedProvince(e.target.value);
//                     updateAddress();
//                   }}
//                 >
//                   <option value="">Chọn tỉnh/thành phố</option>
//                   {provinces?.map((p) => (
//                     <option key={p.code} value={p.code}>
//                       {p.name}
//                     </option>
//                   ))}
//                 </select>

//                 <label>Quận/Huyện *</label>
//                 <select
//                   value={selectedDistrict}
//                   onChange={(e) => {
//                     setSelectedDistrict(e.target.value);
//                     updateAddress();
//                   }}
//                   disabled={!selectedProvince}
//                 >
//                   <option value="">Chọn quận/huyện</option>
//                   {districts?.map((d) => (
//                     <option key={d.code} value={d.code}>
//                       {d.name}
//                     </option>
//                   ))}
//                 </select>
//                 <label>Phường/Xã *</label>
//                 <select
//                   value={selectedWard}
//                   onChange={(e) => {
//                     setSelectedWard(e.target.value);
//                     updateAddress();
//                   }}
//                   disabled={!selectedDistrict}
//                 >
//                   <option value="">Chọn phường/xã</option>
//                   {wards?.map((w) => (
//                     <option key={w.code} value={w.code}>
//                       {w.name}
//                     </option>
//                   ))}
//                 </select>

//                 <label>Địa chỉ cụ thể *</label>
//                 <input
//                   type="text"
//                   placeholder="Nhập số nhà, tên đường..."
//                   value={specificAddress}
//                   onChange={(e) => {
//                     setSpecificAddress(e.target.value);
//                     updateAddress(e.target.value);
//                   }}
//                 />
//                 <label>Địa chỉ đầy đủ *</label>
//                 <input
//                   type="text"
//                   id="address"
//                   value={checkout?.user.address || ""}
//                   onChange={(e) =>
//                     setCheckout((prev) =>
//                       prev
//                         ? {
//                           ...prev,
//                           user: { ...prev.user, address: e.target.value },
//                         }
//                         : null
//                     )
//                   }
//                 />
//                 <button
//                   type="button"
//                   className="cancel-edit-btn"
//                   onClick={() => setEditingAddress(false)}
//                 >
//                   Huỷ
//                 </button>
//               </>
//             )}

//             {savedAddresses.length > 0 && (
//               <div style={{ marginTop: "5px" }}>
//                 {savedAddresses.map((addr, index) => (
//                   <p
//                     key={index}
//                     onClick={() =>
//                       setCheckout((prev) =>
//                         prev
//                           ? {
//                             ...prev,
//                             user: { ...prev.user, address: addr },
//                           }
//                           : null
//                       )
//                     }
//                     style={{
//                       cursor: "pointer",
//                       color: "#888",
//                       marginBottom: "3px",
//                     }}
//                   >
//                     {addr}
//                   </p>
//                 ))}
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
//               let productImage: string;

//               if (typeof item.image === "string") {
//                 if (item.image.startsWith("data:image")) {
//                   productImage = item.image;
//                 } else {
//                   try {
//                     const parsedImage = JSON.parse(item.image);
//                     productImage =
//                       Array.isArray(parsedImage) && parsedImage.length > 0
//                         ? parsedImage[0]
//                         : parsedImage?.url || "fallback-image.jpg";
//                   } catch (error) {
//                     console.error(
//                       "❌ JSON.parse error:",
//                       error,
//                       "| Data:",
//                       item.image
//                     );
//                     productImage = "fallback-image.jpg";
//                   }
//                 }
//               } else {
//                 productImage = "fallback-image.jpg";
//               }

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
//                 </div>
//               );
//             })
//           ) : (
//             <p>Không có sản phẩm nào trong giỏ hàng.</p>
//           )}
//           <div className="voucher-section">
//             <label className="voucher-label">Mã giảm giá</label>
//             <div className="voucher-input-wrapper">
//               <input
//                 type="text"
//                 id="voucher"
//                 value={voucherInput}
//                 onChange={(e) => setVoucherInput(e.target.value)}
//                 className="voucher-input"
//                 placeholder="Nhập mã giảm giá"
//               />
//               <button className="voucher-button" onClick={applyVoucher}>
//                 Áp mã
//               </button>
//             </div>
//           </div>
//           <div className="price-details">
//             <p>
//               Tổng: <span>{checkout?.subtotal?.toLocaleString()}đ</span>
//             </p>
//             <p>
//               Phí ship: <span>{checkout?.deliver_fee?.toLocaleString()}đ</span>
//             </p>
//             {checkout?.discount > 0 && (
//               <p>
//                 Giảm giá: <span>-{parseFloat(checkout?.discount).toLocaleString()}đ</span>
//               </p>
//             )}
//             <p className="total">
//               Tổng cộng: <span>{checkout?.total?.toLocaleString()}đ</span>
//             </p>
//           </div>

//           <div className="payment-method">
//             <label
//               className={`payment-card ${paymentMethod === "cash_on_delivery" ? "active" : ""
//                 }`}
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
//               className={`payment-card ${paymentMethod === "momo" ? "active" : ""}`}
//             >
//               <input
//                 type="radio"
//                 name="payment"
//                 value="momo"
//                 checked={paymentMethod === "momo"}
//                 onChange={() => {
//                   setPaymentMethod("momo");
//                   setShowMoMoTerms(true);
//                 }}
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
//             {showMoMoTerms && (
//               <div className="momo-modal_dieukhoan">
//                 <div className="momo-modal-overlay">
//                   <div className="momo-modal">
//                     <button
//                       className="close-btn"
//                       onClick={() => setShowMoMoTerms(false)}
//                     >
//                       ×
//                     </button>
//                     <h3>🔒 Điều Khoản Thanh Toán Qua MoMo</h3>
//                     <p>
//                       <strong>1. Phương thức thanh toán</strong>
//                       <br />
//                       Khách hàng có thể thanh toán đơn hàng thông qua Ví MoMo bằng
//                       cách quét mã QR hoặc thanh toán trực tiếp trên ứng dụng MoMo.
//                       Giao dịch được xử lý qua cổng thanh toán MoMo tích hợp trên
//                       website và đảm bảo an toàn theo quy trình bảo mật của MoMo.
//                     </p>
//                     <p>
//                       <strong>2. Phí giao dịch</strong>
//                       <br />
//                       Chúng tôi không thu bất kỳ khoản phí nào khi khách hàng thanh
//                       toán bằng MoMo. Tuy nhiên, khách hàng cần đảm bảo số dư trong
//                       ví MoMo đủ để thực hiện thanh toán.
//                     </p>
//                     <p>
//                       <strong>3. Xác nhận giao dịch</strong>
//                       <br />
//                       Sau khi hoàn tất thanh toán, đơn hàng sẽ được ghi nhận tự
//                       động. Hệ thống sẽ gửi xác nhận qua email hoặc giao diện đặt
//                       hàng trên website. Nếu giao dịch không thành công, đơn hàng sẽ
//                       không được xử lý.
//                     </p>
//                     <p>
//                       <strong>4. Bảo mật thông tin</strong>
//                       <br />
//                       Thông tin thanh toán của khách hàng được xử lý hoàn toàn bởi
//                       hệ thống của MoMo. Chúng tôi không lưu trữ bất kỳ thông tin nào
//                       liên quan đến ví điện tử, tài khoản ngân hàng hay mã OTP của
//                       khách hàng.
//                     </p>
//                     <p>
//                       <strong>5. Chính sách hủy đơn & hoàn tiền</strong>
//                       <br />
//                       Đơn hàng thanh toán bằng MoMo sẽ không được hoàn tiền dưới bất
//                       kỳ hình thức nào.
//                     </p>
//                     <p>
//                       Quý khách vui lòng kiểm tra kỹ thông tin đơn hàng và xác nhận
//                       trước khi tiến hành thanh toán.
//                     </p>
//                     <p>
//                       Trong trường hợp đơn hàng bị hủy (bởi khách hàng hoặc vì lý do
//                       khác), số tiền đã thanh toán sẽ không được hoàn lại, do hệ
//                       thống không liên kết với ví MoMo để xử lý hoàn tiền tự động.
//                     </p>
//                     <p>
//                       <strong>6. Hỗ trợ và khiếu nại</strong>
//                       <br />
//                       <p>
//                         Mọi thắc mắc hoặc sự cố liên quan đến thanh toán qua MoMo, vui
//                         lòng liên hệ bộ phận hỗ trợ khách hàng của chúng tôi qua:
//                       </p>
//                       📧 Email: [email hỗ trợ]
//                       <br />
//                       📞 Hotline: [số điện thoại]
//                     </p>
//                   </div>
//                 </div>
//               </div>
//             )}
//           </div>

//           <button
//             type="submit"
//             className="order-button"
//             onClick={handleOrder}
//             disabled={isLoading}
//           >
//             {isLoading ? "Đang xử lý..." : "ĐẶT HÀNG"}
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
import { AxiosError } from "axios";
import { api } from "../config/axios";
import { getAllVoucher, Voucher } from "../services/vouchers"; // Import API và type Voucher

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
  image: string;
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
  selected_items: CartItem[];
}

const CheckOut = () => {
  const [provinces, setProvinces] = useState<Address[]>([]);
  const [districts, setDistricts] = useState<Address[]>([]);
  const [wards, setWards] = useState<Address[]>([]);

  const [selectedProvince, setSelectedProvince] = useState<string>("");
  const [selectedDistrict, setSelectedDistrict] = useState<string>("");
  const [selectedWard, setSelectedWard] = useState<string>("");
  const [isLoading, setIsLoading] = useState(false);
  const [paymentMethod, setPaymentMethod] = useState<string>("COD");

  const [checkout, setCheckout] = useState<CheckoutData | null>(null);
  const [selectedItems, setSelectedItems] = useState<number[]>([]);
  const [voucherInput, setVoucherInput] = useState<string>("");

  // Thêm trạng thái để quản lý danh sách voucher và modal
  const [vouchers, setVouchers] = useState<Voucher[]>([]);
  const [showVoucherModal, setShowVoucherModal] = useState(false);

  // Gọi API để lấy danh sách voucher
  useEffect(() => {
    getAllVoucher()
      .then(({ data }) => {
        setVouchers(data);
        console.log("Vouchers:", data);
      })
      .catch(() => toast.error("Lỗi không lấy được danh sách voucher"));
  }, []);

  useEffect(() => {
    const storedSelectedItems = localStorage.getItem("selectedItems");
    if (storedSelectedItems) {
      const parsedItems = JSON.parse(storedSelectedItems);
      setSelectedItems(parsedItems);
    }
  }, []);

  useEffect(() => {
    if (selectedItems.length > 0) {
      const userId = 1;
      console.log("Calling getCheckOut with:", { userId, selectedItems });
      getCheckOut(userId, selectedItems)
        .then(({ data }) => {
          console.log("getCheckOut response:", data);
          setCheckout(data);
        })
        .catch((error) => {
          console.error("API Error:", error);
          toast.error("Không thể tải dữ liệu thanh toán.");
        });
    }
  }, [selectedItems]);

  const nav = useNavigate();

  const handleOrder = async (e: React.FormEvent) => {
    setIsLoading(true);
    e.preventDefault();

    if (!checkout) {
      toast.error("Không có dữ liệu đơn hàng!");
      setIsLoading(false);
      return;
    }

    if (paymentMethod === "momo" && !agreeTerms) {
      setErrorMessage("⚠️ Bạn chưa chọn điều khoản thanh toán❌");
      setIsLoading(false);
      return;
    }

    if (paymentMethod === "momo") {
      const uniqueOrderId = `ORDER_${new Date().getTime()}`;
      const momoData = {
        amount: checkout.total,
        orderId: uniqueOrderId,
        redirectUrl: window.location.origin + "/momo-success",
        username: checkout.user.name,
        address: checkout.user.address,
        email: checkout.user.email,
        phone_number: checkout.user.phone_number,
        selected_items: checkout.selected_items.map((item) => item.id),
        voucher_code: checkout.voucher || null,
      };

      console.log("📦 Dữ liệu gửi API MoMo:", momoData);

      try {
        const momoResponse = await Momopayment(momoData);
        console.log("✅ MoMo API Response:", momoResponse.data);
        if (momoResponse.data?.payUrl) {
          if (momoResponse.data.discount > 0) {
            toast.success(
              `Voucher '${checkout.voucher}' đã được áp dụng! Giảm giá: ${momoResponse.data.discount.toLocaleString()} VNĐ`
            );
          }
          localStorage.setItem("pendingOrder", JSON.stringify(checkout));
          window.location.href = momoResponse.data.payUrl;
        } else {
          toast.error("⚠️ Không nhận được URL thanh toán từ MoMo!");
        }
      } catch (error) {
        if (error instanceof AxiosError) {
          console.error("Lỗi từ MoMo API:", error.response?.data);
          const errorMessage = error.response?.data?.message || "Lỗi không xác định từ MoMo API!";
          toast.error(`Lỗi: ${errorMessage}`);
        } else {
          console.error("Lỗi kết nối với MoMo:", (error as Error).message);
          toast.error("Lỗi kết nối với MoMo!");
        }
      } finally {
        setIsLoading(false);
      }
    }

    if (paymentMethod === "cash_on_delivery") {
      processOrder();
    }

    if (checkout?.user.address) {
      const storedAddresses = JSON.parse(
        localStorage.getItem("savedAddresses") || "[]"
      );

      const updatedAddresses = [checkout.user.address, ...storedAddresses]
        .filter((addr, index, self) => self.indexOf(addr) === index)
        .slice(0, 2);

      localStorage.setItem("savedAddresses", JSON.stringify(updatedAddresses));
    }
  };

  const delay = (ms: number) =>
    new Promise((resolve) => setTimeout(resolve, ms));

  const processOrder = async () => {
    const savedOrder = localStorage.getItem("pendingOrder");

    if (!checkout) {
      console.error("Checkout data is missing.");
      toast.error("Không có dữ liệu đơn hàng!");
      setIsLoading(false);
      return;
    }

    console.log("Checkout Data:", checkout);

    const orderData = {
      user_id: checkout?.user.id,
      username: checkout?.user.name,
      phone_number: checkout?.user.phone_number,
      email: checkout?.user.email,
      address: checkout?.user.address,
      note: (document.getElementById("note") as HTMLInputElement)?.value || "",
      payment_method: paymentMethod === "momo" ? "momo" : "cash_on_delivery",
      selected_items: (checkout?.selected_items || []).map((item) => item.id),
      voucher_code: checkout?.voucher || null,
    };

    console.log("🚀 Sending Order Data:", orderData);

    try {
      await delay(2000);
      const orderResponse = await getOrder(orderData);
      console.log("✅ Order API Response:", orderResponse);

      if (orderResponse.status !== 201) {
        console.error(`Order failed with status: ${orderResponse.status}`);
        toast.error(`Đặt hàng thất bại! Mã lỗi: ${orderResponse.status}`);
        setIsLoading(false);
        return;
      }

      if (orderResponse.data.discount > 0) {
        toast.success(
          `Voucher '${checkout.voucher}' đã được áp dụng! Giảm giá: ${orderResponse.data.discount.toLocaleString()} VNĐ`
        );
      }
      
      toast.success("🎉 Đã đặt hàng thành công!");
      localStorage.removeItem("pendingOrder");
      nav("/myaccout?tab=orders");
    } catch (error: unknown) {
      if (error instanceof AxiosError) {
        const errorMessage = error.response?.data?.message || "Có lỗi xảy ra!";
        if (errorMessage.includes("Too Many Attempts")) {
          toast.error("Bạn đã thử quá nhiều lần, vui lòng chờ một chút rồi thử lại!");
        } else {
          toast.error(errorMessage);
        }
      } else {
        console.error("❌ Unexpected error:", error);
        toast.error("Có lỗi xảy ra, vui lòng thử lại!");
      }
      setIsLoading(false);
    }
  };

  const applyVoucher = async () => {
    const voucher = voucherInput;
    if (!voucher) {
      toast.error("Vui lòng nhập mã giảm giá!");
      return;
    }

    try {
      const userId = 1;
      console.log("Calling getCheckOut with voucher:", { userId, selectedItems, voucher });
      const response = await getCheckOut(userId, selectedItems, voucher);
      const data = response.data;
      console.log("getCheckOut response with voucher:", data);

      setCheckout((prev) =>
        prev
          ? {
            ...prev,
            voucher: data.discount > 0 ? voucher : null,
            discount: data.discount,
            total: data.total,
            selected_items: data.selected_items,
            subtotal: data.subtotal,
            deliver_fee: data.deliver_fee,
          }
          : null
      );

      if (data.discount > 0) {
        toast.success(
          `Voucher '${voucher}' đã được áp dụng! Giảm giá: ${data.discount.toLocaleString()} VNĐ`
        );
      } else {
        toast.error("Voucher không hợp lệ hoặc không áp dụng được!");
      }
    } catch (err) {
      console.error("Lỗi khi áp dụng voucher:", err);
      toast.error("Lỗi khi áp dụng voucher!");
    }
  };

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
    }
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
              }, ${districts.find((d) => String(d.code) === selectedDistrict)?.name ||
              ""
              }, ${provinces.find((p) => String(p.code) === selectedProvince)?.name ||
              ""
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

  // Hàm xử lý khi chọn voucher
  const handleSelectVoucher = (code: string) => {
    setVoucherInput(code);
    setShowVoucherModal(false);
  };

  return (
    <>
      <div className="menu_overlay"></div>
      <div className="main_section">
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
              let productImage: string;

              if (typeof item.image === "string") {
                if (item.image.startsWith("data:image")) {
                  productImage = item.image;
                } else {
                  try {
                    const parsedImage = JSON.parse(item.image);
                    productImage =
                      Array.isArray(parsedImage) && parsedImage.length > 0
                        ? parsedImage[0]
                        : parsedImage?.url || "fallback-image.jpg";
                  } catch (error) {
                    console.error(
                      "❌ JSON.parse error:",
                      error,
                      "| Data:",
                      item.image
                    );
                    productImage = "fallback-image.jpg";
                  }
                }
              } else {
                productImage = "fallback-image.jpg";
              }

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
            <p>Không có sản phẩm nào trong giỏ hàng.</p>
          )}
          <div className="voucher-section">
            <label className="voucher-label">Mã giảm giá</label>
            <div className="voucher-input-wrapper">
              <input
                type="text"
                id="voucher"
                value={voucherInput}
                onChange={(e) => setVoucherInput(e.target.value)}
                onClick={() => setShowVoucherModal(true)} // Hiển thị modal khi bấm vào input
                className="voucher-input"
                placeholder="Nhập mã giảm giá"
              />
              <button className="voucher-button" onClick={applyVoucher}>
                Áp mã
              </button>
            </div>
          </div>

          {/* Modal Voucher */}
          {showVoucherModal && (
            <div className="voucher-modal-overlay">
              <div className="voucher-modal">
                <div className="voucher-modal-header">
                  <h3 className="voucher-modal-title">Voucher giảm giá</h3>
                  <button
                    className="voucher-modal-back-btn"
                    onClick={() => setShowVoucherModal(false)}
                  >
                    Quay lại
                  </button>
                </div>
                <div className="voucher-list">
                  {vouchers.length === 0 ? (
                    <p>Hiện tại chưa có voucher nào.</p>
                  ) : (
                    vouchers.map((voucher) => (
                      <div key={voucher.id} className="voucher-item">
                        <div className="voucher-info">
                          <p className="voucher-code">{voucher.name}</p>
                          <p className="voucher-discount">
                            Giảm {parseFloat(voucher.discount_percent).toLocaleString("vi-VN")}% - Giảm tối đa{" "}
                            {parseFloat(voucher.max_discount_amount).toLocaleString("vi-VN")}đ
                          </p>
                          <p className="voucher-min-order">
                            Đơn tối thiểu {parseFloat(voucher.min_purchase_amount).toLocaleString("vi-VN")}đ
                          </p>
                          <p className="voucher-expiry">
                          Ngày hết hạn: {new Date(voucher.expiration_date).toLocaleDateString("vi-VN")}
                          </p>
                          <p className="voucher-quantity">
                            Còn: {parseFloat(voucher.quantity).toLocaleString("vi-VN")} mã
                          </p>
                          <p className="voucher-note">
                            Vui lòng chọn sản phẩm từ Shop để áp dụng Voucher này
                          </p>
                        </div>
                        <button
                          className="voucher-select-btn"
                          onClick={() => handleSelectVoucher(voucher.name)}
                        >
                          CHỌN
                        </button>
                      </div>
                    ))
                  )}
                </div>
              </div>
            </div>
          )}

          <div className="price-details">
            <p>
              Tổng: <span>{checkout?.subtotal?.toLocaleString()}đ</span>
            </p>
            <p>
              Phí ship: <span>{checkout?.deliver_fee?.toLocaleString()}đ</span>
            </p>
            {checkout?.discount > 0 && (
              <p>
                Giảm giá: <span>-{parseFloat(checkout?.discount).toLocaleString()}đ</span>
              </p>
            )}
            <p className="total">
              Tổng cộng: <span>{checkout?.total?.toLocaleString()}đ</span>
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
              className={`payment-card ${paymentMethod === "momo" ? "active" : ""}`}
            >
              <input
                type="radio"
                name="payment"
                value="momo"
                checked={paymentMethod === "momo"}
                onChange={() => {
                  setPaymentMethod("momo");
                  setShowMoMoTerms(true);
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
                    <button
                      className="close-btn"
                      onClick={() => setShowMoMoTerms(false)}
                    >
                      ×
                    </button>
                    <h3>🔒 Điều Khoản Thanh Toán Qua MoMo</h3>
                    <p>
                      <strong>1. Phương thức thanh toán</strong>
                      <br />
                      Khách hàng có thể thanh toán đơn hàng thông qua Ví MoMo bằng
                      cách quét mã QR hoặc thanh toán trực tiếp trên ứng dụng MoMo.
                      Giao dịch được xử lý qua cổng thanh toán MoMo tích hợp trên
                      website và đảm bảo an toàn theo quy trình bảo mật của MoMo.
                    </p>
                    <p>
                      <strong>2. Phí giao dịch</strong>
                      <br />
                      Chúng tôi không thu bất kỳ khoản phí nào khi khách hàng thanh
                      toán bằng MoMo. Tuy nhiên, khách hàng cần đảm bảo số dư trong
                      ví MoMo đủ để thực hiện thanh toán.
                    </p>
                    <p>
                      <strong>3. Xác nhận giao dịch</strong>
                      <br />
                      Sau khi hoàn tất thanh toán, đơn hàng sẽ được ghi nhận tự
                      động. Hệ thống sẽ gửi xác nhận qua email hoặc giao diện đặt
                      hàng trên website. Nếu giao dịch không thành công, đơn hàng sẽ
                      không được xử lý.
                    </p>
                    <p>
                      <strong>4. Bảo mật thông tin</strong>
                      <br />
                      Thông tin thanh toán của khách hàng được xử lý hoàn toàn bởi
                      hệ thống của MoMo. Chúng tôi không lưu trữ bất kỳ thông tin nào
                      liên quan đến ví điện tử, tài khoản ngân hàng hay mã OTP của
                      khách hàng.
                    </p>
                    <p>
                      <strong>5. Chính sách hủy đơn & hoàn tiền</strong>
                      <br />
                      Đơn hàng thanh toán bằng MoMo sẽ không được hoàn tiền dưới bất
                      kỳ hình thức nào.
                    </p>
                    <p>
                      Quý khách vui lòng kiểm tra kỹ thông tin đơn hàng và xác nhận
                      trước khi tiến hành thanh toán.
                    </p>
                    <p>
                      Trong trường hợp đơn hàng bị hủy (bởi khách hàng hoặc vì lý do
                      khác), số tiền đã thanh toán sẽ không được hoàn lại, do hệ
                      thống không liên kết với ví MoMo để xử lý hoàn tiền tự động.
                    </p>
                    <p>
                      <strong>6. Hỗ trợ và khiếu nại</strong>
                      <br />
                      <p>
                        Mọi thắc mắc hoặc sự cố liên quan đến thanh toán qua MoMo, vui
                        lòng liên hệ bộ phận hỗ trợ khách hàng của chúng tôi qua:
                      </p>
                      📧 Email: [email hỗ trợ]
                      <br />
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
            disabled={isLoading}
          >
            {isLoading ? "Đang xử lý..." : "ĐẶT HÀNG"}
          </button>
        </div>
      </div>
    </>
  );
};

export default CheckOut;