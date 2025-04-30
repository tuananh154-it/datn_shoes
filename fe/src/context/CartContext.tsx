import { createContext, useContext, useEffect, useState } from "react";
import { fetchCart, addToCart, updateCartItem, removeCartItem, CartItem } from "../services/cart";
import toast from "react-hot-toast";

interface CartContextType {
    cart: CartItem[];
    selectedItems: number[];
    totalPrice: number;
    totalItems: number;
    fetchCartData: () => void;
    addToCart: (productDetailId: number, quantity: number) => void;
    updateCartItem: (cartItemId: number, quantity: number) => void;
    removeCartItem: (cartItemId: number) => void;
    toggleSelectItem: (cartItemId: number) => void;
    selectAllItems: (isChecked: boolean) => void;
}

const CartContext = createContext<CartContextType | undefined>(undefined);

export const CartProvider = ({ children }: { children: React.ReactNode }) => {
    const [cart, setCart] = useState<CartItem[]>([]);
    const [selectedItems, setSelectedItems] = useState<number[]>([]);
    const [totalItems, setTotalItems] = useState(0);

    const totalPrice = cart
        .filter((item) => selectedItems.includes(item.id_cart_item))
        .reduce((total, item) => total + item.quantity * item.discount_price, 0);

    const fetchCartData = async () => {
        try {
            const token = localStorage.getItem("token");
            if (!token) {
                console.warn("Không tìm thấy token, không thể lấy giỏ hàng.");
                return;
            }

            const data = await fetchCart();
            setCart(data);
        } catch (error) {
            console.error("Lỗi khi lấy giỏ hàng:", error);
        }
    };

    useEffect(() => {
        setTotalItems(cart.length);
    }, [cart]);

    const handleAddToCart = async (productDetailId: number, quantity: number) => {
        try {
            // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
            const existingItem = cart.find((item) => item.product_detail_id === productDetailId);

            if (existingItem) {
                const totalQuantity = existingItem.quantity + quantity;
                if (totalQuantity > existingItem.stock) {
                    toast.error("Sản phẩm của bạn đã tồn tại trong giỏ hàng và số lượng tồn kho của sản phẩm không đủ!");
                    return;
                }
                await updateCartItem(existingItem.id_cart_item, totalQuantity);
            } else {
                // Nếu sản phẩm chưa có trong giỏ hàng, gọi fetchCartData để cập nhật dữ liệu
                await addToCart(productDetailId, quantity);
                await fetchCartData(); // Cập nhật giỏ hàng để lấy stock
                const newItem = cart.find((item) => item.product_detail_id === productDetailId);
                if (newItem && quantity > newItem.stock) {
                    toast.error("Số lượng tồn kho của sản phẩm không đủ!");
                    return;
                }
            }

            await fetchCartData();
            toast.success("Thêm vào giỏ hàng thành công!");
        } catch (error: any) {
            console.error("Lỗi khi thêm sản phẩm vào giỏ hàng:", error);
            const errorMessage = error.response?.data?.message || "Đã xảy ra lỗi khi thêm sản phẩm vào giỏ hàng!";
            toast.error(errorMessage);
        }
    };

    const handleUpdateCartItem = async (cartItemId: number, quantity: number) => {
        try {
            const item = cart.find((item) => item.id_cart_item === cartItemId);
            if (!item) {
                console.error("Không tìm thấy sản phẩm trong giỏ hàng!");
                return;
            }

            if (quantity > item.stock) {
                toast.error("Số lượng trong kho không đủ!");
                return;
            }

            if (quantity >= 1) {
                await updateCartItem(cartItemId, quantity);
                await fetchCartData();
            }
        } catch (error: any) {
            console.error("Lỗi khi cập nhật số lượng:", error);
            const errorMessage = error.response?.data?.message || "Đã xảy ra lỗi khi cập nhật giỏ hàng!";
            toast.error(errorMessage);
        }
    };

    const handleRemoveCartItem = async (cartItemId: number) => {
        try {
            await removeCartItem(cartItemId);
            await fetchCartData();
        } catch (error) {
            console.error("Lỗi khi xóa sản phẩm:", error);
            toast.error("Đã xảy ra lỗi khi xóa sản phẩm khỏi giỏ hàng!");
        }
    };

    useEffect(() => {
        fetchCartData();
    }, []);

    const toggleSelectItem = (cartItemId: number) => {
        setSelectedItems((prev) =>
            prev.includes(cartItemId)
                ? prev.filter((id) => id !== cartItemId)
                : [...prev, cartItemId]
        );
    };

    const selectAllItems = (isChecked: boolean) => {
        setSelectedItems(isChecked ? cart.map((item) => item.id_cart_item) : []);
    };

    return (
        <CartContext.Provider
            value={{
                cart,
                selectedItems,
                totalPrice,
                totalItems,
                fetchCartData,
                addToCart: handleAddToCart,
                updateCartItem: handleUpdateCartItem,
                removeCartItem: handleRemoveCartItem,
                toggleSelectItem,
                selectAllItems,
            }}
        >
            {children}
        </CartContext.Provider>
    );
};

export const useCart = () => {
    const context = useContext(CartContext);
    if (!context) {
        throw new Error("useCart phải được sử dụng bên trong CartProvider");
    }
    return context;
};