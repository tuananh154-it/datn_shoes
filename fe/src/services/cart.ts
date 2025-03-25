import { api } from "../config/axios";

export interface CartItem {
    id_cart_item: number;
    product_detail_id: number;
    product_name: string;
    color: string;
    size: string;
    quantity: number;
    discount_price: number;
    image: string;
  }
  export const fetchCart = async (): Promise<CartItem[]> => {
    try {
      const response = await api.get("/cart",);
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
        "/cart/add",
        { product_detail_id: productDetailId, quantity },
        // { withCredentials: true }
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
        `/cart/update/${cartItemId}`,
        { quantity },
        // { withCredentials: true }
      );
    } catch (error) {
      console.error("Lỗi khi cập nhật giỏ hàng:", error);
      throw error;
    }
  };
  
  // API: Xóa sản phẩm khỏi giỏ hàng
  export const removeCartItem = async (cartItemId: number): Promise<void> => {
    try {
      await api.delete(`/cart/remove/${cartItemId}`,);
    } catch (error) {
      console.error("Lỗi khi xóa sản phẩm:", error);
      throw error;
    }
  };
  export const syncCart = async (cartItems: CartItem[]): Promise<boolean> => {
    try {
        await api.post("/cart/sync", { cart: cartItems });
        return true;
    } catch (error) {
        console.error("Lỗi khi đồng bộ giỏ hàng:", error);
        return false;
    }
};
