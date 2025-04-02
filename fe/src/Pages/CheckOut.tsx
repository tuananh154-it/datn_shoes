
import { useEffect, useState } from "react";
import { getCheckOut, getOrder, Momopayment } from "../services/Order";
import toast from "react-hot-toast";
import { useNavigate } from "react-router-dom";
import { FaCcVisa, FaMoneyBillWave, FaMobileAlt } from "react-icons/fa";
// import { string } from "zod";
import { useCart } from "../context/CartContext";
import { AxiosError } from "axios";
interface Address {
  _id: string;
  name: string;
  slug: string;
  type: string;
  name_with_type: string;
  code: number;
}

interface CartItem {
  id:number
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
  selected_items: CartItem[];  // Add this property to the interface
}
const CheckOut = () => {
  const [provinces, setProvinces] = useState<Address[]>([]);
  const [districts, setDistricts] = useState<Address[]>([]);
  const [wards, setWards] = useState<Address[]>([]);

  const [selectedProvince, setSelectedProvince] = useState<string>("");
  const [selectedDistrict, setSelectedDistrict] = useState<string>("");
  const [selectedWard, setSelectedWard] = useState<string>("");

  // const [address, setAddress] = useState<string>("");
  // const [note, setNote] = useState<string>("");
  // const [voucher, setVoucher] = useState<string>("");
  const [paymentMethod, setPaymentMethod] = useState<string>("COD");
  // const [phone, setPhone] = useState<string>("");

  const [checkout, setCheckout] = useState<CheckoutData | null>(null);
  const [selectedItems, setSelectedItems] = useState<number[]>([]);
    console.log("checkout",checkout)
    useEffect(() => {
      const storedSelectedItems = localStorage.getItem("selectedItems");
      if (storedSelectedItems) {
        const parsedItems = JSON.parse(storedSelectedItems);
        setSelectedItems(parsedItems);
      }
    }, []);
    console.log("checkout",checkout)
  
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
    e.preventDefault();

    if (!checkout) {
      alert("Không có dữ liệu đơn hàng!");
      return;
    }

    if (paymentMethod === "paypal") {
      // Gọi API MoMo trước
      const uniqueOrderId = `ORDER_${new Date().getTime()}`;

      const momoData = {
        amount: checkout.total,
        orderId: uniqueOrderId, // Mã đơn hàng tạm thời
        redirectUrl: window.location.origin + "/momo-success", // Trang xử lý sau khi thanh toán MoMo
      };

      console.log("📦 Dữ liệu gửi API MoMo:", momoData);

      try {
        const momoResponse = await Momopayment(momoData);
        console.log("✅ MoMo API Response:", momoResponse);

        const payUrl = momoResponse.data?.payUrl;
        console.log("🔗 MoMo PayUrl:", payUrl);

        if (payUrl) {
          localStorage.setItem("pendingOrder", JSON.stringify(checkout)); // Lưu đơn hàng tạm vào localStorage
          window.location.href = payUrl; // Chuyển hướng đến MoMo
          return;
        } else {
          alert("⚠️ API MoMo không trả về URL thanh toán!");
          return;
        }
      } catch (error) {
        console.error("❌ Lỗi khi gọi API MoMo:", error);
        alert("Lỗi khi tạo thanh toán MoMo!");
        return;
      }
    }

    // Nếu chọn "Thanh toán khi nhận hàng" thì đặt hàng ngay
    if (paymentMethod === "cash_on_delivery") {
      processOrder();
    }
    if (checkout?.user.address) {
      const storedAddresses = JSON.parse(localStorage.getItem("savedAddresses") || "[]");
  
      // Thêm địa chỉ mới vào đầu danh sách, loại bỏ trùng lặp và giữ tối đa 2 địa chỉ
      const updatedAddresses = [checkout.user.address, ...storedAddresses]
        .filter((addr, index, self) => self.indexOf(addr) === index) // Loại bỏ địa chỉ trùng
        .slice(0, 2); // Giữ tối đa 2 địa chỉ gần nhất
  
      localStorage.setItem("savedAddresses", JSON.stringify(updatedAddresses));
    }
  };

  // Hàm xử lý đặt hàng
  // const processOrder = async () => {
  //   const savedOrder = localStorage.getItem("pendingOrder");
  //   if (!checkout) {
  //     alert("Không có dữ liệu đơn hàng!");
  //     return;
  //   }
  //   const orderData = {
  //     user_id: checkout?.user.id, // User ID
  //     username: checkout?.user.name, // User Name
  //     phone_number: checkout?.user.phone_number, // User Phone Number
  //     email: checkout?.user.email, // User Email
  //     address: checkout?.user.address, // User Address
  //     note: (document.getElementById("note") as HTMLInputElement)?.value || "", // Note from input field
  //     payment_method: paymentMethod, // Payment Method
  //     selected_items: (checkout?.selected_items || []).map(item => ({
  //       id: item.id.toString(), // Ensure the ID is a string
  //       product_name: item.product_name, // Product Name
  //       image: item.image, // Image URL or path
  //       size: item.size, // Size
  //       color: item.color, // Color
  //       price: item.price, // Price
  //       quantity: item.quantity, // Quantity
  //       // line_total: item.line_total, // Line Total (Price * Quantity)
  //     })),
  //     // voucher_id: checkout?.voucher ? checkout.voucher.id : null, // If there's a voucher, include the voucher_id
  //   };
    
  //   console.log("🚀 Sending Order Data:", orderData);

  //   try {
  //     const orderResponse = await getOrder(orderData);
  //     console.log("✅ Order API Response:", orderResponse);

  //     if (orderResponse.status !== 201) {
  //       alert(`Đặt hàng thất bại! Mã lỗi: ${orderResponse.status}`);
  //       return;
  //     }

  //     toast.success("🎉 Đã đặt hàng thành công!");
  //     localStorage.removeItem("pendingOrder"); // Xóa đơn hàng tạm sau khi đặt hàng thành công
  //     nav("/");
  //   } catch (error) {
  //     console.error("❌ Lỗi xử lý đơn hàng:", error);
  //     alert("Có lỗi xảy ra, vui lòng thử lại!");
  //   }
  // };
  const delay = (ms: number) => new Promise(resolve => setTimeout(resolve, ms));
  const processOrder = async () => {
    const savedOrder = localStorage.getItem("pendingOrder");
    
    // Step 1: Check if checkout data is available
    if (!checkout) {
      console.error("Checkout data is missing.");
      alert("Không có dữ liệu đơn hàng!");
      return;
    }
  
    // Log checkout data to ensure it has the expected structure
    console.log("Checkout Data:", checkout);
  
    // Step 2: Prepare the order data
    const orderData = {
      user_id: checkout?.user.id,
      username: checkout?.user.name,
      phone_number: checkout?.user.phone_number,
      email: checkout?.user.email,
      address: checkout?.user.address,
      note: (document.getElementById("note") as HTMLInputElement)?.value || "",
      payment_method: paymentMethod,
      selected_items: (checkout?.selected_items || []).map(item => item.id), // ❗ CHỈ LẤY ID
    };
  
    // Log the order data before sending it
    console.log("🚀 Sending Order Data:", orderData);
  
    try {
      await delay(2000);
      // Step 3: Make the API call to place the order
      const orderResponse = await getOrder(orderData);
  
      // Log the API response to inspect the result
      console.log("✅ Order API Response:", orderResponse);
  
      // Step 4: Check for successful order response
      if (orderResponse.status !== 201) {
        console.error(`Order failed with status: ${orderResponse.status}`);
        alert(`Đặt hàng thất bại! Mã lỗi: ${orderResponse.status}`);
        return;
      }
  
      // Step 5: Handle success
      toast.success("🎉 Đã đặt hàng thành công!");
      localStorage.removeItem("pendingOrder"); // Xóa đơn hàng tạm sau khi đặt hàng thành công
      nav("/");
  
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
  }
  

  // Kiểm tra nếu MoMo thanh toán xong
  useEffect(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const momoStatus = urlParams.get("momoStatus");

    if (momoStatus === "success") {
      processOrder(); // Đặt hàng sau khi thanh toán MoMo thành công
    }
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
  const [specificAddress, setSpecificAddress] = useState("");

  const updateAddress = (newValue?: string) => {
    setCheckout((prev) =>
      prev
        ? {
            ...prev,
            user: {
              ...prev.user,
              address: `${newValue || specificAddress}, ${
                wards.find((w) => String(w.code) === selectedWard)?.name || ""
              }, ${
                districts.find((d) => String(d.code) === selectedDistrict)?.name || ""
              }, ${
                provinces.find((p) => String(p.code) === selectedProvince)?.name || ""
              }`,
            },
          }
        : null
    );
  };
  const [savedAddresses, setSavedAddresses] = useState<string[]>([]);

useEffect(() => {
  const storedAddresses = JSON.parse(localStorage.getItem("savedAddresses") || "[]");
  setSavedAddresses(storedAddresses);
}, []);
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
          {/* <form>
            <label>Họ và tên *</label>
            <input
              type="text"
              id="name"
              value={checkout?.user.name || ""}
              readOnly
            />

            <label>Số điện thoại *</label>
            <input
              type="text"
              id="phone_number"
              value={checkout?.user.phone_number || ""}
              readOnly
            />

            <label>Email *</label>
            <input
              type="email"
              id="email"
              value={checkout?.user.email || ""}
              readOnly
            />

            <label>Địa chỉ *</label>
            <input
              type="text"
              id="address"
              value={checkout?.user.address || ""}
              readOnly
            />

            <label>Ghi chú</label>
            <input type="text" id="note" />
          </form> */}
          <form>
  <label>Họ và tên *</label>
  <input
    type="text"
    id="name"
    value={checkout?.user.name || ""}
    onChange={(e) =>
      setCheckout((prev) =>
        prev ? { ...prev, user: { ...prev.user, name: e.target.value } } : null
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
        prev ? { ...prev, user: { ...prev.user, phone_number: e.target.value } } : null
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
        prev ? { ...prev, user: { ...prev.user, email: e.target.value } } : null
      )
    }
  />

{/* <label>Tỉnh/Thành phố *</label>
    <select
      value={selectedProvince}
      onChange={(e) => {
        setSelectedProvince(e.target.value);
        updateAddress(e.target.options[e.target.selectedIndex].text);
      }}
    >
      <option value="">Chọn tỉnh/thành phố</option>
      {provinces?.map((p) => (
        <option key={p.code} value={p.code}>{p.name}</option>
      ))}
    </select>

    <label>Quận/Huyện *</label>
    <select
      value={selectedDistrict}
      onChange={(e) => {
        setSelectedDistrict(e.target.value);
        updateAddress(e.target.options[e.target.selectedIndex].text);
      }}
      disabled={!selectedProvince}
    >
      <option value="">Chọn quận/huyện</option>
      {districts.map((d) => (
        <option key={d.code} value={d.code}>{d.name}</option>
      ))}
    </select>

    <label>Phường/Xã *</label>
    <select
      value={selectedWard}
      onChange={(e) => {
        setSelectedWard(e.target.value);
        updateAddress(e.target.options[e.target.selectedIndex].text);
      }}
      disabled={!selectedDistrict}
    >
      <option value="">Chọn phường/xã</option>
      {wards.map((w) => (
        <option key={w.code} value={w.code}>{w.name}</option>
      ))}
    </select>

    <label>Địa chỉ *</label>
    <input
      type="text"
      id="address"
      value={checkout?.user.address || ""}
      onChange={(e) =>
        setCheckout((prev) =>
          prev ? { ...prev, user: { ...prev.user, address: e.target.value } } : null
        )
      }
    /> */}
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
  setSpecificAddress(e.target.value); // Giữ nguyên chuỗi người dùng nhập
  updateAddress(e.target.value); // Cập nhật lại địa chỉ đầy đủ
}}
    />

    <label>Địa chỉ đầy đủ *</label>
    <input
      type="text" 
      id="address"
      value={checkout?.user.address || ""}
      onChange={(e) =>
        setCheckout((prev) =>
          prev ? { ...prev, user: { ...prev.user, address: e.target.value } } : null
        )
      }
    />
    {savedAddresses.length > 0 && (
  <div style={{ marginTop: "5px" }}>
   {savedAddresses.length > 0 && (
  <div style={{ marginTop: "5px" }}>
    {savedAddresses.map((addr, index) => (
      <p 
        key={index} 
        onClick={() => setCheckout((prev) => prev ? { ...prev, user: { ...prev.user, address: addr } } : null)}
        style={{ cursor: "pointer", color: "#888", marginBottom: "3px" }}
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
          {/* {checkout?.cart_items.map((item, index) => (
            <div className="order-summary" key={index}>
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
                    {(parseFloat(item.price) * item.quantity).toLocaleString()}đ
                  </p>
                </div>
              </div>
              <hr />
            </div>
          ))} */}
          {checkout?.selected_items?.length > 0 ? (
  checkout.selected_items.map((item, index) => {
    // Assuming image is a JSON string that contains an array of image URLs.
    const images = JSON.parse(item.image); // Parse image string to get an array of images
    const productImage = images[0]; // You can pick the first image from the array

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
              {(parseFloat(item.price) * item.quantity).toLocaleString()}đ
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
          <label className={`payment-card ${paymentMethod === "credit_card" ? "active" : ""}`}>
        <input
            type="radio"
            name="payment"
            value="credit_card"
            checked={paymentMethod === "credit_card"}
            onChange={() => setPaymentMethod("credit_card")}
        />
        <FaCcVisa className="payment-icon visa" />
        <span>Thanh toán bằng Visa/Master/JCB</span>
    </label>

    <label className={`payment-card ${paymentMethod === "cash_on_delivery" ? "active" : ""}`}>
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

    <label className={`payment-card ${paymentMethod === "paypal" ? "active" : ""}`}>
        <input
            type="radio"
            name="payment"
            value="paypal"
            checked={paymentMethod === "paypal"}
            onChange={() => setPaymentMethod("paypal")}
        />
        <FaMobileAlt className="payment-icon momo" />
        <span>Thanh toán bằng Ví MoMo</span>
    </label>
          </div>

          <button type="submit" className="order-button" onClick={handleOrder}>
            ĐẶT HÀNG
          </button>
        </div>
      </div>
    </>
  );
};

export default CheckOut;

