import { useState } from 'react'
import UploadThuongHieu from './UploadThuongHieu'
import { MdModeEditOutline } from "react-icons/md";
import UpdateThuongHieu from './UpdateThuonghieu';
const QuanLyThuongHieu = () => {
    const [openUploadCategoty,setsetopenUploadCategoty]= useState(false)
    const [updateThuongHieu,setupdateThuongHieu]= useState(false)
  return (
    <>
     <div>
      <div className='bg-white py-2 px-4 flex justify-between items-center'>
        <h2 className='font-bold text-lg'>Quản lý thương hiệu</h2>
        <button className='border-2 border-red-600 px-3 py-1 text-red-400 rounded-full hover:text-white hover:bg-red-300' onClick={()=>setsetopenUploadCategoty(true)}>Thêm thương hiệu</button>
      </div>
    </div>
    <div>
      <table className='w-full userTable'>
        <thead>
          <tr>
            <th>Tên thương hiệu</th>
            <th>Trạng thái</th>
            <th>Ghi chú</th>
            <th>Hành động</th>
          </tr>
        </thead>
          <tbody>
                          <tr>
                            <td>Thương hiệu 1</td>
                            <td>Sử dụng</td>
                            <td>Thương hiệu</td>
                            <td><button className='bg-green-300 p-2 rounded-full hover:bg-green-500 hover:text-white' onClick={()=> setupdateThuongHieu(true)}><MdModeEditOutline/></button></td>
                          </tr>
                
                        </tbody>
      </table>
    </div>
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
