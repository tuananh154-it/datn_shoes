import { api } from "../config/axios";

export type Contacts={
    id:number;
    name:string;
    email:string;
    phone_number:string
}
export const getContact=()=>{
    return api.get("/contacts")
}
