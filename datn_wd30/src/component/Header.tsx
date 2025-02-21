
import { FaRegUserCircle } from "react-icons/fa";
import { FaSearch } from "react-icons/fa";
import { FaShoppingCart } from "react-icons/fa";
import { Link } from 'react-router-dom';
import Logo from '../assets/th-removebg-preview.png'
import UploadAdmin from "../admin/UpdateAdmin";
import { useState } from "react";
const Header = () => {
       const [uploadAdmin,setuploadAdmin]= useState(false)
  return (
    <header className='h-19 shadow-md bg-white'>
    <div className='h-full container mx-auto flex items-center px-9 justify-between'>
        <div className=''>
         <Link to={'upload'}><img  width={150} height={150} src={Logo}/></Link>
        </div>
        <div className='hidden lg:flex items-center w-full justify-between max-w-sm border rounded-full focus-within:shadow'>
            <input type='text' placeholder='search product here...' className='w-full outline-none'/>
            <div className='text-lg min-w-[50px] h-8 bg-red-600 flex justify-center items-center rounded-r-full text-white'><FaSearch/></div>
        </div>
        <div className='flex items-center gap-4'>
            <div className='text-3xl cursor-pointer'>
                <FaRegUserCircle/>  
            </div>
            <div className='text-2xl relative'>
                <span><FaShoppingCart/></span>
                <div className='bg-red-600 text-white rounded-full flex justify-center items-center w-5 h-5 p-1 absolute -top-2 -right-3'>
                    <p className='text-sm'>0</p>
                </div>
            </div>
            <div>
            <Link to={'/login'} className='px-3 py-1 bg-red-500 hover:bg-red-300 rounded-full text-white'>Đăng nhập</Link>
            </div>
        </div>
       
    </div>
    {uploadAdmin &&(
         <UploadAdmin onClose={()=>setuploadAdmin(false)}/>
        )}
    </header>
  )
}

export default Header