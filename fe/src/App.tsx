
import { useRoutes } from "react-router-dom";
import "./App.css";
import HomePages from "./Pages/HomePages";
import Shop from "./Pages/Shop";
import Layout from "./Layout/Layout";
import Login from "./Pages/Login";
import Register from "./Pages/Register";
import ForgotPassword from "./Pages/ForgotPassword";
import Cart from "./Pages/Cart";
import ProductDetail from "./Pages/ProductDetail";
import Blog from "./Pages/Blog";
import Wishlist from "./Pages/Wishlist";
import CheckOut from "./Pages/CheckOut";
import Contact from "./Pages/Contact";
import BlogDetail from "./Pages/BlogDetail";
function App() {
    const router = [
        {path:"/", element:<Layout/>,children:[
            {path:"/",element:<HomePages/>},
            {path:"/shop",element:<Shop/>},
            {path:"/cart",element:<Cart/>},
            {path:"/blog",element:<Blog/>},
            {path:"/blog/:id",element:<BlogDetail/>},
            {path:"/contacts",element:<Contact/>},
            {path:"/wishlist",element:<Wishlist/>},
            {path:"/product_detail",element:<ProductDetail/>},
            {path:"/login",element:<Login/>},
            {path:"/register",element:<Register/>},
            {path:"/checkout",element:<CheckOut/>},
            {path:"/quenmatkhau",element:<ForgotPassword/>}
        ]}
    ]
    const routerLig = useRoutes(router);
      return (
       <>
         <div>{routerLig}</div>
       </>
      )
}

export default App;