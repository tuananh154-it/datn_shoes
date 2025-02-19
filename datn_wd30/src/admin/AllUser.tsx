import  { useState } from 'react'
import { MdModeEditOutline } from "react-icons/md";
import EditUser from './EditUser';
const AllUser = () => {
  const [openUpdateUser,setopenUpdateUser]= useState(false);
  return (
   <>
  <div className='bg-white pb-4'>
  <table className='w-full userTable'>
    <thead>
      <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Created Date</th>
        <th>Acction</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>Nguyễn văn a</td>
        <td>abc@gmail.com</td>
        <td>Quản trị viên</td>
        <td>20/1/2025</td>
        <td><button className='bg-green-300 p-2 rounded-full hover:bg-green-500 hover:text-white' onClick={()=> setopenUpdateUser(true)}><MdModeEditOutline/></button></td>
      </tr>
      <tr>
        <td>2</td>
        <td>Nguyễn văn b</td>
        <td>abc@gmail.com</td>
        <td>Quản trị viên</td>
        <td>20/1/2025</td>
        <td><button className='p-2 rounded-full bg-green-300 hover:bg-green-500 hover:text-white' onClick={()=> setopenUpdateUser(true)}><MdModeEditOutline/></button></td>
      </tr>
      <tr>
        <td>3</td>
        <td>Nguyễn văn c</td>
        <td>abc@gmail.com</td>
        <td>Quản trị viên</td>
        <td>20/1/2025</td>
        <td><button className='p-2 rounded-full bg-green-300 hover:bg-green-500 hover:text-white'><MdModeEditOutline/></button></td>
      </tr>
    </tbody>
   </table>
  </div>
  {
    openUpdateUser && (
      <EditUser onClose={()=>setopenUpdateUser(false)}/>
    )
  }
   </>
  )
}

export default AllUser