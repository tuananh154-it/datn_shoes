import { useState, useEffect } from "react";
import { Link, useNavigate, useParams } from "react-router-dom";
import { resetPassword } from "../services/user"; // Đảm bảo import đúng hàm resetPassword

const ResetPassword = () => {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [confirmPassword, setConfirmPassword] = useState("");
    const [message, setMessage] = useState("");
    const [loading, setLoading] = useState(false);
    const [isPasswordUpdated, setIsPasswordUpdated] = useState(false);
    const [token, setToken] = useState(''); // Token cần gửi

    const navigate = useNavigate();

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        // Kiểm tra mật khẩu và xác nhận mật khẩu có trùng nhau không
        if (password !== confirmPassword) {
            setMessage("Mật khẩu và xác nhận mật khẩu không khớp.");
            return;
        }

        setLoading(true);

        try {
            // Gửi yêu cầu đặt lại mật khẩu lên backend
            const response = await resetPassword(token!, email, password, confirmPassword);
            setMessage(response.data.message);  // Hiển thị thông báo thành công
            setIsPasswordUpdated(true);  // Đánh dấu đã cập nhật mật khẩu thành công
            console.log("Phản hồi từ backend:", response.data);

        } catch (error) {
            console.error("Lỗi từ backend:", error.response?.data || error);
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
                                        <p>Vui lòng nhập thông tin để đặt lại mật khẩu của bạn.</p>
                                        <div className="form-group">
                                            <label htmlFor="email" className="title_h5">Email*</label>
                                            <input
                                                type="email"
                                                className="form-control"
                                                id="email"
                                                name="email"
                                                value={email}
                                                onChange={(e) => setEmail(e.target.value)}
                                                required
                                            />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="token" className="title_h5">Token*</label>
                                            <input
                                                type="text"
                                                className="form-control"
                                                id="token"
                                                name="token"
                                                value={token}
                                                onChange={(e) => setToken(e.target.value)}
                                                required
                                            />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="password" className="title_h5">Mật khẩu mới*</label>
                                            <input
                                                type="password"
                                                className="form-control"
                                                id="password"
                                                name="password"
                                                value={password}
                                                onChange={(e) => setPassword(e.target.value)}
                                                required
                                            />
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="confirmPassword" className="title_h5">Xác nhận mật khẩu*</label>
                                            <input
                                                type="password"
                                                className="form-control"
                                                id="confirmPassword"
                                                name="confirmPassword"
                                                value={confirmPassword}
                                                onChange={(e) => setConfirmPassword(e.target.value)}
                                                required
                                            />
                                        </div>
                                        <div className="login_links">
                                            <button type="submit" className="btn background-btn text-uppercase" disabled={loading}>
                                                {loading ? "Đang cập nhật..." : "Cập nhật mật khẩu"}
                                            </button>
                                        </div>
                                        {message && <p>{message}</p>}

                                        {isPasswordUpdated && (
                                            <div>
                                                {/* <p>Mật khẩu của bạn đã được cập nhật thành công. Bạn có thể quay lại trang đăng nhập.</p>
                                                <button onClick={() => navigate('/login')} className="btn background-btn text-uppercase">
                                                    Đăng nhập
                                                </button> */}
                                                <Link to="/login"style={{ fontWeight: 'bold', color: 'red' }}>Quay lại đăng nhập</Link>
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

export default ResetPassword;
