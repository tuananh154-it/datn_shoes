import { api } from "../config/axios";

export type Comment={
    id:number;
    customer_id:number;
    product_id:number;
    comment:string;
    rating:number;
    created_at?: string
}
export const getComments=()=>{
    return api.get("/comments")
}