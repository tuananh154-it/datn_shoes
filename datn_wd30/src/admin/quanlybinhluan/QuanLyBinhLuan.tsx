
const QuanLyBinhLuan = () => {
    return (
      <section id="main-content">
            <section className="wrapper">
                <div className="row">
                    <div className="col-lg-12">
                    <section className="card">
                          <header className="card-header">
                              Quản lý bình luận
                          </header>
                          <table className="table table-striped table-advance table-hover">
                              <thead>
                              <tr>
                                  <th><i className="fa fa-bullhorn"></i>Id</th>
                                  <th className="hidden-phone"><i className="fa fa-user"></i> customer_id</th>
                                  <th><i className="fa fa-users"></i>product_id</th>
                                  <th><i className=" fa fa-comments"></i>comment</th>
                                  <th><i className=" fa fa-bookmark"></i>rating</th>
                                  <th><i className=" fa fa-edit"></i> Tùy Chọn</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td><a href="#">11</a></td>
                                  <td className="hidden-phone">2</td>
                                  <td>3</td>
                                  <td>tot</td>
                                  <td>5 sao</td>
                                  <td>
                                      <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                      <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                      <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="#">11</a></td>
                                  <td className="hidden-phone">2</td>
                                  <td>3</td>
                                  <td>tot</td>
                                  <td>5 sao</td>
                                  <td>
                                      <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                      <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                      <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="#">11</a></td>
                                  <td className="hidden-phone">2</td>
                                  <td>3</td>
                                  <td>tot</td>
                                  <td>5 sao</td>
                                  <td>
                                      <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                      <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                      <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              </tbody>
                          </table>
                      </section>
                    </div>
                </div>
            </section>
        </section>
    )
  }
  
  export default QuanLyBinhLuan
  