
import { useState } from "react";

// import UploadAdmin from "./UpdateAdmin";
import UpdateAdmin from "./quanlyadmin/UpdateAdmin";
import { Link, Outlet } from "react-router-dom";
const HomeAdmin = () => {
   const [uploadAdmin,setuploadAdmin]= useState(false)
  return (
    <>
    <div  className='min-h-[calc(100vh-120px)] flex'>
    <aside>
          <div id="sidebar" className="nav-collapse ">
              <ul  className="sidebar-menu" id="nav-accordion">
                  <li>
                      <Link  className="active" to={'dashboard'}>
                          <i  className="fa fa-dashboard"></i>
                          <span>Dashboard</span>
                      </Link>
                  </li>
                  <li>
                      <Link  className="active" to={'all-users'}>
                      <i  className="fa fa-laptop"></i>
                          <span>Admin</span>
                      </Link>
                  </li>
                  <li>
                      <Link  className="active" to={'all-order'}>
                      <i  className="fa fa-laptop"></i>
                          <span>Quản lý đơn hàng</span>
                      </Link>
                  </li>
                  <li>
                      <Link  className="active" to={'all-products'}>
                      <i  className="fa fa-laptop"></i>
                          <span>Quản lý sản phẩm</span>
                      </Link>
                  </li>
                  <li  className="sub-menu">
                      <a href="javascript:;" >
                          <i  className="fa fa-cogs"></i>
                          <span>Components</span>
                      </a>
                      <ul  className="sub">
                          <li><a  href="grids.html">Grids</a></li>
                          <li><a  href="calendar.html">Calendar</a></li>
                          <li><a  href="gallery.html">Gallery</a></li>
                          <li><a  href="todo_list.html">Todo List</a></li>
                          <li><a  href="draggable_portlet.html">Draggable Portlet</a></li>
                          <li><a  href="tree.html">Tree View</a></li>
                      </ul>
                  </li>
                  <li  className="sub-menu">
                      <a href="javascript:;" >
                          <i  className="fa fa-tasks"></i>
                          <span>Form Stuff</span>
                      </a>
                      <ul  className="sub">
                          <li><a  href="form_component.html">Form Components</a></li>
                          <li><a  href="advanced_form_components.html">Advanced Components</a></li>
                          <li><a  href="form_wizard.html">Form Wizard</a></li>
                          <li><a  href="form_validation.html">Form Validation</a></li>
                          <li><a  href="dropzone.html">Dropzone File Upload</a></li>
                          <li><a  href="inline_editor.html">Inline Editor</a></li>
                          <li><a  href="image_cropping.html">Image Cropping</a></li>
                          <li><a  href="file_upload.html">Multiple File Upload</a></li>
                      </ul>
                  </li>
                  <li>
                      <Link  className="active" to={'size'}>
                      <i  className="fa fa-laptop"></i>
                          <span>Quản lý Size</span>
                      </Link>
                  </li>
                  <li>
                      <Link  className="active" to={'customer'}>
                      <i  className="fa fa-laptop"></i>
                          <span>Quản lý khách hàng</span>
                      </Link>
                  </li>
                  <li>
                      <Link  className="active" to={'banner'}>
                      <i  className="fa fa-laptop"></i>
                          <span>Quản lý banner</span>
                      </Link>
                  </li>
                  <li>
                      <Link  className="active" to={'lienhe'}>
                      <i  className="fa fa-laptop"></i>
                          <span>Quản lý liên hệ</span>
                      </Link>
                  </li>
                  <li>
                      <Link  className="active" to={'voucher'}>
                      <i  className="fa fa-laptop"></i>
                          <span>Quản lý mã giảm giá</span>
                      </Link>
                  </li>
                  <li>
                      <Link  className="active" to={'binhluan'}>
                      <i  className="fa fa-laptop"></i>
                          <span>Quản lý bình luận</span>
                      </Link>
                  </li>
                  <li>
                      <Link  className="active" to={'mausac'}>
                      <i  className="fa fa-laptop"></i>
                          <span>Quản lý màu sắc</span>
                      </Link>
                  </li>
                  <li>
                      <a  href="login.html">
                          <i  className="fa fa-user"></i>
                          <span>Login Page</span>
                      </a>
                  </li>
                  <li  className="sub-menu">
                      <a href="javascript:;" >
                          <i  className="fa fa-sitemap"></i>
                          <span>Multi level Menu</span>
                      </a>
                      <ul  className="sub">
                          <li><a  href="javascript:;">Menu Item 1</a></li>
                          <li  className="sub-menu">
                              <a  href="boxed_page.html">Menu Item 2</a>
                              <ul  className="sub">
                                  <li><a  href="javascript:;">Menu Item 2.1</a></li>
                                  <li  className="sub-menu">
                                      <a  href="javascript:;">Menu Item 3</a>
                                      <ul  className="sub">
                                          <li><a  href="javascript:;">Menu Item 3.1</a></li>
                                          <li><a  href="javascript:;">Menu Item 3.2</a></li>
                                      </ul>
                                  </li>
                              </ul>
                          </li>
                      </ul>
                  </li>
               

              </ul>
           
          </div>
      </aside>
        <main  className='w-full h-full p-2'>
           <Outlet/>
        </main>
        {uploadAdmin &&(
         <UpdateAdmin onClose={()=>setuploadAdmin(false)}/>
        )}
    </div>
    </>
  )
}

export default HomeAdmin