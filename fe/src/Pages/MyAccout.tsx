import React from 'react'

const MyAccout = () => {
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
                                <li className="breadcrumb-item active text-capitalize">Tài khoản của tôi</li>
                            </ol>
                        </nav>
                        <h1 className="title_h1 font-weight-normal text-capitalize">Tài khoản của tôi</h1>
                    </div>
                </section>
                <section className="my_account_section padding-top-text-60 padding-bottom-60">
                    <div className="container">
                        <h4 className="title_h4">Lịch sử đơn hàng</h4>
                        <div className="cart_table">
                            <div className="table">
                                <div className="thead">
                                    <div className="tr">
                                        <div className="th title_h5">Đơn hàng</div>
                                        <div className="th title_h5">Ngày đặt</div>
                                        <div className="th title_h5">Trạng thái thanh toán</div>
                                        <div className="th title_h5">Trạng thái hoàn tất</div>
                                        <div className="th title_h5">Tổng tiền</div>
                                        <div className="th title_h5">Hành động</div>
                                    </div>
                                </div>
                                <div className="tbody">
                                    <div className="tr">
                                        <div className="td" data-title="Order"><a href="order_details.html">#1026</a></div>
                                        <div className="td" data-title="Date">12 Tháng 1, 2018</div>
                                        <div className="td" data-title="Payment Status">Đã thanh toán</div>
                                        <div className="td" data-title="Fullfillment Status">Chưa hoàn tất</div>
                                        <div className="td" data-title="Total">$ 50.63</div>
                                        <div className="td" data-title="Action">
                                            <a href="order_details.html" className="text-uppercase border-btn view_btn">Xem</a>
                                        </div>
                                    </div>
                                    <div className="tr">
                                        <div className="td" data-title="Order"><a href="order_details.html">#1025</a></div>
                                        <div className="td" data-title="Date">09 Tháng 1, 2018</div>
                                        <div className="td" data-title="Payment Status">Chờ thanh toán</div>
                                        <div className="td" data-title="Fullfillment Status">Chưa hoàn tất</div>
                                        <div className="td" data-title="Total">$ 50.63</div>
                                        <div className="td" data-title="Action">
                                            <a href="order_details.html" className="text-uppercase border-btn view_btn">Xem</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4 className="title_h4">Chi tiết tài khoản</h4>
                        <div className="address">
                            <address>
                                Naitik Bhavsar<br />
                                Alian Softwares<br />
                                51, Podar Building,<br />
                                Đường Dr. Maheshwari, Masjid Bunder (e)<br />
                                400009 Mumbai MH<br />
                                Ấn Độ
                            </address>
                            <div className="add_address">
                                <a href="your_address.html" className="btn background-btn text-uppercase">XEM ĐỊA CHỈ (2)</a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </>
    )
}

export default MyAccout
