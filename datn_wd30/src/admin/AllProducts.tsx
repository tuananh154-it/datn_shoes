import { useState } from 'react'
import UploadProduct from './UploadProduct'

const AllProducts = () => {
  const [openUploadProduct,setOpenUploadProduct]= useState(false)
  return (
    <>
    <div>
      <div className='bg-white py-2 px-4 flex justify-between items-center'>
        <h2 className='font-bold text-lg'>Sản phẩm</h2>
        <button className='border-2 border-red-600 px-3 py-1 text-red-400 rounded-full hover:bg-red-300' onClick={()=>setOpenUploadProduct(true)}>Thêm sản phẩm</button>
      </div>
    </div>
    <div>
      <table className='w-full userTable'>
        <thead>
          <tr>
            <th>Tên Sản phẩm</th>
            <th>Ảnh sản phẩm</th>
            <th>Số phân loại</th>
            <th>Ghi chú</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
          </tr>
        </thead>
      </table>
    </div>
    <div>
      {
        openUploadProduct && (
          <UploadProduct onClose={()=>setOpenUploadProduct(false)}/>
        )
      }
    </div>
    </>
  )
}

export default AllProducts