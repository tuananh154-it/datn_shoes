
import { useEffect, useState } from "react";
import { getCheckout, getOrder, Momopayment } from "../services/Order";
import toast from "react-hot-toast";
import { useNavigate } from "react-router-dom";
import { FaCcVisa, FaMoneyBillWave, FaMobileAlt } from "react-icons/fa";
interface Address {
  _id: string;
  name: string;
  slug: string;
  type: string;
  name_with_type: string;
  code: number;
}

interface CartItem {
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
  useEffect(() => {
    getCheckout().then(({ data }) => {
      console.log("checkout", data);
      setCheckout(data);
    });
  }, []);
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
  };

  // Hàm xử lý đặt hàng
  const processOrder = async () => {
    const savedOrder = localStorage.getItem("pendingOrder");
    if (!checkout) {
      alert("Không có dữ liệu đơn hàng!");
      return;
    }
    const orderData = {
      user_id: checkout.user.id,
      username: checkout.user.name,
      phone_number: checkout.user.phone_number,
      email: checkout.user.email,
      address: checkout.user.address,
      note: (document.getElementById("note") as HTMLInputElement)?.value || "",
      cart_items: checkout.cart_items,
      deliver_fee: checkout.deliver_fee,
      discount: checkout.discount,
      subtotal: checkout.subtotal,
      total: checkout.total,
      payment_method: paymentMethod,
    };

    console.log("🚀 Sending Order Data:", orderData);

    try {
      const orderResponse = await getOrder(orderData);
      console.log("✅ Order API Response:", orderResponse);

      if (orderResponse.status !== 201) {
        alert(`Đặt hàng thất bại! Mã lỗi: ${orderResponse.status}`);
        return;
      }

      toast.success("🎉 Đã đặt hàng thành công!");
      localStorage.removeItem("pendingOrder"); // Xóa đơn hàng tạm sau khi đặt hàng thành công
      nav("/");
    } catch (error) {
      console.error("❌ Lỗi xử lý đơn hàng:", error);
      alert("Có lỗi xảy ra, vui lòng thử lại!");
    }
  };

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
          </form>
        </div>
        <div className="checkout-right">
          <h2>Đơn hàng của bạn</h2>
          {checkout?.cart_items.map((item, index) => (
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
          ))}

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
{
  /* <div className="payment-method">
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
            </div> */
}
