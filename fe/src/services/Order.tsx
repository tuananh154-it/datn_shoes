import { AxiosError } from "axios";
import { api } from "../config/axios"
// export const getCheckout = ()=>{
//       return api.get("/checkout/init")
// }
export const getCheckOut = (
  userId: number,
  selectedItems: number[],
  voucherCode?: string
) => {
  return api.post("/checkout/preview", {
      item_ids: selectedItems,
      user_id: userId,
      voucher: voucherCode ?? null, // Đổi từ 'code' thành 'voucher'
  });
};
export const getOrder = (orderData: any)=>{
      return api.post('/orders',orderData)
}
// export const Momopayment = async (data: { orderId: string; redirectUrl: string }) => {
//       return api.post("/momo-payment", data);
//     };
export const Momopayment = async (data: {
  orderId: string;
  redirectUrl: string;
  amount: number;
  username: string;
  address: string;
  email: string;
  phone_number: string;
  selected_items: number[];
  voucher_code?: string | null;
}) => {
  try {
    console.log("📦 Gửi dữ liệu MoMo: ", data);
    const response = await api.post("/momo-payment", data);
    return response;
  } catch (error: unknown) {
    if (error instanceof AxiosError) {
      console.error("Lỗi từ MoMo API:", error.response?.data);
    } else {
      console.error("Lỗi kết nối với MoMo:", (error as Error).message);
    }
    throw error;
  }
};