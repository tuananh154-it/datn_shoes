import { AxiosError } from "axios";
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
// export const Momopayment = async (data: { orderId: string; redirectUrl: string }) => {
//       return api.post("/momo-payment", data);
//     };
export const Momopayment = async (data: { orderId: string; redirectUrl: string }) => {
  try {
    console.log("📦 Gửi dữ liệu MoMo: ", data);
    const response = await api.post("/momo-payment", data);
    return response;
  } catch (error: unknown) {
    if (error instanceof AxiosError) {
      // Now TypeScript knows that 'error' is of type AxiosError
      console.error("Lỗi từ MoMo API:", error.response?.data);
    } else {
      // For non-Axios errors (e.g., network errors), treat it as a generic Error
      console.error("Lỗi kết nối với MoMo:", (error as Error).message);
    }
    throw error;
  }
};

