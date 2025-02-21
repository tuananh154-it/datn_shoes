import { useState } from 'react'
import UploadCategory from './UploadCategory'
// import { MdModeEditOutline } from "react-icons/md";
import { RiEdit2Fill } from "react-icons/ri";
import UpdateDanhMuc from './UpdateDanhMuc';
const QuanLyDanhMuc = () => {
    const [openUploadCategoty,setsetopenUploadCategoty]= useState(false)
    const [updateDanhMuc,setupdateDanhMuc]= useState(false)
    
  return (
    <>
     <div>
      <div className='bg-white py-2 px-4 flex justify-between items-center'>
        <h2 className='font-bold text-lg'>Quản lý danh mục</h2>
        <button className='border-2 border-red-600 px-3 py-1 text-red-400 rounded-full hover:bg-red-300 hover:text-white' onClick={()=>setsetopenUploadCategoty(true)}>Thêm danh mục</button>
      </div>
    </div>
    <div>
      <table className='w-full userTable'>
        <thead>
          <tr>
            <th>Tên danh mục</th>
            <th>Trạng thái</th>
            <th>Ghi chú</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
                  <tr>
                    <td>Danh mục 1</td>
                    <td>Sử dụng</td>
                    <td>Danh mục</td>
                    <td><button className='bg-green-300 p-2 rounded-full hover:bg-green-500 hover:text-white' onClick={()=> setupdateDanhMuc(true)}><RiEdit2Fill/></button></td>
                  </tr>
        
                </tbody>
      </table>
    </div>
    {
        openUploadCategoty && (
          <UploadCategory onClose={()=>setsetopenUploadCategoty(false)}/>
        )
      }
       {
        updateDanhMuc && (
          <UpdateDanhMuc onClose={()=>setupdateDanhMuc(false)}/>  
        )
      }
    </>
  )
}

export default QuanLyDanhMuc
