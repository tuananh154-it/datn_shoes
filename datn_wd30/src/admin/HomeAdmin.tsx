
import { FaRegUserCircle } from "react-icons/fa";
import { Link, Outlet } from 'react-router-dom';
import { FaHouseUser } from "react-icons/fa";
import { BsFillCartCheckFill } from "react-icons/bs";
import { FaStore } from "react-icons/fa";
import { MdOutlineInventory } from "react-icons/md";
import { GrCertificate } from "react-icons/gr";
import { BiGift } from "react-icons/bi";
import { ImProfile } from "react-icons/im";
import { AiFillWechat } from "react-icons/ai";
import { AiOutlineSortAscending } from "react-icons/ai";
import { BiCamera } from "react-icons/bi";
import { useState } from "react";
import { AiFillHome } from "react-icons/ai";
// import UploadAdmin from "./UpdateAdmin";
import UpdateAdmin from "./UpdateAdmin";
const HomeAdmin = () => {
   const [uploadAdmin,setuploadAdmin]= useState(false)
  return (
    <>
    <div className='min-h-[calc(100vh-120px)] flex'>
        <aside className='bg-gray-600 text-white min-h-full w-full max-w-60 customShadow'>
           <div className='h-32 bg-white text-black flex justify-center items-center flex-col'>
              <FaRegUserCircle className='text-6xl' onClick={()=>setuploadAdmin(true)}/>
              <p className='font-semibold'>Admin</p>
              <p>role</p>
           </div>
           <div>
            <nav className='grid p-4'>
                <Link to={'dashboard'} className='px-3 py-2 flex items-center hover:bg-slate-100 hover:text-black'><AiFillHome className="text-2xl"/> Trang chủ</Link>
                <Link to={'all-users'} className='px-3 py-2 flex items-center hover:bg-slate-100 hover:text-black'><FaHouseUser className="text-2xl"/> Admin</Link>
                <Link to={'all-order'} className='px-3 py-2 flex items-center hover:bg-slate-100 hover:text-black'><BsFillCartCheckFill className="text-2xl"/>Quản lý đơn hàng</Link>
                <Link to={'all-products'} className='px-3 py-2 flex items-center hover:bg-slate-100 hover:text-black'><FaStore className="text-2xl"/>Quản lý sản phẩm</Link>
                <Link to={'all-categotys'} className='px-3 py-2 flex items-center hover:bg-slate-100 hover:text-black'><MdOutlineInventory className="text-2xl"/>Quản lý danh mục</Link>
                <Link to={'brand'} className= 'px-3 py-2 flex items-center hover:bg-slate-100 hover:text-black'><GrCertificate className="text-2xl"/>Quản lý thương hiệu</Link>
                <Link to={'size'} className='px-3 py-2 flex items-center hover:bg-slate-100 hover:text-black'><AiOutlineSortAscending className="text-2xl"/> Quản lý size</Link>
                <Link to={'customer'} className='px-3 py-2 flex items-center hover:bg-slate-100 hover:text-black'><ImProfile className="text-2xl"/> Quản lý khách hàng</Link>
                <Link to={'banner'} className='px-3 py-2 flex items-center hover:bg-slate-100 hover:text-black'><BiCamera className="text-2xl"/> Quản lý banner</Link>
                <Link to={''} className='px-3 py-2 flex items-center hover:bg-slate-100 hover:text-black'><AiFillWechat className="text-2xl"/> Quản lý liên hệ</Link>
                <Link to={'voucher'} className='px-3 py-2 flex items-center hover:bg-slate-100 hover:text-black'><BiGift className="text-2xl"/> Quản lý mã giảm giá</Link>
            </nav>
           </div>
        </aside>
        <main className='w-full h-full p-2'>
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