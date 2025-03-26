import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { Article, getOneArticles } from '../services/articles';
import toast from 'react-hot-toast';
import DOMPurify from 'dompurify';
const BlogDetail = () => {
    const { id } = useParams();
    const [article, setArticle] = useState<Article | null>(null);
    const [loading, setLoading] = useState<boolean>(true);

    useEffect(() => {
        if (!id) return;
        getOneArticles(id)
            .then(({ data }) => {
                setArticle(data);
                console.log(data);
            })
            .catch(() => toast.error("Lỗi hiển thị"))
            .finally(() => setLoading(false));
    }, [id]);
  
    return (
        <>
            <div className="menu_overlay"></div>
            <div className="main_section">
                <section className="breadcrumb_section nav">
                    <div className="container">
                        <nav aria-label="breadcrumb">
                            <ol className="breadcrumb">
                                <li className="breadcrumb-item text-capitalize"><a href="earthyellow.html">Home</a> <i className="flaticon-arrows-4"></i></li>
                                <li className="breadcrumb-item text-capitalize"><a href="grids_blog_list.html">Blog</a> <i className="flaticon-arrows-4"></i></li>
                                <li className="breadcrumb-item active text-capitalize">{article?.name}</li>
                            </ol>
                        </nav>
                        <h1 className="title_h1 font-weight-normal text-capitalize">{article?.name}</h1>
                    </div>
                </section>

                <section className="blog_section padding-top-60 padding-bottom-60">
                    <div className="blog_list2_section blog_list_section wow fadeIn">
                        <div className="container">
                            <div className="row">
                                <div className="col-md-8 col-xl-9 wow fadeInLeft" data-wow-duration="1300ms">
                                    {loading ? (
                                        <p>Đang tải bài viết...</p>
                                    ) : (
                                        <div className="blog_content">
                                        <img src={article?.image} alt={article?.name} className="img-fluid" />
                                        <span className="article__date">
                                            Ngày đăng | {article?.created_at} <span className="diamond_shape"></span>
                                        </span>
                                        <div dangerouslySetInnerHTML={{ __html: DOMPurify.sanitize(article?.content || "") }} />
                                        </div>
                                        
                                    )}
                                </div>

                                <div className="col-xl-3 col-md-4 wow fadeInRight" data-wow-duration="1300ms">
                                    <div className="featured_posts">
                                        <h4 className="title_h4 text-capitalize">Featured Posts</h4>
                                        <div className="featured_posts_content">
                                            <div className="featured_posts_img">
                                                <img src="../src/images/post1.png" alt="bloglist" className="img-fluid vertical_middle" />
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
                                                <img src="../src/images/post2.png" alt="bloglist" className="img-fluid vertical_middle" />
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
                                                <img src="../src/images/post3.png" alt="bloglist" className="img-fluid vertical_middle" />
                                            </div>
                                            <div className="featured_posts_text">
                                                <a href="javascript:void(0);">
                                                    <h5 className="title_h5">Ut enim ad minima send</h5>
                                                </a>
                                                <p>March 21, 2018</p>
                                            </div>
                                        </div>

                                        <div className="featured_posts_content">
                                            <div className="featured_posts_img">
                                                <img src="../src/images/post4.png" alt="bloglist" className="img-fluid vertical_middle" />
                                            </div>
                                            <div className="featured_posts_text">
                                                <a href="javascript:void(0);">
                                                    <h5 className="title_h5">Quis nostrum ullam corporis</h5>
                                                </a>
                                                <p>March 21, 2018</p>
                                            </div>
                                        </div>

                                        <div className="blog_instagram">
                                            <h4 className="title_h4 text-capitalize">Instagram</h4>
                                            <ul>
                                                <li><a href="javascript:void(0);"><img className="img-fluid" src="../src/images/blog_insta1.png" alt="blog_insta" /></a></li>
                                                <li><a href="javascript:void(0);"><img className="img-fluid" src="../src/images/blog_insta2.png" alt="blog_insta" /></a></li>
                                                <li><a href="javascript:void(0);"><img className="img-fluid" src="../src/images/blog_insta3.png" alt="blog_insta" /></a></li>
                                                <li><a href="javascript:void(0);"><img className="img-fluid" src="../src/images/blog_insta4.png" alt="blog_insta" /></a></li>
                                                <li><a href="javascript:void(0);"><img className="img-fluid" src="../src/images/blog_insta5.png" alt="blog_insta" /></a></li>
                                                <li><a href="javascript:void(0);"><img className="img-fluid" src="../src/images/blog_insta6.png" alt="blog_insta" /></a></li>
                                            </ul>
                                        </div>
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

export default BlogDetail;
