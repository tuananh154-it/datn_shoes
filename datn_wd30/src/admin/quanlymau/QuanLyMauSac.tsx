
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
                            <th><i className="fa fa-bullhorn"></i>Tên màu</th>
                            <th><i className=" fa fa-bookmark"></i> Ghi chú</th>
                            <th><i className=" fa fa-edit"></i> Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><a href="#">1</a></td>
                            <td><a href="#">Màu trắng</a></td>
                            <td><span className="badge badge-info label-mini">Sử dụng</span></td>
                            <td>
                                <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="#">1</a></td>
                            <td><a href="#">Màu trắng</a></td>
                            <td><span className="badge badge-info label-mini">Sử dụng</span></td>
                            <td>
                                <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="#">1</a></td>
                            <td><a href="#">Màu trắng</a></td>
                            <td><span className="badge badge-info label-mini">Sử dụng</span></td>
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
