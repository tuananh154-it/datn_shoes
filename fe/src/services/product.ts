import { api } from "../config/axios"
export interface Product{
    id:string,
    name:string,
    price: string| number;
    image:string,
    description:string,
    category:string,
    brand:string,
  }
export const getAllProduct = ()  =>{
    return api.get('/products') 
}
export const getProductDetail = (id:string)  =>{
    return api.get(`/products/${id}`) 
}
export const getLatesProducts= ()=>{
    return api.get('/latest-products')
}