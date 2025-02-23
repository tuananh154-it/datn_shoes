import { useState } from 'react'
import UploadProduct from './UploadProduct'
// import { MdModeEditOutline } from "react-icons/md";
// import { MdEditSquare } from "react-icons/md";
import UpdateProduct from './UpdateProduct';
const AllProducts = () => {
  const [openUploadProduct,setOpenUploadProduct]= useState(false)
  const [updateProduct,setupdateProduct]= useState(false)

  return (
    <>
     <section id="main-content">
          <section className="wrapper">
              <div className="row">
                  <div className="col-lg-12">
                      <section className="card">
                          <header className="card-header">
                             Danh sách sản phẩm
                          </header>
                          <table className="table table-striped table-advance table-hover">
                              <thead>
                              <tr>
                                  <th><i className="fa fa-bullhorn"></i> Tên sản phẩm</th>
                                  <th className="hidden-phone"><i className="fa fa-question-circle"></i> Ảnh sản phẩm</th>
                                  <th><i className="fa fa-bookmark"></i> Số phân loại</th>
                                  <th><i className="fa fa-comments"></i> Hành động</th>
                                  <th><i className=" fa fa-edit"></i> Hành động</th>
                                  <th></th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td><a href="#">Sản phẩm 1</a></td>
                                  <td className="hidden-phone"><img width={200} height={200} src='https://salt.tikicdn.com/cache/w1200/ts/product/c9/97/70/46f4d5b4ffc1fe8b29f272ac0261b773.jpg'/></td>
                                  <td>100</td>
                                  <td><span className="badge badge-info label-mini">Sử dụng</span></td>
                                  <td>
                                      <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                      <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                      <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="#">Sản phẩm 1</a></td>
                                  <td className="hidden-phone"><img width={200} height={200} src='https://salt.tikicdn.com/cache/w1200/ts/product/c9/97/70/46f4d5b4ffc1fe8b29f272ac0261b773.jpg'/></td>
                                  <td>100</td>
                                  <td><span className="badge badge-info label-mini">Sử dụng</span></td>
                                  <td>
                                      <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                      <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                      <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="#">Sản phẩm 1</a></td>
                                  <td className="hidden-phone"><img width={200} height={200} src='https://salt.tikicdn.com/cache/w1200/ts/product/c9/97/70/46f4d5b4ffc1fe8b29f272ac0261b773.jpg'/></td>
                                  <td>100</td>
                                  <td><span className="badge badge-info label-mini">Sử dụng</span></td>
                                  <td>
                                      <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                      <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                      <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="#">Sản phẩm 1</a></td>
                                  <td className="hidden-phone"><img width={200} height={200} src='https://salt.tikicdn.com/cache/w1200/ts/product/c9/97/70/46f4d5b4ffc1fe8b29f272ac0261b773.jpg'/></td>
                                  <td>100</td>
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
        openUploadProduct && (
          <UploadProduct onClose={()=>setOpenUploadProduct(false)}/>
        )
      }
       {
        updateProduct && (
          <UpdateProduct onClose={()=>setupdateProduct(false)}/>
        )
      }
 
    </>
  )
}

export default AllProducts