
import { useState } from 'react'
import UploadSize from './UploadSize'

const QuanLySize = () => {
    const [openUploadCategoty,setsetopenUploadCategoty]= useState(false)
  
  return (
    <>
     <div>
      <div className='bg-white py-2 px-4 flex justify-between items-center'>
        <h2 className='font-bold text-lg'>Size</h2>
        <button className='border-2 border-red-600 px-3 py-1 text-red-400 rounded-full hover:bg-red-300' onClick={()=>setsetopenUploadCategoty(true)}>Thêm site</button>
      </div>
    </div>
    <div>
      <table className='w-full userTable'>
        <thead>
          <tr>
            <th>Tên size</th>
            <th>Trạng thái</th>
            <th>Ghi chú</th>
            <th>Hành động</th>
          </tr>
        </thead>
      </table>
    </div>
    {
        openUploadCategoty && (
          <UploadSize  onClose={()=>setsetopenUploadCategoty(false)}/>
        )
      }
    </>
  )
}

export default QuanLySize
