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
            <h1 className="title_h1 font-weight-normal text-capitalize">Danh sách bài viết</h1>
          </div>
        </section>

        <section className="blog_section padding-top-60 padding-bottom-60">
          <div className="blog_list_section wow fadeIn">
            <div className="container">
              <div className="row">
                {loading ? (
                  <p>Đang tải bài viết...</p>
                ) : currentArticles.length === 0 ? (
                  <p>Không có bài viết nào để hiển thị.</p>
                ) : (
                  currentArticles.map((article) => (
                    <div
                      key={article.id}
                      className="col-md-6 wow fadeInLeft"
                      data-wow-duration="1300ms"
                    >
                      <div className="blog_content">
                        <a href={`/blog/${article.id}`}>
                          <img
                            src={article.image}
                            alt={article.name}
                            className="img-fluid"
                            style={{ width: "800px", height: "400px" }}
                          />
                        </a>
                        <span className="article__date">
                          {new Date(article.created_at).toLocaleDateString("vi-VN")} | Đăng bởi Admin
                          <span className="diamond_shape"></span>
                        </span>
                        <a href={`/blog/${article.id}`}>
                          <h5 className="article__title title_h5">
                            {article.name.length > 60
                              ? article.name.slice(0, 60) + "..."
                              : article.name}
                          </h5>
                        </a>
                        <p>
                          {article.title.length > 150
                            ? article.title.slice(0, 150) + "..."
                            : article.title}
                        </p>
                      </div>
                    </div>
                  ))
                )}
                {!loading && (
                  <div className="col-md-12 align-self-center">
                    <Pagination
                      currentPage={currentPage}
                      totalPages={totalPages}
                      onPageChange={handlePageChange}
                    />
                  </div>
                )}
              </div>
            </div>
          </div>
        </section>
      </div>
    </>
  );
};

export default Blog;