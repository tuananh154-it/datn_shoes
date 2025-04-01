import { useState } from "react";
import { sendForgotPasswordEmail } from "../services/user";
import { Link, useNavigate } from "react-router-dom";  // Dùng useNavigate để điều hướng trong react-router-dom v6+

const ForgotPassword = () => {
    const [email, setEmail] = useState("");
    const [message, setMessage] = useState("");
    const [loading, setLoading] = useState(false);
    const [isEmailSent, setIsEmailSent] = useState(false); // Trạng thái gửi email thành công
    const navigate = useNavigate();  // Dùng useNavigate thay vì useHistory

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);

        try {
            const response = await sendForgotPasswordEmail(email);
            setMessage(response.data.message); // Hiển thị thông báo thành công
            setIsEmailSent(true); // Đánh dấu email đã được gửi thành công
        } catch (error) {
            setMessage("Có lỗi xảy ra. Vui lòng thử lại.");
        } finally {
            setLoading(false);
        }
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
                                    <a href="earthyellow.html">Trang chủ</a> <i className="flaticon-arrows-4"></i>
                                </li>
                                <li className="breadcrumb-item active text-capitalize">Đặt lại mật khẩu</li>
                            </ol>
                        </nav>
                        <h1 className="title_h1 font-weight-normal text-capitalize">Đặt lại mật khẩu</h1>
                    </div>
                </section>
                <div className="login_section reset_password_section padding-top-60 padding-bottom-60">
                    <div className="container">
                        <div className="row">
                            <div className="col-lg-6">
                                <div className="login_form">
                                    <form onSubmit={handleSubmit}>
                                        <p>Chúng tôi sẽ gửi cho bạn email để đặt lại mật khẩu.</p>
                                        <div className="form-group">
                                            <label htmlFor="email" className="title_h5">Email*</label>
                                            <input
                                                type="email"
                                                className="form-control"
                                                id="email"
                                                name="Email"
                                                value={email}
                                                onChange={(e) => setEmail(e.target.value)}
                                                required
                                            />
                                        </div>
                                        <div className="login_links">
                                            <button type="submit" className="btn background-btn text-uppercase" disabled={loading}>
                                                {loading ? "Đang gửi..." : "Gửi"}
                                            </button>
                                        </div>
                                        {message && <p>{message}</p>}

                                        {/* Hiển thị link khi email đã gửi thành công */}
                                        {isEmailSent && (
                                            <div>
                                                <p>
                                                    {/* <a href="/reset-password" style={{ fontWeight: 'bold', color: 'red' }}> đây để đặt lại mật khẩu</a>. */}
                                                    <Link to="/reset-password"style={{ fontWeight: 'bold', color: 'red' }}>Vào đây để đặt lại mật khẩu</Link>
                                                </p>
                                                {/* Nếu bạn dùng react-router-dom và muốn điều hướng bằng button */}
                                                {/* <p>Email đã được gửi thành công! Bạn có thể đi đến <button onClick={() => navigate('/reset-password')}>đặt lại mật khẩu</button></p> */}
                                            </div>
                                        )}
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default ForgotPassword;
