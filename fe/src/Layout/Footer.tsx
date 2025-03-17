
const Footer = () => {
  return (
    <footer className="shoes_footer wow fadeIn animated">
    <div className="container">
        <div className="footer_top">
            <div className="row">
                <div className="col-lg-3 col-sm-6  wow fadeInUp ">
                    <div className="column">
                        <a href="shoes.html"><img src="src/images/shoes_footer_logo.png" alt="logo" className="img-fluid footer_logo"/></a>
                        <p>There are many variations of passages of Lorem Ipsum has been the industry stand ard dummy text ever since...</p>
                    </div>
                </div>
                <div className="col-lg-3 col-sm-6  wow fadeInUp">
                    <div className="column">
                        <h5 className="title_h5 text-capitalize">Need Help?</h5>
                        <ul>
                            <li><a href="javascript:void(0);">Customer Service</a></li>
                            <li><a href="my_account.html">My Account</a></li>
                            <li><a href="contactus.html">Contact Us</a></li>
                            <li><a href="faq.html">FAQs</a></li>
                        </ul>
                    </div>
                </div>
                <div className="col-lg-3 col-sm-6  wow fadeInUp">
                    <div className="column">
                        <h5 className="title_h5 text-capitalize">Shopping With Us</h5>
                        <ul>
                            <li><a href="shipping_information.html">Shipping Information</a></li>
                            <li><a href="returns_exchanges.html">Returns & Exchanges</a></li>
                            <li><a href="size_chart.html">Size Charts</a></li>
                        </ul>
                    </div>
                </div>
                <div className="col-lg-3 col-sm-6  wow fadeInUp">
                    <div className="column">
                        <h5 className="title_h5 text-capitalize">About Us</h5>
                        <ul>
                            <li><a href="we_are_earthyellow.html">We Are Earthyellow</a></li>
                            <li><a href="careers.html">Careers</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div className="footer_middle border-top">
            <div className="row">
                <div className="col-sm-12 col-md-4  wow fadeInUp align-self-center">
                    <ul className="footer_social_icons mt-0">
                        <li className="text-center"><a href="javascript:void(0);"><i className="flaticon-facebook vertical_middle"></i></a></li>
                        <li className="text-center"><a href="javascript:void(0);"><i className="flaticon-pinterest vertical_middle"></i></a></li>
                        <li className="text-center"><a href="javascript:void(0);"><i className="flaticon-instagram-logo vertical_middle"></i></a></li>
                    </ul>                            
                </div>
                <div className="col-sm-12 col-md-4  wow fadeInUp align-self-center">
                    <p className="copy_right text-center">2018 &copy; Earthyellow All rights reserved.</p>
                </div>
                <div className="col-sm-12 col-md-4  wow fadeInUp align-self-center">
                    <ul className="text-md-right payment_content mt-0">
                        <li><img src="src/images/shoes_payments.png" alt="payments" className="img-fluid" /></li>
                    </ul>                            
                </div>
            </div>
        </div>
    </div>            
</footer>
  )
}

export default Footer
