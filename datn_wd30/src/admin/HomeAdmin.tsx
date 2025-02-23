
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
                  <li>
                      <Link  className="active" to={'all-categories'}>
                      <i  className="fa fa-laptop"></i>
                          <span>Quản lý danh mục</span>
                      </Link>
                  </li>
                  <li>
                      <Link  className="active" to={'brand'}>
                      <i  className="fa fa-laptop"></i>
                          <span>Quản lý thương hiệu</span>
                      </Link>
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