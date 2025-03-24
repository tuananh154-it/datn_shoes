import { api } from "../config/axios"

export const getBrand = ()=>{
    return api.get('/brands')
}
export interface Brand{
    id:string,
    name:string
}