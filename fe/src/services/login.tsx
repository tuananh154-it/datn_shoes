import { api } from "../config/axios"

export interface UserLogin{
    password:string,
    email:string
 }
 export const loginForm= (data:UserLogin)=>{
    return api.post('/login',data)
  } 