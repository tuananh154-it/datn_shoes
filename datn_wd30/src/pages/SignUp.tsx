import { useState } from 'react'
import { FaEye } from "react-icons/fa";
import { FaEyeSlash } from "react-icons/fa";
import { zodResolver } from "@hookform/resolvers/zod";
import iconUser from '../assets/cHJpdmF0ZS9sci9pbWFnZXMvd2Vic2l0ZS8yMDIzLTAxL3JtNjA5LXNvbGlkaWNvbi13LTAwMi1wLnBuZw.jpg'
import { useForm } from 'react-hook-form';
import { signin } from '../validate/validateForm';
const SignUp = () => {
    const [showPassword,setshowPassword]= useState(false);
    const {register, handleSubmit,formState:{errors}}= useForm({resolver:zodResolver(signin)})
    const onSubmit = (data:any)=>{
        console.log(data)
    }
  return (
   <>
   <section id='login'>
       <div className='mx-auto container p-4'>
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
                    <div className='grid'>
                        <label>Họ tên:</label>
                        <div className='bg-slate-100 p-2'>
                        <input type='text' placeholder='Nhập họ tên người dùng...' {...register('name')} className='h-full w-full outline-none bg-transparent'/>
                        
                        </div>
                        {errors.name && <p className='text-red-600'>{errors.name.message}</p>}
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
                    <button className='bg-red-600 px-5 py-2 w-full max-w-[150px] rounded-full text-white hover:scale-105 mx-auto block mt-6'>Đăng ký</button>
                  </form>
              </div>
       </div>
   </section>
   </>
  )
}

export default SignUp