import { createContext, useContext, useEffect, useState } from "react";
import { fetchCart, addToCart, updateCartItem, removeCartItem, CartItem } from "../services/cart";

interface CartContextType {
    cart: CartItem[];
    selectedItems: number[]; // Lưu ID sản phẩm được chọn
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
    const [totalItems, setTotalItems] = useState(0); // Biến mới để hiển thị số lượng trên icon

    // tính tồng số sản phẩm trong cart

    // Tính tổng tiền chỉ của sản phẩm đã chọn
    const totalPrice = cart
        .filter((item) => selectedItems.includes(item.id_cart_item)) // Chỉ tính sản phẩm được chọn
        .reduce((total, item) => total + item.quantity * item.discount_price, 0);

    // Lấy dữ liệu giỏ hàng từ API
    // const fetchCartData = async () => {
    //     try {
    //         const data = await fetchCart();
    //         setCart(data);

    //         //   // Tính tổng số lượng sản phẩm trong giỏ hàng
    //         //   const total = data.reduce((sum: number, item: CartItem) => sum + item.quantity, 0);
    //         //   setTotalItems(total);
    //     } catch (error) {
    //         console.error("Lỗi khi lấy giỏ hàng:", error);
    //     }
    // };
    const fetchCartData = async () => {
        try {
            const token = localStorage.getItem("token");
            if (!token) {
                console.warn("Không tìm thấy token, không thể lấy giỏ hàng.");
                return;
            }
    
            const data = await fetchCart();
            setCart(data);
    
            // ✅ Tính tổng số lượng sản phẩm trong giỏ hàng
            // const total = data.reduce((sum: number, item: CartItem) => sum + item.quantity, 0);
            // setTotalItems(total);
        } catch (error) {
            console.error("Lỗi khi lấy giỏ hàng:", error);
        }
    };
    // Tự động tính tổng số lượng sản phẩm khi `cart` thay đổi
    // useEffect(() => {
    //     const total = cart.reduce((sum, item) => sum + item.quantity, 0);
    //     setTotalItems(total);
    // }, [cart]);
    useEffect(() => {
    setTotalItems(cart.length); // Chỉ tính số sản phẩm khác nhau
}, [cart]);

    // Thêm sản phẩm vào giỏ hàng
    const handleAddToCart = async (productDetailId: number, quantity: number) => {
        await addToCart(productDetailId, quantity);
        fetchCartData();
    };

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    const handleUpdateCartItem = async (cartItemId: number, quantity: number) => {
        await updateCartItem(cartItemId, quantity);
        fetchCartData();
    };

    // Xóa sản phẩm khỏi giỏ hàng
    const handleRemoveCartItem = async (cartItemId: number) => {
        await removeCartItem(cartItemId);
        fetchCartData();
    };

    useEffect(() => {
        fetchCartData();
    }, []);


    // Toggle chọn/bỏ chọn một sản phẩm
    const toggleSelectItem = (cartItemId: number) => {
        setSelectedItems((prev) =>
            prev.includes(cartItemId)
                ? prev.filter((id) => id !== cartItemId) // Bỏ chọn
                : [...prev, cartItemId] // Chọn
        );
    };

    // Chọn/Bỏ chọn tất cả sản phẩm
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

// Hook tùy chỉnh để sử dụng CartContext
export const useCart = () => {
    const context = useContext(CartContext);
    if (!context) {
        throw new Error("useCart phải được sử dụng bên trong CartProvider");
    }
    return context;
};
