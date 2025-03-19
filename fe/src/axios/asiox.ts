import axios from 'axios'
const http = axios.create({
    baseURL : 'http://127.0.0.1:8000/api',

})
export const getAllProduct = ()  =>{
    return http.get('/products') 
}
export const getProductDetail = (id:string)  =>{
    return http.get(`/products/${id}`) 
}