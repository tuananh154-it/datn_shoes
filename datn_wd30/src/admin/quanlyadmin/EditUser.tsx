
import { TiDelete } from "react-icons/ti";
const EditUser = ({onClose}:any) => {
  
  return (
    <div className='fixed top-0 right-0 left-0 bottom-0 w-full h-full z-10 flex justify-between items-center my-4 bg-slate-100 bg-opacity-20 '>
    <div className='w-full mx-auto bg-white p-4 shadow-md max-w-xs'>

        <button className='block ml-auto text-2xl' onClick={onClose}><TiDelete/></button>
        <h1 className='pb-4 text-lg font-medium'>Edit User Role</h1> 
        <p>Name: Nguyễn Văn a</p>
        <p>Email: abcgmail.com</p>  
       <div className='flex justify-between my-4'>
        <p>Role:</p>
        <select className='border px-4 py-1'>   
        <option>Admin</option>
        <option>Quản trị viên</option>
       </select>
       </div>
       <button className='w-fit border mx-auto block py-1 px-3 rounded-full bg-red-600 text-white hover:bg-red-300'>Edit</button>
    </div>
    </div>
  )
}

export default EditUser