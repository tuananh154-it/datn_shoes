import { api } from "../config/axios";

export interface CartItem {
    id_cart_item: number;
    product_detail_id: number;
    product_name: string;
    color: string;
    size: string;
    quantity: number;
    price: number;
    image: string;
  }
  export const fetchCart = async (): Promise<CartItem[]> => {
    try {
      const response = await api.get("/api/cart", { withCredentials: true });
      return response.data.cart || [];
    } catch (error) {
      console.error("Lỗi khi lấy giỏ hàng:", error);
      throw error;
    }
  };
  
  // API: Thêm sản phẩm vào giỏ hàng
  export const addToCart = async (productDetailId: number, quantity: number): Promise<void> => {
    try {
      await api.post(
        "/api/cart/add",
        { product_detail_id: productDetailId, quantity },
        { withCredentials: true }
      );
    } catch (error) {
      console.error("Lỗi khi thêm vào giỏ hàng:", error);
      throw error;
    }
  };
  
  // API: Cập nhật số lượng sản phẩm trong giỏ hàng
  export const updateCartItem = async (cartItemId: number, quantity: number): Promise<void> => {
    try {
      await api.put(
        `/api/cart/update/${cartItemId}`,
        { quantity },
        { withCredentials: true }
      );
    } catch (error) {
      console.error("Lỗi khi cập nhật giỏ hàng:", error);
      throw error;
    }
  };
  
  // API: Xóa sản phẩm khỏi giỏ hàng
  export const removeCartItem = async (cartItemId: number): Promise<void> => {
    try {
      await api.delete(`/api/cart/remove/${cartItemId}`, { withCredentials: true });
    } catch (error) {
      console.error("Lỗi khi xóa sản phẩm:", error);
      throw error;
    }
  };