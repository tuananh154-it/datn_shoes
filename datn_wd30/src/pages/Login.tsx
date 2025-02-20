import { useState } from 'react'
import { FaEye } from "react-icons/fa";
import { FaEyeSlash } from "react-icons/fa";
import { zodResolver } from "@hookform/resolvers/zod";
import iconUser from '../assets/cHJpdmF0ZS9sci9pbWFnZXMvd2Vic2l0ZS8yMDIzLTAxL3JtNjA5LXNvbGlkaWNvbi13LTAwMi1wLnBuZw.jpg'
import { Link } from 'react-router-dom';
import { useForm } from 'react-hook-form';
import { signin } from '../validate/validateForm';
import { LoginUser } from '../types/User';
const Login = () => {
    const [showPassword,setshowPassword]= useState(false);
    const {register, handleSubmit,formState:{errors}}= useForm<LoginUser>({resolver:zodResolver(signin)})
   
    const onSubmit = (data:LoginUser) =>{
        console.log(data)
        // loginForm(data).then(({data})=>{
        //  toast.success("Đã đăng nhập thành công")
        // //  console.log(localStorage.setItem("user", JSON.stringify(data.token)));
        //  localStorage.setItem("user", JSON.stringify(data.data))
        //  localStorage.setItem("token",data.token)
        //  nav("/admin")
        // })
        // .catch((e)=>{toast.error("Error:"+e.message)})
      }
  return (
   <>
   <section id='login'>
       <div className='mx-auto bg-slate-100 container p-4'>
              <div className='w-full p-2 bg-white max-w-md mx-auto mt-4'>
                  <div className='w-20 mt-4 mx-auto'>
                    <img src={iconUser} alt='icon user'/>
                  </div>
                  <form className='pt-6' onSubmit={handleSubmit(onSubmit)}>
                    <div className='grid'>
                        <label>Email:</label>
                        <div className='bg-slate-100 p-2'>
                        <input type='email' placeholder='Nhập email...' {...register('email')} className='h-full w-full outline-none bg-transparent'/>
                        
                        </div>
                        {errors.email && <p className='text-red-600'>{errors.email.message}</p>}
                    </div>
                    <div>
                        <label>Password:</label>
                        <div className='bg-slate-100 p-2 flex'>
                        <input type={showPassword ? "text" :"password"} {...register('password')} placeholder='Nhập mật khẩu...' className='h-full w-full outline-none bg-transparent'    />
                        <div className='cursor-pointer text-xl' onClick={()=>setshowPassword((preve)=>!preve)}>
                            <span>
                                {showPassword ? (
                                    <FaEyeSlash/>

                                ):(
                                    <FaEye/>
                                )}
                                </span>
                        </div>
                        
                        </div>
                        {errors.password && <p className='text-red-600'>{errors.password.message}</p>}

                    </div>
                    <Link className='block w-fit hover:underline hover:text-red-300 ml-auto' to={'/forgot-password'}>Quên mật khẩu?</Link>
                    <button type='submit' className='bg-red-600 px-5 py-2 w-full max-w-[150px] rounded-full text-white hover:scale-105 mx-auto block mt-6'>Đăng nhập</button>
                  </form>
                  <p className='my-4'>Không có tài khoản ? <Link className='text-red-600 hover:underline hover:text-red-300' to={'/sign-up'}>Tạo tài khoản</Link></p>
              </div>
       </div>
   </section>
   </>
  )
}

export default Login