import { useState } from 'react'
// import UploadProduct from './UploadProduct'
// import { FaUserEdit } from "react-icons/fa";
import OpenKhachHang from './OpenKhachHang';
// import UpdateProduct from './UpdateProduct';
const AllKhachHang = () => {
//   const [openUploadProduct,setOpenUploadProduct]= useState(false)
  const [openKhachHang,setopenKhachHang]= useState(false)

  return (
    <>
     <section id="main-content">
          <section className="wrapper">
              <div className="row">
                  <div className="col-lg-12">
                      <section className="card">
                          <header className="card-header">
                             Danh sách khách hàng
                          </header>
                          <table className="table table-striped table-advance table-hover">
                              <thead>
                              <tr>
                              <th><i className="fa fa-bullhorn"></i>Id</th>
                                  <th><i className="fa fa-bullhorn"></i> User name</th>
                                  <th><i className="fa fa-bullhorn"></i> Phone_number</th>
                                  <th><i className="fa fa-bookmark"></i> Email</th>
                                  <th><i className="fa fa-bookmark"></i> Addess</th>
                                  <th><i className=" fa fa-edit"></i>Date_of_birth</th>
                                  <th><i className=" fa fa-edit"></i> Gender</th>
                                  <th><i className=" fa fa-edit"></i> Ceated_at</th>
                                  <th><i className=" fa fa-edit"></i> Ceated_up</th>
                                  <th><i className=" fa fa-edit"></i> Action</th>
                                  <th></th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                              <td>1</td>
                                  <td><a href="#">Nguyễn Văn a</a></td>  
                                  <td>0123456789</td>
                                  <td>a@gmail.com</td>
                                  <td>Hà Nội</td>
                                  <td>01/01/2000</td>
                                  <td>Nam</td>
                                  <td>Created_at</td>
                                  <td>Created_up</td>
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
    {/* <section id="main-content">
      <div className="row">
                  <div className="col-lg-12">
                      <section className="card">
                          <header className="card-header">
                              Quản lý khách hàng
                          </header>
                          <table className="table table-striped table-advance table-hover">
                              <thead>
                              <tr>
                                  <th><i className="fa fa-bullhorn"></i>Id</th>
                                  <th className="hidden-phone"><i className="fa fa-question-circle"></i> Name</th>
                                  <th><i className="fa fa-bookmark"></i> PassWord</th>
                                  <th><i className=" fa fa-edit"></i> Email</th>
                                  <th><i className=" fa fa-edit"></i> Địa chỉ</th>
                                  <th><i className=" fa fa-edit"></i> Tùy Chọn</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td><a href="#">11</a></td>
                                  <td className="hidden-phone">Kiet</td>
                                  <td>123456123456</td>
                                  <td>kiet123@gmail.com</td>
                                  <td>Hà nội</td>
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
      </section> */}
       {
        openKhachHang && (
          <OpenKhachHang onClose={()=>setopenKhachHang(false)}/>
        )
      }
    </>
  )
}

export default AllKhachHang