import { useState } from 'react'
import UploadThuongHieu from './UploadThuongHieu'
// import { MdModeEditOutline } from "react-icons/md";
import UpdateThuongHieu from './UpdateThuonghieu';
const QuanLyThuongHieu = () => {
    const [openUploadCategoty,setsetopenUploadCategoty]= useState(false)
    const [updateThuongHieu,setupdateThuongHieu]= useState(false)
  return (
    <>
     <section id="main-content">
          <section className="wrapper">
              <div className="row">
                  <div className="col-lg-12">
                      <section className="card">
                          <header className="card-header">
                             Quản lý thương hiệu
                          </header>
                          <table className="table table-striped table-advance table-hover">
                              <thead>
                              <tr>
                              <th><i className="fa fa-bullhorn"></i>Id</th>
                                  <th><i className="fa fa-bullhorn"></i>Name</th>
                                  <th><i className="fa fa-question-circle"></i> Status</th>
                                  <th><i className="fa fa-comments"></i>Created_at</th>
                                  <th><i className="fa fa-comments"></i>Created_up</th>
                                  <th><i className=" fa fa-edit"></i> Action</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                              <td>1</td>
                                  <td><a href="#">Thương hiệu 1</a></td>
                                  <td><span className="badge badge-info label-mini">Sử dụng</span></td>
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
    {
        openUploadCategoty && (
          <UploadThuongHieu onClose={()=>setsetopenUploadCategoty(false)}/>
        )
      }
       {
        updateThuongHieu && (
          <UpdateThuongHieu onClose={()=>setupdateThuongHieu(false)}/>
        )
      }
    </>
  )
}

export default QuanLyThuongHieu
