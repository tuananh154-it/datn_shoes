import { api } from "../config/axios";

export type Banner={
    id:number;
    image_url:string;
    link:string;
}
export const getBanners=()=>{
    return api.get("/banners")
}
export const getOneBanner=(id:number)=>{
    return api.get("/banners/"+id)
}