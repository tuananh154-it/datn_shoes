
import { useState } from "react";

// import UploadAdmin from "./UpdateAdmin";
import UpdateAdmin from "./UpdateAdmin";
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
                  <li  className="sub-menu">
                      <a href="javascript:;" >
                          <i  className="fa fa-th"></i>
                          <span>Data Tables</span>
                      </a>
                      <ul  className="sub">
                          <li><a  href="basic_table.html">Basic Table</a></li>
                          <li><a  href="responsive_table.html">Responsive Table</a></li>
                          <li><a  href="dynamic_table.html">Dynamic Table</a></li>
                          <li><a  href="editable_table.html">Editable Table</a></li>
                      </ul>
                  </li>
                  <li  className="sub-menu">
                      <a href="javascript:;" >
                          <i  className=" fa fa-envelope"></i>
                          <span>Mail</span>
                      </a>
                      <ul  className="sub">
                          <li><a  href="inbox.html">Inbox</a></li>
                          <li><a  href="inbox_details.html">Inbox Details</a></li>
                      </ul>
                  </li>
                  <li  className="sub-menu">
                      <a href="javascript:;" >
                          <i  className=" fa fa-bar-chart-o"></i>
                          <span>Charts</span>
                      </a>
                      <ul  className="sub">
                          <li><a  href="morris.html">Morris</a></li>
                          <li><a  href="chartjs.html">Chartjs</a></li>
                          <li><a  href="flot_chart.html">Flot Charts</a></li>
                          <li><a  href="xchart.html">xChart</a></li>
                      </ul>
                  </li>
                  <li  className="sub-menu">
                      <a href="javascript:;" >
                          <i  className="fa fa-shopping-cart"></i>
                          <span>Shop</span>
                      </a>
                      <ul  className="sub">
                          <li><a  href="product_list.html">List View</a></li>
                          <li><a  href="product_details.html">Details View</a></li>
                      </ul>
                  </li>
                  <li>
                      <a href="google_maps.html" >
                          <i  className="fa fa-map-marker"></i>
                          <span>Google Maps </span>
                      </a>
                  </li>
                  <li  className="sub-menu">
                      <a href="javascript:;">
                          <i  className="fa fa-comments-o"></i>
                          <span>Chat Room</span>
                      </a>
                      <ul  className="sub">
                          <li><a  href="lobby.html">Lobby</a></li>
                          <li><a  href="chat_room.html"> Chat Room</a></li>
                      </ul>
                  </li>
                  <li  className="sub-menu">
                      <a href="javascript:;" >
                          <i  className="fa fa-glass"></i>
                          <span>Extra</span>
                      </a>
                      <ul  className="sub">
                          <li><a  href="blank.html">Blank Page</a></li>
                          <li><a  href="sidebar_closed.html">Sidebar Closed</a></li>
                          <li><a  href="people_directory.html">People Directory</a></li>
                          <li><a  href="coming_soon.html">Coming Soon</a></li>
                          <li><a  href="lock_screen.html">Lock Screen</a></li>
                          <li><a  href="profile.html">Profile</a></li>
                          <li><a  href="invoice.html">Invoice</a></li>
                          <li><a  href="project_list.html">Project List</a></li>
                          <li><a  href="project_details.html">Project Details</a></li>
                          <li><a  href="search_result.html">Search Result</a></li>
                          <li><a  href="pricing_table.html">Pricing Table</a></li>
                          <li><a  href="faq.html">FAQ</a></li>
                          <li><a  href="fb_wall.html">FB Wall</a></li>
                          <li><a  href="404.html">404 Error</a></li>
                          <li><a  href="500.html">500 Error</a></li>
                      </ul>
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