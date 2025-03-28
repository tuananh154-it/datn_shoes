import { api } from "../config/axios"
import {z} from "zod"
 export interface User{
    name:string,
    password:string,
    email:string,
    password_confirmation:string,
    phone?:string,
 }
export const registerForm = (data:User)=>{
  return api.post("/register",data)
}

export const schemaRegister = z.object({
  name: z.string().min(3, "Tên phải có ít nhất 3 ký tự").trim(),
  email: z.string().email("Email không hợp lệ"),
  password: z.string().min(8, "Mật khẩu phải có ít nhất 8 ký tự"),
  password_confirmation: z.string().min(8, "Mật khẩu xác nhận phải có ít nhất 8 ký tự"),
}).refine(data => data.password === data.password_confirmation, {
  message: "Mật khẩu xác nhận không khớp",
  path: ["password_confirmation"], // Gán lỗi vào trường password_confirmation
});
