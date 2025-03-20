import { api } from "../config/axios";

export type Article={
    id:number;
    name:string;
    title:string;
    content:string;
    image:string;
    created_at:string;
}
export const getArticles=()=>{
    return api.get("/articles")
}
export const getOneArticles=(id:number)=>{
    return api.get("/articles/"+id)
}