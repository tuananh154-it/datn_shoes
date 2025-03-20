import React, { useEffect, useState } from "react";
import { Article, getArticles } from "../services/articles";
import toast from "react-hot-toast";
import { getAllVoucher, Voucher } from "../services/vouchers";

const Blog = () => {
  // Bài viết
  const [articles, setArticles] = useState<Article[]>([]);
  const [loading, setLoading] = useState<boolean>(true);

  useEffect(() => {
    getArticles()
      .then(({ data }) => {
        setArticles(data);
      })
      .catch(() => toast.error("Lỗi không hiển thị bài viết"))
      .finally(() => setLoading(false));
  }, []);

  // Voucher
  const [vouchers, setVouchers] = useState<Voucher[]>([]);
  useEffect(() => {
    getAllVoucher()
      .then(({ data }) => {
        setVouchers(data);
      })
      .catch(() => toast.error("Lỗi không lấy được danh sách voucher"));
  }, []);

  return (
    <>
      <div className="menu_overlay"></div>
      <div className="main_section">
        {/* Breadcrumb */}
        <section className="breadcrumb_section nav">
          <div className="container">
            <nav aria-label="breadcrumb">
              <ol className="breadcrumb">
                <li className="breadcrumb-item text-capitalize">
                  <a href="/">Home</a> <i className="flaticon-arrows-4"></i>
                </li>
                <li className="breadcrumb-item active text-capitalize">Blog</li>
              </ol>
            </nav>
            <h1 className="title_h1 font-weight-normal text-capitalize">Blog</h1>
          </div>
        </section>

        {/* Blog Section */}
        <section className="blog_section padding-top-60 padding-bottom-60">
          <div className="blog_list2_section blog_list_section wow fadeIn">
            <div className="container">
              <div className="row">
                {/* Danh sách bài viết */}
                <div className="col-md-8 col-xl-9 wow fadeInLeft" data-wow-duration="1300ms">
                  {articles.map((article) => (
                    <div key={article.id} className="blog_content">
                      <a href={`/blog/${article.id}`}>
                        <img src={article.image} alt={article.name} className="img-fluid" style={{ width: 800 }} />
                      </a>
                      <a href={`/blog/${article.id}`}>
                        <h5 className="article__title title_h5">{article.name}</h5>
                      </a>
                      <p>{article.title}</p>
                      <span className="article__date">
                        Ngày đăng | {article.created_at} <span className="diamond_shape"></span>
                      </span>
                    </div>
                  ))}
                </div>

                {/* Sidebar */}
                <div className="col-xl-3 col-md-4 wow fadeInRight" data-wow-duration="1300ms">
                  {/* Voucher List */}
                  <div className="featured_posts">
                    <h4  style={{fontSize:30,color:"red",width:400}}>Danh sách Voucher</h4>
                    {vouchers.length === 0 ? (
                      <p>Đang hiển thị danh sách vouchers</p>
                    ) : (
                      vouchers.map((voucher) => (
                        <div key={voucher.id} className="featured_posts_content relative flex w-[800px] bg-white shadow-lg rounded-lg border" style={{width:400,height:140}} >
                          <div className="featured_posts_img" >
                          <img src="src/images/thoitrang.png" alt="bloglist2" style={{height:140,width:100}}/>
                          </div>
                          <div className="featured_posts_text" style={{marginTop:5,lineHeight:2}}>
                            <h6 className="title_h5" >Giảm {voucher.discount_percent}% Giảm tối đa {voucher.max_discount_amount}k Đơn tối thiểu {voucher.min_purchase_amount}k</h6>
                            <p>Ngày hết hạn: {voucher.expiration_date}</p>
                            <a href={`/vouchers/${voucher.id}`}>
                            <p style={{color: "#60A5FA", textDecoration: "underline"}}>Nhận mã:{voucher.name}</p>
                            </a>
                          </div>
                        </div>
                      ))
                    )}
                  </div>
                </div>
                {/* End Sidebar */}

              </div>
            </div>
          </div>
        </section>
      </div>
    </>
  );
};

export default Blog;
