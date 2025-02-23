import React from 'react'

const QuanLyMauSac = () => {
  return (
    <section id="main-content">
    <section className="wrapper">
        <div className="row">
            <div className="col-lg-12">
                <section className="card">
                    <header className="card-header">
                       Quản lý màu sắc
                    </header>
                    <table className="table table-striped table-advance table-hover">
                        <thead>
                        <tr>
                            <th><i className="fa fa-bullhorn"></i>Id</th>
                            <th><i className="fa fa-bullhorn"></i>Tên</th>
                            <th><i className=" fa fa-edit"></i> Ghi chú</th>
                            <th><i className=" fa fa-edit"></i> Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><a href="#">1</a></td>
                            <td><a href="#">38</a></td>
                            <td><span className="badge badge-info label-mini">Due</span></td>
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

export default QuanLyMauSac
