import { useState } from 'react'
// import UploadProduct from './UploadProduct'
import { FaUserEdit } from "react-icons/fa";
import OpenKhachHang from './OpenKhachHang';
// import UpdateProduct from './UpdateProduct';
const AllKhachHang = () => {
//   const [openUploadProduct,setOpenUploadProduct]= useState(false)
  const [openKhachHang,setopenKhachHang]= useState(false)

  return (
    <>
    {/* <div>
      <div className='bg-white py-2 px-4 flex justify-between items-center'>
        <h2 className='font-bold text-lg'>Danh sách khách hàng</h2>
      </div>
    </div>
    <div>
      <table className='w-full userTableKhachHang'>
        <thead>
          <tr>
            <th>STT</th>
            <th>Ảnh đại diện</th>
            <th>Tên khách hàng</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td><img src="" className="border-none rounded-full ml-[35%] w-20 h-20 bg-slate-200"/></td>
            <td>Khách hàng 1</td>
            <td><button className='bg-green-300 p-2 rounded-full hover:bg-green-500 hover:text-white' onClick={()=> setopenKhachHang(true)}><FaUserEdit/></button></td>
          </tr>
        </tbody>
      </table>
    </div> */}
    <section id="main-content">
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
      </section>
       {
        openKhachHang && (
          <OpenKhachHang onClose={()=>setopenKhachHang(false)}/>
        )
      }
    </>
  )
}

export default AllKhachHang