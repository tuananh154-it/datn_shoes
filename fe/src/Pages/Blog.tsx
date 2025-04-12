import React, { useEffect, useState } from "react";
import { Article, getArticles } from "../services/articles";
import toast from "react-hot-toast";
import { getAllVoucher, Voucher } from "../services/vouchers";
import Pagination from "./Pagination";

const Blog = () => {
  const [articles, setArticles] = useState<Article[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [currentPage, setCurrentPage] = useState(1);
  const articlesPerPage = 4;

  const indexOfLastArticle = currentPage * articlesPerPage;
  const indexOfFirstArticle = indexOfLastArticle - articlesPerPage;
  const currentArticles = articles.slice(indexOfFirstArticle, indexOfLastArticle);
  const totalPages = Math.ceil(articles.length / articlesPerPage);

  const handlePageChange = (page: number) => {
    if (page >= 1 && page <= totalPages) {
      setCurrentPage(page);
    }
  };

  const handleReceiveVoucher = (voucherCode: string) => {
    localStorage.setItem("voucher_code", voucherCode);
    alert(`Mã ${voucherCode} đã được lưu!`);
  };

  useEffect(() => {
    getArticles()
      .then(({ data }) => {
        console.log("Articles:", data);
        setArticles(data);
      })
      .catch((error) => {
        console.error("Error fetching articles:", error);
        toast.error("Lỗi không hiển thị bài viết");
      })
      .finally(() => setLoading(false));
  }, []);

  const [vouchers, setVouchers] = useState<Voucher[]>([]);
  useEffect(() => {
    getAllVoucher()
      .then(({ data }) => {
        setVouchers(data);
        console.log(data);
      })
      .catch(() => toast.error("Lỗi không lấy được danh sách voucher"));
  }, []);

  if (loading) {
    return <div>Đang tải...</div>;
  }

  return (
    <>
      <div className="menu_overlay"></div>
      <div className="main_section">
        <section className="breadcrumb_section nav">
          <div className="container">
            <nav aria-label="breadcrumb">
              <ol className="breadcrumb">
                <li className="breadcrumb-item text-capitalize">
                  <a href="/">Trang chủ</a> <i className="flaticon-arrows-4"></i>
                </li>
                <li className="breadcrumb-item active text-capitalize">Bài viết</li>
              </ol>
            </nav>
            <h1 className="title_h1 font-weight-normal text-capitalize">Bài viết</h1>
          </div>
        </section>

        <section className="blog_section padding-top-60 padding-bottom-60">
          <div className="blog_list2_section blog_list_section wow fadeIn">
            <div className="container">
              <div className="row">
                <div className="blog-1 col-xl-9 wow fadeInLeft" data-wow-duration="1300ms">
                  {currentArticles.length === 0 ? (
                    <p>Không có bài viết nào để hiển thị.</p>
                  ) : (
                    currentArticles.map((article) => (
                      <div key={article.id} className="blog_content">
                        <a href={`/blog/${article.id}`}>
                          <img
                            src={article.image}
                            alt={article.name}
                            className="img-fluid"
                            style={{ width: "800px", height: "200px" }}
                          />
                        </a>
                        <a href={`/blog/${article.id}`}>
                          <h5 className="article__title title_h5">{article.name}</h5>
                        </a>
                        <p>{article.title}</p>
                        <span className="article__date">
                          Ngày đăng |{" "}
                          {new Date(article.created_at).toLocaleDateString("vi-VN")}{" "}
                          <span className="diamond_shape"></span>
                        </span>
                      </div>
                    ))
                  )}

                 
                    <Pagination
                      currentPage={currentPage}
                      totalPages={totalPages}
                      onPageChange={handlePageChange}
                    />
                 
                </div>

                <div className="col-xl-3 blog-2 wow fadeInRight" data-wow-duration="1300ms">
                  <div className="featured_posts">
                    <h4 style={{ fontSize: 30, color: "red", width: 400 }}>Danh sách Voucher</h4>
                    {vouchers.length === 0 ? (
                      <p>Hiện tại chưa có vouchers nào</p>
                    ) : (
                      vouchers.map((voucher) => (
                        <div
                          key={voucher.id}
                          className="featured_posts_content relative flex w-[800px] bg-white shadow-lg rounded-lg border"
                          style={{ width: 400, height: 140 }}
                        >
                          <div className="featured_posts_img">
                            <img
                              src="src/images/thoitrang.png"
                              alt="bloglist2"
                              style={{ height: 140, width: 100 }}
                            />
                          </div>
                          <div
                            className="featured_posts_text"
                            style={{ marginTop: 5, lineHeight: 2 }}
                          >
                            <h6 className="title_h5">
                              Giảm {voucher.discount_percent}% Giảm tối đa {voucher.max_discount_amount}k
                              Đơn tối thiểu {voucher.min_purchase_amount}k
                            </h6>
                            <p>Ngày hết hạn: {voucher.expiration_date}(Còn:{voucher.quantity} mã)</p>
                            <p
                              style={{ color: "#60A5FA", textDecoration: "underline", cursor: "pointer" }}
                              onClick={() => handleReceiveVoucher(voucher.name)}
                            >
                              Mã: {voucher.name}
                            </p>
                          </div>
                        </div>
                      ))
                    )}
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

export default Blog;