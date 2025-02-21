import { useState } from 'react'

import UpLoadVoucher from './UpLoadVoucher'

const MaGiamGia = () => {
    const [openUploadCategoty,setsetopenUploadCategoty]= useState(false)
  
  return (
    <>
     <div>
      <div className='bg-white py-2 px-4 flex justify-between items-center'>
        <h2 className='font-bold text-lg'>Quản lý Voucher</h2>
        <button className='border-2 border-red-600 px-3 py-1 hover:text-white text-red-400 rounded-full hover:bg-red-300' onClick={()=>setsetopenUploadCategoty(true)}>Thêm Vocher</button>
      </div>
    </div>
    <div>
      <table className='w-full userTable'>
        <thead>
          <tr>
            <th>Mã</th>
            <th>Tên Vocher</th>
            <th>Số lượng</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <th>Trạng thái</th>
            <th>Ghi chú</th>
            <th>Hành động</th>
          </tr>
        </thead>
      </table>
    </div>
    {
        openUploadCategoty && (
          <UpLoadVoucher onClose={()=>setsetopenUploadCategoty(false)}/>
        )
      }
    </>
  )
}

export default MaGiamGia
