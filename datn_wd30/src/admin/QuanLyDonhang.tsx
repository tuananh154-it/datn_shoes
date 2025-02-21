import{ useState } from 'react'
import UploadOrder from './UploadOrder'

const QuanLyDonhang = () => {
        const [openUploadCategoty,setopenUploadCategoty]= useState(false)
    
  return (
   <>
     <div>
      <div className='bg-white py-2 px-4 flex justify-between items-center'>
        <h2 className='font-bold text-lg'>Quản lý đơn hàng</h2>
        <button className='border-2 border-red-600 px-3 py-1 text-red-400 rounded-full hover:bg-red-300 hover:text-white' onClick={()=>setopenUploadCategoty(true)}>Thêm mới đơn hàng</button>
      </div>
    </div>
    <div>
      <table className='w-full userTable'>
        <thead>
          <tr>
            <th>Địa chỉ</th>
            <th>Số điện thoại</th>
            <th>Trạng thái</th>
            <th>Tổng tiền</th>
            <th>Trạng thái thanh toán</th>
            <th>Id người dùng</th>
            <th>Id nhân viên</th>
            <th>Ghi chú</th>
            <th>Hành động</th>
          </tr>
        </thead>
      </table>
    </div>
    {
        openUploadCategoty && (
          <UploadOrder onClose={()=>setopenUploadCategoty(false)}/>
        )
      }
   </>
  )
}

export default QuanLyDonhang