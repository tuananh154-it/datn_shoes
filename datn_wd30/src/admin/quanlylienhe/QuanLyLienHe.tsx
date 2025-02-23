import React from 'react'

const QuanLyLienHe = () => {
  return (
    <section id="main-content">
      <div className="row">
                  <div className="col-lg-12">
                      <section className="card">
                          <header className="card-header">
                              Quản lý liên hệ
                          </header>
                          <table className="table table-striped table-advance table-hover">
                              <thead>
                              <tr>
                                  <th><i className="fa fa-bullhorn"></i>Id</th>
                                  <th className="hidden-phone"><i className="fa fa-question-circle"></i> Name</th>
                                  <th><i className="fa fa-bookmark"></i> Số điện thoại</th>
                                  <th><i className=" fa fa-edit"></i> Email</th>
                                  <th><i className=" fa fa-edit"></i> Tùy Chọn</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td><a href="#">1</a></td>
                                  <td className="hidden-phone">Kiet</td>
                                  <td>123456123456</td>
                                  <td>kiet123@gmail.com</td>
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
  )
}

export default QuanLyLienHe
