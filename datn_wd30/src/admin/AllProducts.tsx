import { useState } from 'react'
import UploadProduct from './UploadProduct'
// import { MdModeEditOutline } from "react-icons/md";
import { MdEditSquare } from "react-icons/md";
import UpdateProduct from './UpdateProduct';
const AllProducts = () => {
  const [openUploadProduct,setOpenUploadProduct]= useState(false)
  const [updateProduct,setupdateProduct]= useState(false)

  return (
    <>
    <div>
      <div className='bg-white py-2 px-4 flex justify-between items-center'>
        <h2 className='font-bold text-lg'>Danh sách sản phẩm</h2>
        <button className='border-2 border-red-600 px-3 py-1 text-red-400 rounded-full hover:bg-red-300 hover:text-white' onClick={()=>setOpenUploadProduct(true)}>Thêm sản phẩm</button>
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
        <tbody>
          <tr>
            <td>Sản phẩm 1</td>
            <td><img src=""/></td>
            <td>100</td>
            <td>Sản phẩm 1</td>
            <td>Sử dụng</td>
            <td><button className='bg-green-300 p-2 rounded-full hover:bg-green-500 hover:text-white' onClick={()=> setupdateProduct(true)}><MdEditSquare/></button></td>
          </tr>

        </tbody>
      </table>
    </div>
    <div>
      {
        openUploadProduct && (
          <UploadProduct onClose={()=>setOpenUploadProduct(false)}/>
        )
      }
       {
        updateProduct && (
          <UpdateProduct onClose={()=>setupdateProduct(false)}/>
        )
      }
    </div>
    </>
  )
}

export default AllProducts