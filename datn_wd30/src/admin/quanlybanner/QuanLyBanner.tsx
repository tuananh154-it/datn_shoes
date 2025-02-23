
const QuanLyBanner = () => {
    return (
      <>
      <section id="main-content">
            <section className="wrapper">
                <div className="row">
                    <div className="col-lg-12">
                        <section className="card">
                            <header className="card-header">
                               Quản lý banner
                            </header>
                            <table className="table table-striped table-advance table-hover">
                                <thead>
                                <tr>
                                    <th><i className="fa fa-bullhorn"></i>ID</th>
                                    <th className=""><i className="fa fa-question-circle"></i>Ảnh Banner</th>
                                    <th><i className=" fa fa-edit"></i> Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><a href="#">1</a></td>
                                    <td className="hidden-phone flex p-2"><img width={200} className=" " height={200} src='https://tse3.mm.bing.net/th?id=OIP.zPwNViOhSeP4Se_bZV6gAAHaDt&pid=Api&P=0&h=180'/>
                                    <img width={200} height={200} className="ml-3" src='https://tse3.mm.bing.net/th?id=OIP.zPwNViOhSeP4Se_bZV6gAAHaDt&pid=Api&P=0&h=180'/>
                                    <img width={200} height={200} className="ml-3" src='https://tse3.mm.bing.net/th?id=OIP.zPwNViOhSeP4Se_bZV6gAAHaDt&pid=Api&P=0&h=180'/></td>
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
      </>
    )
  }
  
  export default QuanLyBanner
  