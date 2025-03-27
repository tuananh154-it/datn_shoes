import { useEffect, useState } from "react";

interface Address {
  _id: string;
  name: string;
  slug: string;
  type: string;
  name_with_type: string;
  code: number;
}
const CheckOut = () => {
  const [provinces, setProvinces] = useState<Address[]>([]);
  const [districts, setDistricts] = useState<Address[]>([]);
  const [wards, setWards] = useState<Address[]>([]);
  const [selectedProvince, setSelectedProvince] = useState<string>("");
  const [selectedDistrict, setSelectedDistrict] = useState<string>("");
  useEffect(() => {
    fetch("https://vn-public-apis.fpo.vn/provinces/getAll?limit=-1")
      .then((response) => response.json())
      .then((data) => {
        setProvinces(data.data.data);
      })
      .catch((error) => console.error("error", error));
  }, []);
  useEffect(() => {
    if (selectedProvince) {
      fetch(
        `https://vn-public-apis.fpo.vn/districts/getByProvince?provinceCode=${selectedProvince}&limit=-1`
      )
        .then((response) => response.json())
        .then((data) => {
          setDistricts(data.data.data);
          setWards([]); // Reset danh sách xã/phường khi đổi tỉnh
        })
        .catch((error) => console.error("error", error));
    }
  }, [selectedProvince]);
  useEffect(() => {
    if (selectedDistrict) {
      fetch(
        `https://vn-public-apis.fpo.vn/wards/getByDistrict?districtCode=${selectedDistrict}&limit=-1`
      )
        .then((response) => response.json())
        .then((data) => {
          setWards(data.data.data);
        })
        .catch((error) => console.error("error", error));
    }
  }, [selectedDistrict]);
  return (
    <>
      <div className="menu_overlay"></div>
      {/* END Header */}
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
        {/* END Breadcrumb */}
        {/* START Checkout Section */}
      </div>
      <div className="checkout-container">
        <div className="checkout-left">
          <h2>Thanh toán & Vận chuyển</h2>
          <form>
            <label>Họ và tên *</label>
            <input type="text" placeholder="Họ và tên" required />

            <label>Số điện thoại *</label>
            <input type="text" placeholder="Số điện thoại của bạn" required />

            <label>Địa chỉ email (tùy chọn)</label>
            <input type="email" placeholder="Email của bạn" />

            <label>Tỉnh/Thành phố *</label>
            <select onChange={(e) => setSelectedProvince(e.target.value)}>
              <option value="">Chọn tỉnh/thành phố</option>
              {provinces.map((province) => (
                <option key={province.code} value={province.code}>
                  {province.name}
                </option>
              ))}
            </select>

            <label>Quận/Huyện *</label>
            <select
              onChange={(e) => setSelectedDistrict(e.target.value)}
              disabled={!selectedProvince}
            >
              <option value="">Chọn quận/huyện</option>
              {districts.map((district) => (
                <option key={district.code} value={district.code}>
                  {district.name}
                </option>
              ))}
            </select>

            <label>Xã/Phường *</label>
            <select disabled={!selectedDistrict}>
              <option value="">Chọn xã/phường</option>
              {wards.map((ward) => (
                <option key={ward.code} value={ward.code}>
                  {ward.name}
                </option>
              ))}
            </select>

            <label>Địa chỉ *</label>
            <input type="text" placeholder="Ví dụ: Số 20, ngõ 90" required />
          </form>
        </div>
        <div className="checkout-right">
          <h2>Đơn hàng của bạn</h2>
          <div className="order-summary">
            <div className="product">
              <img
                src="http://127.0.0.1:8000/storage/product_images/w6KYWJdg7hmcKas0wpiIZyq0OCcmPvgXolmRsyKp.jpg"
                alt="Giày Sneaker"
                className="product-image"
              />
              <div className="product-details">
                <p className="product-name">
                  Adidas Samba OG Trắng Kẻ Đen Mũi Da Lộn REP 1:1 - Trắng đen,
                  42
                </p>
                <p className="product-quantity">x3</p>
                <p className="product-price">1.500.000đ</p>
              </div>
            </div>
            <hr />
            <div className="price-details">
              <p>
                Tổng: <span>1.500.000đ</span>
              </p>
              <p>
                Phí ship: <span>30.000đ</span>
              </p>
              <p className="total">
                Tổng cộng: <strong>1.530.000đ</strong>
              </p>
            </div>
            <div className="payment-method">
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
            </div>
            <button className="order-button">ĐẶT HÀNG</button>
          </div>
        </div>
      </div>
    </>
  );
};

export default CheckOut;
