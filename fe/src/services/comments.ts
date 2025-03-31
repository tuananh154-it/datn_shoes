import { api } from "../config/axios";

export type Comment={
    id:number;
    user_id:number;
    user_name:string,
    // product_id:number;
    comment:string;
    rating:number;
    created_at: string
}
export const getComments=()=>{
    return api.get("/comments")
}
export const getCommentsByProductId = (productId: number) => {
    return api.get<Comment[]>(`/comments/product/${productId}`);
};