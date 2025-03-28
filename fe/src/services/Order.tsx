import { api } from "../config/axios"
import { OrderData } from "../Pages/CheckOut"

export const getOrder = (orderData: OrderData)=>{
      return api.post('/payments',orderData)   
}