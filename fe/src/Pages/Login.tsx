
const Login = () => {
  return (
   <>
    <div className="menu_overlay"></div>
        <div className="main_section">
            <section className="breadcrumb_section nav">
                <div className="container">
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb">
                            <li className="breadcrumb-item text-capitalize"><a href="earthyellow.html">Home</a> <i className="flaticon-arrows-4"></i></li>
                            <li className="breadcrumb-item active text-capitalize">Đăng nhập</li>
                        </ol>
                    </nav>
                    <h1 className="title_h1 font-weight-normal text-capitalize">Đăng nhập</h1>
                </div>
            </section>
            <section className="login_section padding-top-text-60 padding-bottom-60">
                <div className="container">
                    <div className="row">
                        <div className="col-lg-6 border-right">
                            <div className="login_form">
                                <form>
                                    <div className="form-group">
                                        <label htmlFor="email" className="title_h5">Email</label>
                                        <input type="email" className="form-control" id="email" name="Email"/>
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="password" className="title_h5">Password</label>
                                        <input type="password" className="form-control" id="password" name="Password"/>
                                    </div>
                                    <div className="login_links">
                                        <button type="submit" className="btn background-btn text-uppercase">Đăng nhập</button>
                                        <p>or</p>
                                        <a className="btn-link return_text" href="/shop">Quay lại cửa hàng</a>
                                        <a className="btn-link text-right forgot_text" href="/quenmatkhau"><u>Quên mật khẩu?</u></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div className="col-lg-6 align-self-center">
                            <div className="create_account">
                                <h3 className="text-center title_h3 font-weight-normal">Tạo tài khoản</h3>
                                <p className="text-center">Nếu bạn chưa đăng ký. Vui lòng <a href="register"><u>click here</u></a> để tạo một tài khoản.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
   </>
  )
}

export default Login
