// import { useEffect, useState } from "react";
// import { useSelector } from "react-redux";
// import { RootState } from "../store/store";
// import { CartItem, fetchCart } from "../services/cart";

// interface Address {
//   _id: string;
//   name: string;
//   slug: string;
//   type: string;
//   name_with_type: string;
//   code: number;
// }
// const CheckOut = () => {
//   const [cart, setCart] = useState<CartItem[]>([]);
//   useEffect(() => {
//     fetchCart().then((data) => {
//       console.log("cartItem", data);
//       setCart(data);
//     });

//   });

//   const [provinces, setProvinces] = useState<Address[]>([]);
//   const [districts, setDistricts] = useState<Address[]>([]);
//   const [wards, setWards] = useState<Address[]>([]);
//   const [selectedProvince, setSelectedProvince] = useState<string>("");
//   const [selectedDistrict, setSelectedDistrict] = useState<string>("");
//   const userId = useSelector((state: RootState) => state.user.user);
//   console.log("user", userId);
//   useEffect(() => {
//     fetch("https://vn-public-apis.fpo.vn/provinces/getAll?limit=-1")
//       .then((response) => response.json())
//       .then((data) => {
//         setProvinces(data.data.data);
//       })
//       .catch((error) => console.error("error", error));
//   }, []);
//   useEffect(() => {
//     if (selectedProvince) {
//       fetch(
//         `https://vn-public-apis.fpo.vn/districts/getByProvince?provinceCode=${selectedProvince}&limit=-1`
//       )
//         .then((response) => response.json())
//         .then((data) => {
//           setDistricts(data.data.data);
//           setWards([]); // Reset danh sách xã/phường khi đổi tỉnh
//         })
//         .catch((error) => console.error("error", error));
//     }
//   }, [selectedProvince]);
//   useEffect(() => {
//     if (selectedDistrict) {
//       fetch(
//         `https://vn-public-apis.fpo.vn/wards/getByDistrict?districtCode=${selectedDistrict}&limit=-1`
//       )
//         .then((response) => response.json())
//         .then((data) => {
//           setWards(data.data.data);
//         })
//         .catch((error) => console.error("error", error));
//     }
//   }, [selectedDistrict]);

//   return (
//     <>
//       <div className="menu_overlay"></div>
//       {/* END Header */}
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
//         {/* END Breadcrumb */}
//         {/* START Checkout Section */}
//       </div>
//       <div className="checkout-container">
//         <div className="checkout-left">
//           <h2>Thanh toán & Vận chuyển</h2>
//           <form>
//             <label>Họ và tên *</label>
//             <input type="text" value={userId?.name || ""} required />

//             <label>Số điện thoại *</label>
//             <input type="text" placeholder="Số điện thoại của bạn" required />

//             <label>Địa chỉ email (tùy chọn)</label>
//             <input type="email" value={userId?.email || ""} />

//             <label>Tỉnh/Thành phố *</label>
//             <select onChange={(e) => setSelectedProvince(e.target.value)}>
//               <option value="">Chọn tỉnh/thành phố</option>
//               {provinces.map((province) => (
//                 <option key={province.code} value={province.code}>
//                   {province.name}
//                 </option>
//               ))}
//             </select>

//             <label>Quận/Huyện *</label>
//             <select
//               onChange={(e) => setSelectedDistrict(e.target.value)}
//               disabled={!selectedProvince}
//             >
//               <option value="">Chọn quận/huyện</option>
//               {districts.map((district) => (
//                 <option key={district.code} value={district.code}>
//                   {district.name}
//                 </option>
//               ))}
//             </select>

//             <label>Xã/Phường *</label>
//             <select disabled={!selectedDistrict}>
//               <option value="">Chọn xã/phường</option>
//               {wards.map((ward) => (
//                 <option key={ward.code} value={ward.code}>
//                   {ward.name}
//                 </option>
//               ))}
//             </select>

//             <label>Địa chỉ *</label>
//             <input type="text" placeholder="Ví dụ: Số 20, ngõ 90" required />
//             <label>Ghi chú</label>
//             <input type="text" placeholder="Ghi chú" required />
//           </form>
//         </div>
//         <div className="checkout-right">
//           <h2>Đơn hàng của bạn</h2>
//           {cart.map((cart) => (
//             <div className="order-summary">
//               <div className="product">
//                 <img
//                   src={cart.image}
//                   alt="Giày Sneaker"
//                   className="product-image"
//                 />
//                 <div className="product-details">
//                   <p className="product-name">{cart.product_name}</p>
//                   <p className="product-quantity">x{cart.quantity}</p>
//                   <p className="product-price">
//                     {cart.discount_price * cart.quantity}đ
//                   </p>
//                 </div>
//               </div>
//               <hr />
//             </div>
//           ))}
//           {(() => {
//             const total = cart.reduce(
//               (sum, item) => sum + item.discount_price * item.quantity,
//               0
//             );
//             const shippingFee = 30000;
//             const grandTotal = total + shippingFee;

//             return (
//               <div className="price-details">
//                 <p>
//                   Tổng: <span>{total.toLocaleString()}đ</span>
//                 </p>
//                 <p>
//                   Phí ship: <span>{shippingFee.toLocaleString()}đ</span>
//                 </p>
//                 <p className="total">
//                   Tổng cộng: <strong>{grandTotal.toLocaleString()}đ</strong>
//                 </p>
//               </div>
//             );
//           })()}

//           <div className="payment-method">
//             <label className="payment-option">
//               <input
//                 type="radio"
//                 name="payment"
//                 defaultChecked
//                 className="payment-checkbox square"
//               />
//               <span>Trả tiền mặt khi nhận hàng</span>
//               <p className="payment-description">
//                 Bạn đặt hàng và thanh toán sau khi nhận hàng.
//               </p>
//             </label>
//             <hr className="payment-divider" />
//             <label className="payment-option">
//               <input
//                 type="radio"
//                 name="payment"
//                 className="payment-checkbox square"
//               />
//               <span>Chuyển khoản ngân hàng</span>
//               <p className="payment-description">
//                 Thanh toán qua ngân hàng trước khi giao hàng.
//               </p>
//             </label>
//           </div>
//           <button className="order-button">ĐẶT HÀNG</button>
//         </div>
//       </div>
//     </>
//   );
// };

// export default CheckOut;

// // import { useEffect, useState } from "react";
// // import { useSelector } from "react-redux";
// // import { RootState } from "../store/store";
// // import api from "../utils/api"; // Import hàm gọi API

// // interface Address {
// //   _id: string;
// //   name: string;
// //   slug: string;
// //   type: string;
// //   name_with_type: string;
// //   code: number;
// // }

// // const CheckOut = () => {
// //   const [provinces, setProvinces] = useState<Address[]>([]);
// //   const [districts, setDistricts] = useState<Address[]>([]);
// //   const [wards, setWards] = useState<Address[]>([]);
// //   const [selectedProvince, setSelectedProvince] = useState<string>("");
// //   const [selectedDistrict, setSelectedDistrict] = useState<string>("");
// //   const [voucher, setVoucher] = useState<string>(""); // Thêm state cho voucher
// //   const [paymentMethod, setPaymentMethod] = useState<string>("cod");
// //   const [address, setAddress] = useState<string>("");
// //   const [note, setNote] = useState<string>("");

// //   const user = useSelector((state: RootState) => state.user.user);
// //   const cart = useSelector((state: RootState) => state.cart.items); // Giả sử giỏ hàng lưu trong Redux
// //   const totalAmount = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
// //   const shippingFee = 30000;

// //   useEffect(() => {
// //     fetch("https://vn-public-apis.fpo.vn/provinces/getAll?limit=-1")
// //       .then((response) => response.json())
// //       .then((data) => setProvinces(data.data.data))
// //       .catch((error) => console.error("error", error));
// //   }, []);

// //   useEffect(() => {
// //     if (selectedProvince) {
// //       fetch(`https://vn-public-apis.fpo.vn/districts/getByProvince?provinceCode=${selectedProvince}&limit=-1`)
// //         .then((response) => response.json())
// //         .then((data) => setDistricts(data.data.data))
// //         .catch((error) => console.error("error", error));
// //     }
// //   }, [selectedProvince]);

// //   useEffect(() => {
// //     if (selectedDistrict) {
// //       fetch(`https://vn-public-apis.fpo.vn/wards/getByDistrict?districtCode=${selectedDistrict}&limit=-1`)
// //         .then((response) => response.json())
// //         .then((data) => setWards(data.data.data))
// //         .catch((error) => console.error("error", error));
// //     }
// //   }, [selectedDistrict]);

// //   const handleOrder = async () => {
// //     if (!user || !address || cart.length === 0) {
// //       alert("Vui lòng nhập đầy đủ thông tin và kiểm tra giỏ hàng!");
// //       return;
// //     }

// //     const orderData = {
// //       user_id: user.id,
// //       name: user.name,
// //       email: user.email,
// //       phone: "SĐT người dùng",
// //       address: `${address}, ${selectedWard}, ${selectedDistrict}, ${selectedProvince}`,
// //       total_amount: totalAmount + shippingFee,
// //       shipping_fee: shippingFee,
// //       payment_method: paymentMethod,
// //       voucher: voucher || null,
// //       note: note,
// //       items: cart.map((item) => ({
// //         product_id: item.id,
// //         quantity: item.quantity,
// //         price: item.price,
// //       })),
// //     };

// //     try {
// //       const response = await api.post("/orders", orderData);
// //       alert("Đặt hàng thành công!");
// //       console.log("Order response:", response.data);
// //       // Chuyển hướng hoặc làm mới giỏ hàng nếu cần
// //     } catch (error) {
// //       console.error("Lỗi đặt hàng:", error);
// //       alert("Đặt hàng thất bại, vui lòng thử lại!");
// //     }
// //   };

// //   return (
// //     <div className="checkout-container">
// //       <div className="checkout-left">
// //         <h2>Thanh toán & Vận chuyển</h2>
// //         <form>
// //           <label>Họ và tên *</label>
// //           <input type="text" value={user?.name || ""} required />

// //           <label>Email *</label>
// //           <input type="email" value={user?.email || ""} required />

// //           <label>Địa chỉ *</label>
// //           <input type="text" value={address} onChange={(e) => setAddress(e.target.value)} required />

// //           <label>Mã giảm giá</label>
// //           <input type="text" value={voucher} onChange={(e) => setVoucher(e.target.value)} placeholder="Nhập mã giảm giá" />

// //           <label>Phương thức thanh toán *</label>
// //           <select value={paymentMethod} onChange={(e) => setPaymentMethod(e.target.value)}>
// //             <option value="cod">Thanh toán khi nhận hàng</option>
// //             <option value="bank">Chuyển khoản ngân hàng</option>
// //           </select>

// //           <label>Ghi chú</label>
// //           <textarea value={note} onChange={(e) => setNote(e.target.value)} placeholder="Ghi chú về đơn hàng"></textarea>
// //         </form>
// //       </div>

// //       <div className="checkout-right">
// //         <h2>Đơn hàng của bạn</h2>
// //         {cart.map((item) => (
// //           <div className="product" key={item.id}>
// //             <img src={item.image} alt={item.name} className="product-image" />
// //             <div className="product-details">
// //               <p className="product-name">{item.name}</p>
// //               <p className="product-quantity">x{item.quantity}</p>
// //               <p className="product-price">{item.price.toLocaleString()}đ</p>
// //             </div>
// //           </div>
// //         ))}
// //         <hr />
// //         <div className="price-details">
// //           <p>
// //             Tổng: <span>{totalAmount.toLocaleString()}đ</span>
// //           </p>
// //           <p>
// //             Phí ship: <span>{shippingFee.toLocaleString()}đ</span>
// //           </p>
// //           <p className="total">
// //             Tổng cộng: <strong>{(totalAmount + shippingFee).toLocaleString()}đ</strong>
// //           </p>
// //         </div>

// //         <button className="order-button" onClick={handleOrder}>ĐẶT HÀNG</button>
// //       </div>
// //     </div>
// //   );
// // };

// // export default CheckOut;

import { useEffect, useState } from "react";
import { useSelector } from "react-redux";
import { RootState } from "../store/store";
import { CartItem, fetchCart } from "../services/cart";
import { getOrder } from "../services/Order";
import axios from "axios";

interface Address {
  _id: string;
  name: string;
  slug: string;
  type: string;
  name_with_type: string;
  code: number;
}

interface OrderItem {
  product_id: string;
  quantity: number;
  unit_price: number;
}

export interface OrderData {
  order_id?: string | null;
  user_id: string;
  recipient: {
    name: string;
    email: string;
    phone: string;
  };
  shipping_address: {
    province: string;
    district: string;
    ward: string;
    detail: string;
  };
  total_amount: number;
  shipping_fee: number;
  payment_method: "cash_on_delivery" | "bank_transfer";
  payment_date: string | null;
  voucher_id?: string | null;
  note?: string;
  status: "pending" | "confirmed" | "shipped" | "delivered" | "cancelled";
  items: OrderItem[];
}

const CheckOut = () => {
  const [cart, setCart] = useState<CartItem[]>([]);
  const [provinces, setProvinces] = useState<Address[]>([]);
  const [districts, setDistricts] = useState<Address[]>([]);
  const [wards, setWards] = useState<Address[]>([]);

  const [selectedProvince, setSelectedProvince] = useState<string>("");
  const [selectedDistrict, setSelectedDistrict] = useState<string>("");
  const [selectedWard, setSelectedWard] = useState<string>("");

  const [address, setAddress] = useState<string>("");
  const [note, setNote] = useState<string>("");
  const [voucher, setVoucher] = useState<string>("");
  const [paymentMethod, setPaymentMethod] = useState<string>("cod");
  const [phone, setPhone] = useState<string>("");

  const user = useSelector((state: RootState) => state.user.user);
  console.log("user",user)
  useEffect(() => {
    fetchCart().then((data) => {
      setCart(data);
    });
  }, []);

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

  const total = cart.reduce(
    (sum, item) => sum + item.discount_price * item.quantity,
    0
  );
  const shippingFee = 30000;
  const grandTotal = total + shippingFee;

  const handleOrder = async () => {
    if (!user || !address || !selectedProvince || !selectedDistrict || !selectedWard || cart.length === 0) {
      alert("Vui lòng nhập đầy đủ thông tin và kiểm tra giỏ hàng!");
      return;
    }

    const orderData: OrderData = {
      order_id: null,
      user_id: String(user.id), 
      recipient: {
        name: user.name,
        email: user.email,
        phone: phone,
      },
      shipping_address: {
        province: selectedProvince,
        district: selectedDistrict,
        ward: selectedWard,
        detail: address,
      },
      total_amount: grandTotal,
      shipping_fee: shippingFee,
      payment_method: paymentMethod === "cod" ? "cash_on_delivery" : "bank_transfer",
      payment_date: paymentMethod === "cod" ? null : new Date().toISOString(),
      voucher_id: voucher || null,
      note: note || "",
      status: "pending",
      items: cart.map((item) => ({
        product_id: String(item.product_detail_id), // Chuyển thành string
        quantity: item.quantity,
        unit_price: item.discount_price,
      })),
    };
    try {
      const response = await getOrder(orderData);
      alert("Đặt hàng thành công!");
      console.log("Order response:", response.data);
    } catch (error: unknown) {
      if (axios.isAxiosError(error)) {
        console.error("Lỗi đặt hàng:", error.response?.data || error.message);
        alert(`Lỗi đặt hàng: ${error.response?.data?.message || "Có lỗi xảy ra!"}`);
      } else {
        console.error("Lỗi không xác định:", error);
        alert("Đã có lỗi xảy ra, vui lòng thử lại!");
      }
    }
  };
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
            <input type="text" value={user?.name || ""} readOnly />

            <label>Số điện thoại *</label>
            <input
              type="text"
              value={phone}
              onChange={(e) => setPhone(e.target.value)}
              required
            />

            <label>Email *</label>
            <input type="email" value={user?.email || ""} readOnly />

            <label>Tỉnh/Thành phố *</label>
            <select
              onChange={(e) => setSelectedProvince(e.target.value)}
              value={selectedProvince}
            >
              <option value="">Chọn tỉnh/thành phố</option>
              {provinces?.map((province) => (
                <option key={province.code} value={province.code}>
                  {province.name}
                </option>
              ))}
            </select>

            <label>Quận/Huyện *</label>
            <select
              onChange={(e) => setSelectedDistrict(e.target.value)}
              value={selectedDistrict}
              disabled={!selectedProvince}
            >
              <option value="">Chọn quận/huyện</option>
              {districts?.map((district) => (
                <option key={district.code} value={district.code}>
                  {district.name}
                </option>
              ))}
            </select>

            <label>Xã/Phường *</label>
            <select
              onChange={(e) => setSelectedWard(e.target.value)}
              value={selectedWard}
              disabled={!selectedDistrict}
            >
              <option value="">Chọn xã/phường</option>
              {wards?.map((ward) => (
                <option key={ward.code} value={ward.code}>
                  {ward.name}
                </option>
              ))}
            </select>

            <label>Địa chỉ *</label>
            <input
              type="text"
              value={address}
              onChange={(e) => setAddress(e.target.value)}
              required
            />

            <label>Ghi chú</label>
            <input
              type="text"
              value={note}
              onChange={(e) => setNote(e.target.value)}
            />

            <label>Mã giảm giá</label>
            <input
              type="text"
              value={voucher}
              onChange={(e) => setVoucher(e.target.value)}
            />
          </form>
        </div>

        <div className="checkout-right">
          <h2>Đơn hàng của bạn</h2>
          {cart.map((item) => (
            <div className="order-summary" key={item.product_detail_id}>
              <div className="product">
                <img
                  src={item.image}
                  alt={item.product_name}
                  className="product-image"
                />
                <div className="product-details">
                  <p className="product-name">{item.product_name}</p>
                  <p className="product-quantity">x{item.quantity}</p>
                  <p className="product-price">
                    {(item.discount_price * item.quantity).toLocaleString()}đ
                  </p>
                </div>
              </div>
              <hr />
            </div>
          ))}

          <div className="price-details">
            <p>
              Tổng: <span>{total.toLocaleString()}đ</span>
            </p>
            <p>
              Phí ship: <span>{shippingFee.toLocaleString()}đ</span>
            </p>
            <p className="total">
              Tổng cộng: <strong>{grandTotal.toLocaleString()}đ</strong>
            </p>
          </div>

          <div className="payment-method">
            <label className="payment-option">
              <input
                type="radio"
                name="payment"
                value="cod"
                checked={paymentMethod === "cod"}
                onChange={() => setPaymentMethod("cod")}
                 className="payment-checkbox square"
              />
              <span>Trả tiền mặt khi nhận hàng</span>
            </label>
            <label className="payment-option">
              <input
                type="radio"
                name="payment"
                value="bank"
                checked={paymentMethod === "bank"}
                onChange={() => setPaymentMethod("bank")}
                 className="payment-checkbox square"
              />
              <span>Chuyển khoản ngân hàng</span>
            </label>
          </div>
          {/* <div className="payment-method">
              <label className="payment-option">
                <input
                  type="radio"
                  name="payment"
                  defaultChecked
                  className="payment-checkbox square"
                />
                <span>Trả tiền mặt khi nhận hàng</span>
                <p className="payment-description">
                  Bạn đặt hàng và thanh toán sau khi nhận hàng.
                </p>
              </label>
              <hr className="payment-divider" />
              <label className="payment-option">
                <input
                  type="radio"
                  name="payment"
                  className="payment-checkbox square"
                />
                <span>Chuyển khoản ngân hàng</span>
                <p className="payment-description">
                  Thanh toán qua ngân hàng trước khi giao hàng.
                </p>
              </label>
            </div> */}
          <button className="order-button" onClick={handleOrder}>
            ĐẶT HÀNG
          </button>
        </div>
      </div>
    </>
  );
};

export default CheckOut;
