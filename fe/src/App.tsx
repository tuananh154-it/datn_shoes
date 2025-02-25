
import { useRoutes } from "react-router-dom";
import "./App.css";
import Layouts from "./Layout/Layouts";
import HomePages from "./Pages/HomePages";

function App() {
    const router = [
        {path:"/", element:<Layouts/>,children:[
            {path:"/",element:<HomePages/>}
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