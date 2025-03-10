import React from 'react'

const Blog = () => {
  return (
   <>
   <div className="menu_overlay"></div>
{/* END Header */}
<div className="main_section">
  {/* START Breadcrumb */}
  <section className="breadcrumb_section nav">
    <div className="container">
      <nav aria-label="breadcrumb">
        <ol className="breadcrumb">
          <li className="breadcrumb-item text-capitalize">
            <a href="earthyellow.html">Home</a> <i className="flaticon-arrows-4"></i>
          </li>
          <li className="breadcrumb-item active text-capitalize">Blog</li>
        </ol>
      </nav>
      <h1 className="title_h1 font-weight-normal text-capitalize">Blog</h1>
    </div>
  </section>
  {/* END Breadcrumb */}
  {/* START Blog Section */}
  <section className="blog_section padding-top-60 padding-bottom-60">
    <div className="blog_list2_section blog_list_section wow fadeIn">
      <div className="container">
        <div className="row">
          <div className="col-md-8 col-xl-9 wow fadeInLeft" data-wow-duration="1300ms">
            <div className="blog_content">
              <a href="blog_list_detail.html">
                <img src="src/images/bloglist2_1.png" alt="bloglist" className="img-fluid" />
              </a>
              <span className="article__date">
                March 21, 2018 | Posted By Admin
                <span className="diamond_shape"></span>
              </span>
              <a href="blog_list_detail.html">
                <h5 className="article__title title_h5">
                  Sed ut perspiciatis unde omnis iste natus
                </h5>
              </a>
              <p>
                There are many variations of passages of Lorem Ipsum available but the majority have suffered alteration in some form by injected humour or randomised words which do...
              </p>
            </div>
            <div className="blog_content">
              <a href="blog_list_detail.html">
                <img src="src/images/bloglist2_2.png" alt="bloglist" className="img-fluid" />
              </a>
              <span className="article__date">
                March 21, 2018 | Posted By Admin
                <span className="diamond_shape"></span>
              </span>
              <a href="blog_list_detail.html">
                <h5 className="article__title title_h5">
                  Voluptatem accusantium dolor emque totam
                </h5>
              </a>
              <p>
                There are many variations of passages of Lorem Ipsum available but the majority have suffered alteration in some form by injected humour or randomised words which do...
              </p>
            </div>
            <div className="blog_content">
              <a href="blog_list_detail.html">
                <img src="src/images/bloglist2_3.png" alt="bloglist" className="img-fluid" />
              </a>
              <span className="article__date">
                March 21, 2018 | Posted By Admin
                <span className="diamond_shape"></span>
              </span>
              <a href="blog_list_detail.html">
                <h5 className="article__title title_h5">
                  Eaque ipsa quae ab illo inventore veritatis et
                </h5>
              </a>
              <p>
                There are many variations of passages of Lorem Ipsum available but the majority have suffered alteration in some form by injected humour or randomised words which do...
              </p>
            </div>
            <div className="blog_content">
              <a href="blog_list_detail.html">
                <img src="src/images/bloglist2_4.png" alt="bloglist" className="img-fluid" />
              </a>
              <span className="article__date">
                March 21, 2018 | Posted By Admin
                <span className="diamond_shape"></span>
              </span>
              <a href="blog_list_detail.html">
                <h5 className="article__title title_h5">
                  Magni dolores eos qui ratione voluptatem sequi
                </h5>
              </a>
              <p>
                There are many variations of passages of Lorem Ipsum available but the majority have suffered alteration in some form by injected humour or randomised words which do...
              </p>
            </div>
            <div className="blog_content">
              <a href="blog_list_detail.html">
                <img src="src/images/bloglist2_5.png" alt="bloglist" className="img-fluid" />
              </a>
              <span className="article__date">
                March 21, 2018 | Posted By Admin
                <span className="diamond_shape"></span>
              </span>
              <a href="blog_list_detail.html">
                <h5 className="article__title title_h5">
                  Neque porro quisquam est qui dolorem ipsum
                </h5>
              </a>
              <p>
                There are many variations of passages of Lorem Ipsum available but the majority have suffered alteration in some form by injected humour or randomised words which do...
              </p>
            </div>
            <div className="blog_content">
              <a href="blog_list_detail.html">
                <img src="src/images/bloglist2_6.png" alt="bloglist" className="img-fluid" />
              </a>
              <span className="article__date">
                March 21, 2018 | Posted By Admin
                <span className="diamond_shape"></span>
              </span>
              <a href="blog_list_detail.html">
                <h5 className="article__title title_h5">
                  Quia dolor sit amet consectetur adipisci velit
                </h5>
              </a>
              <p>
                There are many variations of passages of Lorem Ipsum available but the majority have suffered alteration in some form by injected humour or randomised words which do...
              </p>
            </div>
          </div>
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
          {/* Blog pagination */}
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
  {/* END Blog Section */}
</div>

   </>
  )
}

export default Blog
