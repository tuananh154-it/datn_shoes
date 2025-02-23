
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
                                  <td className="hidden-phone"><img width={200} height={200} src='https://salt.tikicdn.com/cache/w1200/ts/product/c9/97/70/46f4d5b4ffc1fe8b29f272ac0261b773.jpg'/></td>
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
