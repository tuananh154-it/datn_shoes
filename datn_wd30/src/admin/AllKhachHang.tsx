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
    <div>
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
    </div>
    <div>
       {
        openKhachHang && (
          <OpenKhachHang onClose={()=>setopenKhachHang(false)}/>
        )
      }
    </div>
    </>
  )
}

export default AllKhachHang