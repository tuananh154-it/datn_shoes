
import { useRoutes } from "react-router-dom";
import "./App.css";
import HomePages from "./Pages/HomePages";
import Shop from "./Pages/Shop";
import Layout from "./Layout/Layout";
import Login from "./Pages/Login";
import Register from "./Pages/Register";
import ForgotPassword from "./Pages/ForgotPassword";

function App() {
    const router = [
        {path:"/", element:<Layout/>,children:[
            {path:"/",element:<HomePages/>},
            {path:"/shop",element:<Shop/>},
            {path:"/login",element:<Login/>},
            {path:"/register",element:<Register/>},
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