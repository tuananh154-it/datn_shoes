import { api } from "../config/axios"

export const getAllCategory = ()=>{
    return api.get('/categories')
}
export interface Category{
    id:number,
    name:string
}