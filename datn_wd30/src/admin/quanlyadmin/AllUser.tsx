import  { useState } from 'react'
import EditUser from './EditUser';
import UploadAdmin from './UploadAdmin';
const AllUser = () => {
  const [openUpdateUser,setopenUpdateUser]= useState(false);
  const [openUploadAdmin,setopenUploadAdmin]= useState(false);
  return (
   <>
      <section id="main-content">
      <div className="row">
                  <div className="col-lg-12">
                      <section className="card">
                          <header className="card-header">
                              Advanced Table
                          </header>
                          <table className="table table-striped table-advance table-hover">
                              <thead>
                              <tr>
                                  <th><i className="fa fa-bullhorn"></i> Name</th>
                                  <th className="hidden-phone"><i className="fa fa-question-circle"></i> Email</th>
                                  <th><i className="fa fa-bookmark"></i> Role</th>
                                  <th><i className=" fa fa-edit"></i> Created date</th>
                                  <th><i className=" fa fa-edit"></i> Action</th>
                                  <th></th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td><a href="#">Vector Ltd</a></td>
                                  <td className="hidden-phone">a@gmail.com</td>
                                  <td>Admin </td>
                                  <td>20/2/2025</td>
                                  <td>
                                      <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                      <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                      <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="#">Vector Ltd</a></td>
                                  <td className="hidden-phone">a@gmail.com</td>
                                  <td>Admin </td>
                                  <td>20/2/2025</td>
                                  <td>
                                      <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                      <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                      <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="#">Vector Ltd</a></td>
                                  <td className="hidden-phone">a@gmail.com</td>
                                  <td>Admin </td>
                                  <td>20/2/2025</td>
                                  <td>
                                      <button className="btn btn-success btn-sm"><i className="fa fa-check"></i></button>
                                      <button className="btn btn-primary btn-sm"><i className="fa fa-pencil"></i></button>
                                      <button className="btn btn-danger btn-sm"><i className="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="#">Vector Ltd</a></td>
                                  <td className="hidden-phone">a@gmail.com</td>
                                  <td>Admin </td>
                                  <td>20/2/2025</td>
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
  {
    openUpdateUser && (
      <EditUser onClose={()=>setopenUpdateUser(false)}/>
    )
  }
  {
    openUploadAdmin && (
      <UploadAdmin onClose={()=>setopenUploadAdmin(false)}/>
    )
  }
   </>
  )
}

export default AllUser