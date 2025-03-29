import { api } from "../config/axios"
export const getCheckout = ()=>{
      return api.get("/checkout/init")
}
export const getOrder = (orderData: any)=>{
      return api.post('/orders/place',orderData)
}