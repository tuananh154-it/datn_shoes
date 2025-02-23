import { useState } from 'react'
import UploadThuongHieu from './UploadThuongHieu'
import { MdModeEditOutline } from "react-icons/md";
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
                                  <th><i className="fa fa-bullhorn"></i>Tên Thương Hiệu</th>
                                  <th><i className=" fa fa-edit"></i> Hành động</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td><a href="#">1</a></td>
                                  <td><a href="#">Adidas</a></td>
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
