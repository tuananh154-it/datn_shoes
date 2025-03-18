import React, { useEffect, useState } from "react";
import { Article, getArticles } from "../services/articles";
import toast from "react-hot-toast";

const Blog = () => {
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
                  {  (
                    articles.map((article) => (
                      <div key={article.id} className="blog_content">
                        <a href={`/blog/${article.id}`}>
                          <img src={article.image} alt={article.name} className="img-fluid" />
                        </a>
                        <a href={`/blog/${article.id}`}>
                          <h5 className="article__title title_h5">{article.name}</h5>
                        </a>
                        <p>{article.title}</p>
                        <span className="article__date">
                          Ngày đăng | {article.created_at} <span className="diamond_shape"></span>
                        </span>
                        {/* <p>{article.content.substring(0, 100)}...</p> */}
                      </div>
                    ))
                  )}
                </div>

                {/* Sidebar */}
                <div className="col-xl-3 col-md-4 wow fadeInRight" data-wow-duration="1300ms">
                  {/* Blog Post Navigation */}
                  <div className="featured_posts">
                    <h4 className="title_h4 text-capitalize">Featured Posts</h4>
                    <div className="featured_posts_content">
                      <div className="featured_posts_img">
                        <img src="src/images/post1.png" alt="bloglist" className="img-fluid vertical_middle" />
                      </div>
                      <div className="featured_posts_text">
                        <a href="javascript:void(0);">
                          <h5 className="title_h5">Sed ut perspicia tis unde omnis</h5>
                        </a>
                        <p>March 21, 2018</p>
                      </div>
                    </div>
                    <div className="featured_posts_content">
                      <div className="featured_posts_img">
                        <img src="src/images/post2.png" alt="bloglist" className="img-fluid vertical_middle" />
                      </div>
                      <div className="featured_posts_text">
                        <a href="javascript:void(0);">
                          <h5 className="title_h5">Non numquam eius modi</h5>
                        </a>
                        <p>March 21, 2018</p>
                      </div>
                    </div>
                    <div className="featured_posts_content">
                      <div className="featured_posts_img">
                        <img src="src/images/post3.png" alt="bloglist" className="img-fluid vertical_middle" />
                      </div>
                      <div className="featured_posts_text">
                        <a href="javascript:void(0);">
                          <h5 className="title_h5">Ut enim ad  minima send</h5>
                        </a>
                        <p>March 21, 2018</p>
                      </div>
                    </div>
                    <div className="featured_posts_content">
                      <div className="featured_posts_img">
                        <img src="src/images/post4.png" alt="bloglist" className="img-fluid vertical_middle" />
                      </div>
                      <div className="featured_posts_text">
                        <a href="javascript:void(0);">
                          <h5 className="title_h5">Quis nostrum ullam corporis</h5>
                        </a>
                        <p>March 21, 2018</p>
                      </div>
                    </div>
                    {/* Post Instagram */}
                    <div className="blog_instagram">
                      <h4 className="title_h4 text-capitalize">Instagram</h4>
                      <ul>
                        <li>
                          <a href="javascript:void(0);">
                            <img className="img-fluid" src="src/images/blog_insta1.png" alt="blog_insta" />
                          </a>
                        </li>
                        <li>
                          <a href="javascript:void(0);">
                            <img className="img-fluid" src="src/images/blog_insta2.png" alt="blog_insta" />
                          </a>
                        </li>
                        <li>
                          <a href="javascript:void(0);">
                            <img className="img-fluid" src="src/images/blog_insta3.png" alt="blog_insta" />
                          </a>
                        </li>
                        <li>
                          <a href="javascript:void(0);">
                            <img className="img-fluid" src="src/images/blog_insta4.png" alt="blog_insta" />
                          </a>
                        </li>
                        <li>
                          <a href="javascript:void(0);">
                            <img className="img-fluid" src="src/images/blog_insta5.png" alt="blog_insta" />
                          </a>
                        </li>
                        <li>
                          <a href="javascript:void(0);">
                            <img className="img-fluid" src="src/images/blog_insta6.png" alt="blog_insta" />
                          </a>
                        </li>
                        <li>
                          <a href="javascript:void(0);">
                            <img className="img-fluid" src="src/images/blog_insta7.png" alt="blog_insta" />
                          </a>
                        </li>
                        <li>
                          <a href="javascript:void(0);">
                            <img className="img-fluid" src="src/images/blog_insta8.png" alt="blog_insta" />
                          </a>
                        </li>
                        <li>
                          <a href="javascript:void(0);">
                            <img className="img-fluid" src="src/images/blog_insta9.png" alt="blog_insta" />
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                {/* End Sidebar */}
                <div className="col-md-8 col-xl-9 align-self-center">
                  <ul className="pagination text-center justify-content-center">
                    <li className="page-item">
                      <a className="page-link" href="javascript:void(0);">
                        <i className="flaticon-arrows-1"></i>
                      </a>
                    </li>
                    <li className="page-item active">
                      <a className="page-link" href="javascript:void(0);">1</a>
                    </li>
                    <li className="page-item">
                      <a className="page-link" href="javascript:void(0);">2</a>
                    </li>
                    <li className="page-item">
                      <a className="page-link" href="javascript:void(0);">3</a>
                    </li>
                    <li className="page-item">
                      <a className="page-link" href="javascript:void(0);">
                        <i className="flaticon-arrows"></i>
                      </a>
                    </li>
                  </ul>
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
