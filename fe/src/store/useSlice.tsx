import { createSlice } from "@reduxjs/toolkit";

export interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  created_at: string;
  updated_at: string;
}

// ✅ Xử lý dữ liệu từ localStorage an toàn
const storedUser = localStorage.getItem("user");
let parsedUser: User | null = null;

try {
  parsedUser = storedUser ? JSON.parse(storedUser) : null;
} catch (error) {
  console.error("Lỗi khi parse user từ localStorage:", error);
  localStorage.removeItem("user");
}

// ✅ Khởi tạo state từ localStorage
const initialState: { user: User | null } = {
  user: parsedUser,
};

// ✅ Tạo slice cho user
export const userSlice = createSlice({
  name: "user",
  initialState,
  reducers: {
    setUserDetail: (state, action) => {
      state.user = action.payload;
      localStorage.setItem("user", JSON.stringify(action.payload)); // Lưu vào localStorage
    },
    logout: (state) => {
        state.user = null;
        localStorage.removeItem("user"); // Xóa user
        localStorage.removeItem("token"); // ✅ Xóa token khi logout
      },
  },
});

// Export actions
export const { setUserDetail, logout } = userSlice.actions;

// Export reducer
export default userSlice.reducer;
