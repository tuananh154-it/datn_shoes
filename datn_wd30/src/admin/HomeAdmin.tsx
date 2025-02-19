
import { FaRegUserCircle } from "react-icons/fa";
import { Link, Outlet } from 'react-router-dom';
const HomeAdmin = () => {
  return (
    <>
    <div className='min-h-[calc(100vh-120px)] flex'>
        <aside className='bg-gray-600 text-white min-h-full w-full max-w-60 customShadow'>
           <div className='h-32 flex justify-center items-center flex-col'>
              <FaRegUserCircle className='text-6xl'/>
              <p className='font-semibold'>Admin</p>
              <p>role</p>
           </div>
           <div>
            <nav className='grid p-4 items-center justify-center'>
                <Link to={'all-users'} className='hover:bg-slate-100'>Admin</Link>
                <Link to={'all-order'} className='hover:bg-slate-100'>Quản lý đơn hàng</Link>
                <Link to={'all-products'} className='hover:bg-slate-100'>Quản lý sản phẩm</Link>
                <Link to={'all-categotys'} className='hover:bg-slate-100'>Quản lý danh mục</Link>
                <Link to={'brand'} className='hover:bg-slate-100'>Quản lý thương hiệu</Link>
                <Link to={'size'} className='hover:bg-slate-100'>Quản lý site</Link>
                <Link to={''} className='hover:bg-slate-100'>Quản lý khách hàng</Link>
                <Link to={''} className='hover:bg-slate-100'>Quản lý liên hệ</Link>
                <Link to={'voucher'} className='hover:bg-slate-100'>Quản lý mã giảm giá</Link>
            </nav>
           </div>
        </aside>
        <main className='w-full h-full p-2'>
           <Outlet/>
        </main>
    </div>
    </>
  )
}

export default HomeAdmin