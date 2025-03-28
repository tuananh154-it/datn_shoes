import { api } from "../config/axios"

export const getOrder = ()=>{
      return api.post('/orders')   
}