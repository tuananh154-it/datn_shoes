const Register = () => {
  return (
    <>
      <div className="menu_overlay"></div>
      <div className="main_section">
        <section className="breadcrumb_section nav">
          <div className="container">
            <nav aria-label="breadcrumb">
              <ol className="breadcrumb">
                <li className="breadcrumb-item text-capitalize">
                  <a href="earthyellow.html">Home</a>{" "}
                  <i className="flaticon-arrows-4"></i>
                </li>
                <li className="breadcrumb-item active text-capitalize">
                  Đăng ký
                </li>
              </ol>
            </nav>
            <h1 className="title_h1 font-weight-normal text-capitalize">
              Đăng ký
            </h1>
          </div>
        </section>
        <section className="login_section register_section padding-top-text-60 padding-bottom-60">
          <div className="container">
            <div className="row">
              <div className="col-lg-6 border-right">
                <div className="login_form">
                  <form>
                    <div className="form-group">
                      <label htmlFor="fname" className="title_h5">
                        Họ*
                      </label>
                      <input
                        type="text"
                        className="form-control"
                        id="fname"
                        name="Firstname"
                        required
                      />
                    </div>
                    <div className="form-group">
                      <label className="title_h5" htmlFor="lname">
                        Tên*
                      </label>
                      <input
                        type="text"
                        className="form-control"
                        id="lname"
                        name="Lastname"
                        required
                      />
                    </div>
                    <div className="form-group">
                      <label htmlFor="email" className="title_h5">
                        Email*
                      </label>
                      <input
                        type="email"
                        className="form-control"
                        id="email"
                        name="Email"
                        required
                      />
                    </div>
                    <div className="form-group">
                      <label htmlFor="password" className="title_h5">
                        Mật khẩu*
                      </label>
                      <input
                        type="password"
                        className="form-control"
                        id="password"
                        name="Password"
                        required
                      />
                    </div>
                    <div className="login_links ">
                      <button
                        type="submit"
                        className="btn background-btn text-uppercase"
                      >
                        Đăng ký
                      </button>
                      <p>or</p>
                      <a className="btn-link return_text px-2" href="/shop">
                        Quay lại cửa hàng
                      </a>
                    </div>
                  </form>
                </div>
              </div>
              {/* <div className="col-lg-6 align-self-center dangnhap">
                            <div className="create_account">
                                <h3 className="text-center title_h3 font-weight-normal">Đăng nhập</h3>
                                <p className="text-center">Nếu bạn đã đăng ký. Vui lòng <a href="/login"><u>click here</u></a> để đăng nhập vào tài khoản của bạn.</p>
                            </div>
                        </div> */}
              <div className="col-lg-6 align-self-center dangnhap">
                <div className="image-overlay">
                  <img
                    src="https://toplinemanagement.com/wp-content/uploads/2017/01/top-line-management-login-background-1.jpg"
                    alt="Background"
                    className="background-image"
                  />
                  <div className="overlay-text">
                    <h3 className="text-center title_h3 font-weight-normal">
                      Đăng nhập
                    </h3>
                    <p className="text-center">
                      Nếu bạn đã đăng ký. Vui lòng{" "}
                      <a href="/login">
                        <u className="text">click here</u>
                      </a>{" "}
                      để đăng nhập vào tài khoản của bạn.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </>
  );
};

export default Register;
