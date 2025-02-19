import { useState } from 'react'
import UploadCategory from './UploadCategory'

const QuanLyDanhMuc = () => {
    const [openUploadCategoty,setsetopenUploadCategoty]= useState(false)
  
  return (
    <>
     <div>
      <div className='bg-white py-2 px-4 flex justify-between items-center'>
        <h2 className='font-bold text-lg'>Danh mục</h2>
        <button className='border-2 border-red-600 px-3 py-1 text-red-400 rounded-full hover:bg-red-300' onClick={()=>setsetopenUploadCategoty(true)}>Thêm danh mục</button>
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
      </table>
    </div>
    {
        openUploadCategoty && (
          <UploadCategory onClose={()=>setsetopenUploadCategoty(false)}/>
        )
      }
    </>
  )
}

export default QuanLyDanhMuc
