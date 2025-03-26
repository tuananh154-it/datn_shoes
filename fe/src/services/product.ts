import { api } from "../config/axios"

export const getAllProduct = ()  =>{
    return api.get('/products') 
}
export const getProductDetail = (id:string)  =>{
    return api.get(`/products/${id}`) 
}