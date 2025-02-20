import {z} from 'zod';
export const signin = z.object({
    email: z.string().email({message:"Mời nhập địa chỉ email?"}),
    password:z.string().min(6,{message:"Mật khẩu ít nhất 6 kí tự?"})
})
export const signup = z.object({
    profilePic:z.string(),
    email: z.string().email({message:"Mời nhập địa chỉ email?"}),
    name:z.string().min(3,{message:"Tên người dùng ít nhất 3 kí tự?"}),
    password:z.string().min(6,{message:"Mật khẩu ít nhất 6 kí tự?"}),
    confirmPassword:z.string().min(6,{message:"Mật khẩu ít nhất 6 kí tự?"}),
})