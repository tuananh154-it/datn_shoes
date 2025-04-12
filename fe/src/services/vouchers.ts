import { api } from "../config/axios";

export type Voucher={
    id:number;
    name:string;
    quantity:number;
    discount_percent:number;
    expiration_date:string;
    min_purchase_amount:number;
    max_discount_amount:number;
    status:string;
}
export const getAllVoucher=()=>{
    return api.get("/vouchers")
}
export const getVoucherById=(id:number)=>{
    return api.get("/vouchers/"+id)
}