const Footer = () => {
    return (
      <footer className="shoes_footer wow fadeIn animated">
        <div className="container">
          <div className="footer_top">
            <div className="row">
              <div className="col-lg-3 col-sm-6  wow fadeInUp ">
                <div className="column">
                  <a href="shoes.html">
                    <img src="../src/images/logo_footvibe_01.png" alt="logo" className="logo"/>
                  </a>
                  <p>Đồng hành trên mọi hành trình, nâng niu từng bước chân của các bạn</p>
                </div>
              </div>
              <div className="col-lg-3 col-sm-6  wow fadeInUp">
                <div className="column">
                  <h5 className="title_h5 text-capitalize">Cần giúp đỡ?</h5>
                  <ul>
                    <li><a href="javascript:void(0);">Dịch vụ khách hàng</a></li>
                    <li><a href="my_account.html">Tài khoản của tôi</a></li>
                    <li><a href="contactus.html">Liên hệ chúng tôi</a></li>
                    <li><a href="faq.html">Câu hỏi thường gặp</a></li>
                  </ul>
                </div>
              </div>
              <div className="col-lg-3 col-sm-6  wow fadeInUp">
                <div className="column">
                  <h5 className="title_h5 text-capitalize">Mua sắm cùng chúng tôi</h5>
                  <ul>
                    <li><a href="shipping_information.html">Thông tin giao hàng</a></li>
                    <li><a href="returns_exchanges.html">Trả hàng & Đổi hàng</a></li>
                    <li><a href="size_chart.html">Bảng kích thước</a></li>
                  </ul>
                </div>
              </div>
              <div className="col-lg-3 col-sm-6  wow fadeInUp">
                <div className="column">
                  <h5 className="title_h5 text-capitalize">Về chúng tôi</h5>
                  <ul>
                    <li><a href="we_are_earthyellow.html">Chúng tôi là Footvibe</a></li>
                    <li><a href="careers.html">Cơ hội nghề nghiệp</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div className="footer_middle border-top">
            <div className="row">
              <div className="col-sm-12 col-md-4  wow fadeInUp align-self-center">
                <ul className="footer_social_icons mt-0">
                  <li className="text-center">
                    <a href="javascript:void(0);">
                      <i className="flaticon-facebook vertical_middle"></i>
                    </a>
                  </li>
                  <li className="text-center">
                    <a href="javascript:void(0);">
                      <i className="flaticon-pinterest vertical_middle"></i>
                    </a>
                  </li>
                  <li className="text-center">
                    <a href="javascript:void(0);">
                      <i className="flaticon-instagram-logo vertical_middle"></i>
                    </a>
                  </li>
                </ul>
              </div>
              <div className="col-sm-12 col-md-4  wow fadeInUp align-self-center">
              </div>
              <div className="col-sm-12 col-md-4  wow fadeInUp align-self-center">
                <ul className="text-md-right payment_content mt-0">
                  <li>
                    <img src="../src/images/shoes_payments.png" alt="phương thức thanh toán" className="img-fluid" />
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </footer>
    )
  }
  
  export default Footer;
  