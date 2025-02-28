import { useState } from 'react'
import { FaEye } from "react-icons/fa";
import { FaEyeSlash } from "react-icons/fa";
import { zodResolver } from "@hookform/resolvers/zod";
import iconUser from '../assets/cHJpdmF0ZS9sci9pbWFnZXMvd2Vic2l0ZS8yMDIzLTAxL3JtNjA5LXNvbGlkaWNvbi13LTAwMi1wLnBuZw.jpg'
import { useForm } from 'react-hook-form';
import { signup } from '../validate/validateForm';
import { Link } from 'react-router-dom';
import { SignInUser } from '../types/User';
import { SignUpForm } from '../axios';
import toast from 'react-hot-toast';
const SignUp = () => {
    const [showPassword,setshowPassword]= useState(false);
    // const nav = useNavigate();
    const [showConfirmPassword,setshowConfirmPassword]= useState(false);
    const {register, handleSubmit,formState:{errors}}= useForm<SignInUser>({resolver:zodResolver(signup)})
    const [data,setData]= useState({
        email : "",
        password : "",
        name : "",
        confirmPassword : "",
        profilePic : "",
    })
    // const onSubmit = (data:SignInUser) =>{
    //     SignUpForm(data).then(()=>{
    //         toast.success("Đã đăng kí thành công")
    //         nav("/login")
    //     })
    //     .catch((e)=>{
    //         toast.error("Error"+e.message)
    //     })
    // }
    const onSubmit = (data:SignInUser)=>{
        console.log(data)
    }
    //file
    const imageProfile= async(image:any)=>{
          const render = new FileReader();
          render.readAsDataURL(image);
          const data = await new Promise((resolve,reject)=>{
            render.onload= () => resolve(render.result)
            render.onerror = errors => reject(errors)
          })

          return data
    }
    const handleImagePic = async(e:any)=>{
        const file = e.target.files[0];
        const image = await imageProfile(file);
        // console.log("image",image)
        setData((preve:any)=>{
            return {
                ...preve,
                profilePic : image
            }
        })
    }
  return (
   <>
   <section>
       <div className='mx-auto container p-4'>
              <div className='w-full p-2 bg-white max-w-md mx-auto mt-4'>
                  <div className='w-28 h-28 mt-4 mx-auto cursor-pointer overflow-hidden rounded-full'>
                   <form onSubmit={handleSubmit(onSubmit)} >
                   <label>
                   <img {...register('profilePic')} id='profilePic'  src={data.profilePic || iconUser} alt='icon user'/>
                   <input  type='file' className='hidden' onChange={handleImagePic}/>
                   </label>
                   </form>
                  </div>
                  <form action='' className='pt-6 flex flex-col gap-2 ' onSubmit={handleSubmit(onSubmit)}>
                  <div className='grid'>
                        <label>Họ tên:</label>
                        <div className='bg-slate-100 p-2'>
                        <input type='text' placeholder='Nhập họ tên người dùng...' {...register('name')} id='name' className='h-full w-full outline-none bg-transparent'/>
                        </div>
                        {errors.name && <p className='text-red-600'>{errors.name.message}</p>}
                    </div>
                    <div className='grname'>
                        <label>Email:</label>
                        <div className='bg-slate-100 p-2'>
                        <input type='email' placeholder='Nhập email...' {...register('email')} id='email' className='h-full w-full outline-none bg-transparent'/>
                        
                        </div>
                        {errors.email && <p className='text-red-600'>{errors.email.message}</p>}
                    </div>
                    
                    <div>
                        <label>Password:</label>
                        <div className='bg-slate-100 p-2 flex'>
                        <input type={showPassword ? "text" :"password"} {...register('password')} id='password' placeholder='Nhập mật khẩu...' className='h-full w-full outline-none bg-transparent'    />
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
                    <div>
                        <label>Confirm Password:</label>
                        <div className='bg-slate-100 p-2 flex'>
                        <input type={showConfirmPassword ? "text" :"password"} {...register('confirmPassword')} id='confirmPassword' placeholder='Nhập lại mật khẩu...' className='h-full w-full outline-none bg-transparent'    />
                        <div className='cursor-pointer text-xl' onClick={()=>setshowConfirmPassword((preve)=>!preve)}>
                            <span>
                                {showConfirmPassword ? (
                                    <FaEyeSlash/>

                                ):(
                                    <FaEye/>
                                )}
                                </span>
                        </div>
                        
                        </div>
                        {errors.confirmPassword && <p className='text-red-600'>{errors.confirmPassword.message}</p>}

                    </div>
                    <button type='submit' className='bg-red-600 px-5 py-2 w-full max-w-[150px] rounded-full text-white hover:scale-105 mx-auto block mt-6'>Đăng ký</button>
                  </form>
                  <p className='my-4'>Bạn đã có tài khoản ? <Link className='text-red-600 hover:underline hover:text-red-300' to={'/login'}>Đăng nhập</Link></p>
              </div>
       </div>
   </section>
   </>
  )
}

export default SignUp
