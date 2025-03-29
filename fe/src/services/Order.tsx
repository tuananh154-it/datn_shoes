import { api } from "../config/axios"
export const getOrder = (id:string)=>{
      return api.get(`/payments/${id}`)  
}