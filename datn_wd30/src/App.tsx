
import { useRoutes } from 'react-router-dom';
import './App.css'
import LayOut from './component/LayOut';
import HomeAdmin from './admin/HomeAdmin';
import AllUser from './admin/AllUser';
import AllProducts from './admin/AllProducts';
import QuanLyDanhMuc from './admin/QuanLyDanhMuc';
import QuanLyDonhang from './admin/QuanLyDonhang';
import MaGiamGia from './admin/MaGiamGia';
import QuanLyThuongHieu from './admin/QuanLyThuongHieu';
import QuanLySize from './admin/QuanLySize';
import Login from './pages/Login';
import SignUp from './pages/SignUp';
import ForgotPassword from './pages/ForgotPassword';
import QuanLyBanner from './admin/QuanLyBanner';
import UploadAdmin from './admin/UloadAdmin';

function App() {
  const router = [
    {path:"/", element:<LayOut/>,children:[
      {path:'/login',element:<Login/>},
      {path:'/forgot-password',element:<ForgotPassword/>},
      {path:'/sign-up',element:<SignUp/>},
      {path:'/admin',element:<HomeAdmin/>,children:[
        {path:'/admin/all-users',element:<AllUser/>},
        {path:'/admin/all-products',element:<AllProducts/>},
        {path:'/admin/all-categotys', element:<QuanLyDanhMuc/>},
        {path:'/admin/all-order',element:<QuanLyDonhang/>},
        {path:'/admin/update-admin',element:<UploadAdmin/>},
        {path:'/admin/voucher',element:<MaGiamGia/>},
        {path:'/admin/banner',element:<QuanLyBanner/>},
        {path:'/admin/brand',element:<QuanLyThuongHieu/>},
        {path:'/admin/size',element:<QuanLySize/>},
       ]}
    ]}
]
const routerLig = useRoutes(router);
  return (
   <>
     <div>{routerLig}</div>
   </>
  )
}

export default App
