import{ useState } from 'react'
import UploadOrder from './UploadOrder'

const QuanLyDonhang = () => {
        const [openUploadCategoty,setopenUploadCategoty]= useState(false)
    
  return (
   <>
     {/* <div>
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
    </div> */}
    <section id="main-content">
          <section className="wrapper">
              <div className="row">
                  <div className="col-lg-12">
                      <section className="card">
                          <header className="card-header">
                             Quản lý đơn hàng
                          </header>
                          <table className="table table-striped table-advance table-hover">
                              <thead>
                              <tr>
                              <th><i className="fa fa-bullhorn"></i> Id</th>
                                  <th><i className="fa fa-bullhorn"></i> Voucher_id</th>
                                  <th><i className="fa fa-bookmark"></i> Customer_id</th>
                                  <th><i className=" fa fa-tags"></i>Total_amount</th>
                                  <th><i className=" fa fa-star"></i> Created_at</th>
                                  <th><i className=" fa fa-users"></i> Updated_at</th>
                                  <th><i className=" fa fa-edit"></i> Action</th>
                                  <th></th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td><a href="#">01</a></td>
                                  <td className="">Vuocher</td>
                                  <td>001</td>
                                  <td>100.000 đ</td>
                                  {/* <td><span className="badge badge-info label-mini">Thanh toán khi nhận hàng</span></td> */}
                                  <td>Created_at</td>
                                  <td>Created_up</td>
                                  {/* <td>note</td> */}
                                  <td>
                                      <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                      <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                      <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              
                              </tbody>
                          </table>
                      </section>
                  </div>
              </div>
          </section>
      </section>
    {
        openUploadCategoty && (
          <UploadOrder onClose={()=>setopenUploadCategoty(false)}/>
        )
      }
   </>
  )
}

export default QuanLyDonhang