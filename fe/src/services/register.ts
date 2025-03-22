import { api } from "../config/axios"

 export interface User{
    name:string,
    password:string,
    email:string
 }
export const registerForm = (data:User)=>{
  return api.post("/register",data)
}
