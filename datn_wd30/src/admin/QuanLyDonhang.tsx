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
                                  <th><i className="fa fa-bullhorn"></i> Địa chỉ</th>
                                  <th className="hidden-phone"><i className="fa fa-question-circle"></i>Số điện thoại</th>
                                  <th><i className="fa fa-bookmark"></i> Trạng thái</th>
                                  <th><i className=" fa fa-edit"></i>Tổng tiền</th>
                                  <th><i className=" fa fa-edit"></i> Trạng thái thanh toán</th>
                                  <th><i className=" fa fa-edit"></i> Id người dùng</th>
                                  <th><i className=" fa fa-edit"></i> Id nhân viên</th>
                                  <th><i className=" fa fa-edit"></i> Ghi chú</th>
                                  <th><i className=" fa fa-edit"></i> Hành động</th>
                                  <th></th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td><a href="#">Hà Nội</a></td>
                                  <td className="hidden-phone">0123456789</td>
                                  <td>Đang xử lý</td>
                                  <td>100.000 đ</td>
                                  <td><span className="badge badge-info label-mini">Thanh toán khi nhận hàng</span></td>
                                  <td>0111</td>
                                  <td>1222</td>
                                  <td>note</td>
                                  <td>
                                      <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                      <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                      <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="#">Hà Nội</a></td>
                                  <td className="hidden-phone">0123456789</td>
                                  <td>Đang xử lý</td>
                                  <td>100.000 đ</td>
                                  <td><span className="badge badge-info label-mini">Thanh toán khi nhận hàng</span></td>
                                  <td>0111</td>
                                  <td>1222</td>
                                  <td>note</td>
                                  <td>
                                      <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                      <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                      <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="#">Hà Nội</a></td>
                                  <td className="hidden-phone">0123456789</td>
                                  <td>Đang xử lý</td>
                                  <td>100.000 đ</td>
                                  <td><span className="badge badge-info label-mini">Thanh toán khi nhận hàng</span></td>
                                  <td>0111</td>
                                  <td>1222</td>
                                  <td>note</td>
                                  <td>
                                      <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                      <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                      <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="#">Hà Nội</a></td>
                                  <td className="hidden-phone">0123456789</td>
                                  <td>Đang xử lý</td>
                                  <td>100.000 đ</td>
                                  <td><span className="badge badge-info label-mini">Thanh toán khi nhận hàng</span></td>
                                  <td>0111</td>
                                  <td>1222</td>
                                  <td>note</td>
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