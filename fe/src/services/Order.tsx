import { api } from "../config/axios"
export const getCheckout = ()=>{
      return api.get("/checkout/init")
}
export const getCheckOut = (userId: number, selectedItems: number[]) => {
      return api.post("/checkout/preview", {
        item_ids: selectedItems, // Gửi đúng tên trường theo backend yêu cầu
        user_id: userId, // Thêm user_id cho backend nhận diện người dùng
      });
    };
export const getOrder = (orderData: any)=>{
      return api.post('/orders',orderData)
}
export const Momopayment = async (data: { amount: number; orderId: string; redirectUrl: string }) => {
      return api.post("/momo-payment", data);
    };
