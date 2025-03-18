import asiox from 'axios'
const http = asiox.create({
    baseURL : '',

})
export const getAllProduct = ()  =>{
    return http.get('') 
}