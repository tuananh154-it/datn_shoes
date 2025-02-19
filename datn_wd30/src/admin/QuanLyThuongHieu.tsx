import { useState } from 'react'
import UploadThuongHieu from './UploadThuongHieu'

const QuanLyThuongHieu = () => {
    const [openUploadCategoty,setsetopenUploadCategoty]= useState(false)
  
  return (
    <>
     <div>
      <div className='bg-white py-2 px-4 flex justify-between items-center'>
        <h2 className='font-bold text-lg'>Thương hiệu</h2>
        <button className='border-2 border-red-600 px-3 py-1 text-red-400 rounded-full hover:bg-red-300' onClick={()=>setsetopenUploadCategoty(true)}>Thêm thương hiệu</button>
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
      </table>
    </div>
    {
        openUploadCategoty && (
          <UploadThuongHieu onClose={()=>setsetopenUploadCategoty(false)}/>
        )
      }
    </>
  )
}

export default QuanLyThuongHieu
