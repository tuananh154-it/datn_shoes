
const ForgotPassword = () => {
  return (
   <>
    <div className="menu_overlay"></div>
    <div className="main_section">
        <section className="breadcrumb_section nav">
            <div className="container">
                <nav aria-label="breadcrumb">
                    <ol className="breadcrumb">
                        <li className="breadcrumb-item text-capitalize"><a href="earthyellow.html">Home</a> <i className="flaticon-arrows-4"></i></li>
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
                            <form>
                                <p>Chúng tôi sẽ gửi cho bạn email để đặt lại mật khẩu.</p>
                                <div className="form-group">
                                    <label htmlFor="email" className="title_h5">Email*</label>
                                    <input type="email" className="form-control" id="email" name="Email" required />
                                </div>
                                <div className="login_links ">
                                    <button type="submit" className="btn background-btn text-uppercase">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   </>
  )
}

export default ForgotPassword
