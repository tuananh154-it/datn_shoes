import axios from "axios";
import { LoginUser, SignInUser } from "../types/User";
const token  = localStorage.getItem("token");
const http = axios.create({
    baseURL : 'http://localhost:8080/api',
    headers:{
        Authorization: token ? `Bearer ${token}` : null,
        'Content-Type':'application/json'
    }
})
export const SignUpForm = (data:SignInUser)=>{
       return http.post('/signup',data)
}
export const LoginForm = (data:LoginUser)=>{
    return http.post('/login', data)
}