
import { useState } from 'react'
import UploadSize from './UploadSize'

const QuanLySize = () => {
    const [openUploadCategoty,setsetopenUploadCategoty]= useState(false)
  
  return (
    <>
     <section id="main-content">
          <section className="wrapper">
              <div className="row">
                  <div className="col-lg-12">
                      <section className="card">
                          <header className="card-header">
                             Quản lý size
                          </header>
                          <table className="table table-striped table-advance table-hover">
                              <thead>
                              <tr>
                                  <th><i className="fa fa-bullhorn"></i>Id</th>
                                  <th><i className="fa fa-bullhorn"></i>Name</th>
                                  <th><i className="fa fa-bullhorn"></i>Discount_price</th>
                                  <th><i className="fa fa-bookmark"></i>image_url</th>
                                  <th><i className="fa fa-bookmark"></i>Created_at</th>
                                  <th><i className="fa fa-bookmark"></i>Created_up</th>
                                  <th><i className="fa fa-bookmark"></i>Status</th>
                                  <th><i className=" fa fa-edit"></i> Action</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td><a href="#">1</a></td>
                                  <td><a href="#">38</a></td>
                                  <td><a href="#">100.000đ</a></td>
                                  <td className="hidden-phone"><img width={200} height={200} src='https://salt.tikicdn.com/cache/w1200/ts/product/c9/97/70/46f4d5b4ffc1fe8b29f272ac0261b773.jpg'/></td>
                                  <td>created_at</td>
                                  <td>created_up</td>
                                  <td><span className="badge badge-info label-mini">Sử dụng</span></td>
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
          <UploadSize  onClose={()=>setsetopenUploadCategoty(false)}/>
        )
      }
    </>
  )
}

export default QuanLySize
