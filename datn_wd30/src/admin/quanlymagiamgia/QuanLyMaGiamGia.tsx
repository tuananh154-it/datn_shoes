import { useState } from 'react'

import UpLoadVoucher from './UpLoadVoucher'

const MaGiamGia = () => {
    const [openUploadCategoty,setsetopenUploadCategoty]= useState(false)
  
  return (
    <>
    <section id="main-content">
    <section className="wrapper">
        <div className="row">
            <div className="col-lg-12">
            <section className="card">
                          <header className="card-header">
                              Quản lý max giảm giá
                          </header>
                          <table className="table table-striped table-advance table-hover">
                              <thead>
                              <tr>
                                  <th><i className="fa fa-bullhorn"></i>Id</th>
                                  <th className="hidden-phone"><i className="fa fa-question-circle"></i>discount_amount</th>
                                  <th><i className="fa fa-bookmark"></i>discount_percent </th>
                                  <th><i className=" fa fa-edit"></i>expiration_date</th>
                                  <th><i className=" fa fa-edit"></i> min_purchase_amount</th>
                                  <th><i className=" fa fa-edit"></i>max_discount_amount</th>
                                  <th><i className=" fa fa-edit"></i>terms_and_conditions</th>
                                  <th><i className=" fa fa-edit"></i>Status</th>
                                  <th>Action</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td><a href="#">11</a></td>
                                  <td className="hidden-phone">Kiet</td>
                                  <td>123456123456</td>
                                  <td>kiet123@gmail.com</td>
                                  <td>Hà nội</td>
                                  <td>Hà nội</td>
                                  <td>Hà nội</td>
                                  <td>Hà nội</td>
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
          <UpLoadVoucher onClose={()=>setsetopenUploadCategoty(false)}/>
        )
      }
    </>
  )
}

export default MaGiamGia
