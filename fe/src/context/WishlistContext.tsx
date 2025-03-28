import React, { createContext, useContext, useEffect, useState } from "react";

export interface Detail {
  id: string;
  color: string;
  default_price: number;
  discount_price: number;
  description: string;
  image: string;
  quantity: number;
  size: number;
}

export interface Product {
  id: number;
  name: string;
  image: string;
  brand: string;
  price: string;
  description: string;
  details: Detail[]; // Biến thể sản phẩm
  selectedDetail?: Detail; // Biến thể đã chọn
}

interface WishlistContextType {
  wishlist: Product[];
  addToWishlist: (product: Product) => void;
  removeFromWishlist: (id: number) => void;
  updateSelectedDetail: (id: number, detail: Detail) => void;
}

const WishlistContext = createContext<WishlistContextType | undefined>(undefined);

export const WishlistProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [wishlist, setWishlist] = useState<Product[]>(() => {
    const savedWishlist = localStorage.getItem("wishlist");
    return savedWishlist ? JSON.parse(savedWishlist) : [];
  });

  useEffect(() => {
    localStorage.setItem("wishlist", JSON.stringify(wishlist));
  }, [wishlist]);

  // Thêm sản phẩm vào Wishlist, không chọn biến thể ngay
  const addToWishlist = (product: Product) => {
    setWishlist((prev) => {
      if (prev.some((item) => item.id === product.id)) return prev;
      return [...prev, { ...product, selectedDetail: undefined }];
    });
  };

  // Xóa sản phẩm khỏi Wishlist
  const removeFromWishlist = (id: number) => {
    setWishlist((prev) => prev.filter((item) => item.id !== id));
  };

  // Cập nhật biến thể đã chọn cho sản phẩm trong Wishlist
  const updateSelectedDetail = (id: number, detail: Detail) => {
    setWishlist((prev) =>
      prev.map((item) => (item.id === id ? { ...item, selectedDetail: detail } : item))
    );
  };

  return (
    <WishlistContext.Provider value={{ wishlist, addToWishlist, removeFromWishlist, updateSelectedDetail }}>
      {children}
    </WishlistContext.Provider>
  );
};

export const useWishlist = () => {
  const context = useContext(WishlistContext);
  if (!context) throw new Error("useWishlist must be used within a WishlistProvider");
  return context;
};
