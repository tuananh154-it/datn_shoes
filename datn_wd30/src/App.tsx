
import { useRoutes } from 'react-router-dom';
import './App.css'
import LayOut from './component/LayOut';
import HomeAdmin from './admin/HomeAdmin';
import Login from './pages/Login';
import SignUp from './pages/SignUp';
import ForgotPassword from './pages/ForgotPassword';
import { Toaster } from 'react-hot-toast';

import Dashboard from './admin/Dashboard';
import AllUser from './admin/quanlyadmin/AllUser';
import AllProducts from './admin/quanlysanpham/AllProducts';
import QuanLyDanhMuc from './admin/quanlydanhmuc/QuanLyDanhMuc';
import QuanLyDonhang from './admin/quanlydonhang/QuanLyDonhang';
import UploadAdmin from './admin/quanlyadmin/UploadAdmin';
import MaGiamGia from './admin/quanlymagiamgia/QuanLyMaGiamGia';
import AllKhachHang from './admin/quanlynguoidung/AllKhachHang';
import QuanLyBanner from './admin/quanlybanner/QuanLyBanner';
import QuanLyThuongHieu from './admin/quanlythuonghieu/QuanLyThuongHieu';
import QuanLySize from './admin/quanlysize/QuanLySize';
import QuanLyBinhLuan from './admin/quanlybinhluan/QuanLyBinhLuan';
import QuanLyMauSac from './admin/quanlymau/QuanLyMauSac';

function App() {
  const router = [
    {path:"/", element:<LayOut/>,children:[
      {path:'/login',element:<Login/>},
      {path:'/forgot-password',element:<ForgotPassword/>},
      {path:'/sign-up',element:<SignUp/>},
      {path:'/admin',element:<HomeAdmin/>,children:[
        {path:'/admin/all-users',element:<AllUser/>},
        {path:'/admin/dashboard',element:<Dashboard/>},
        {path:'/admin/all-products',element:<AllProducts/>},
        {path:'/admin/all-categotys', element:<QuanLyDanhMuc/>},
        {path:'/admin/all-order',element:<QuanLyDonhang/>},
        {path:'/admin/update-admin',element:<UploadAdmin/>},
        {path:'/admin/voucher',element:<MaGiamGia/>},
        {path:'/admin/customer',element:<AllKhachHang/>},
        {path:'/admin/all-banner',element:<QuanLyBanner/>},
        {path:'/admin/all-brand',element:<QuanLyThuongHieu/>},
        {path:'/admin/all-brand',element:<QuanLyThuongHieu/>},
        {path:'/admin/all-brand',element:<QuanLyThuongHieu/>},
        {path:'/admin/binhluan',element:<QuanLyBinhLuan/>},
        {path:'/admin/mausac',element:<QuanLyMauSac/>},
        {path:'/admin/size',element:<QuanLySize/>},
       ]}
    ]}
]
const routerLig = useRoutes(router);
  return (
   <>
     <div>{routerLig}</div>
     <Toaster/>
   </>
  )
}

export default App
