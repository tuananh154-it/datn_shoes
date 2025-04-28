import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import { getUser } from "../services/user"; // Import getUser từ file service
import toast from "react-hot-toast";

export interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  created_at: string;
  updated_at: string;
}

interface UserState {
  user: User | null;
  loading: boolean;
  error: string | null;
}

const storedUser = localStorage.getItem("user");
let parsedUser: User | null = null;

try {
  parsedUser = storedUser ? JSON.parse(storedUser) : null;
} catch (error) {
  console.error("Lỗi khi parse user từ localStorage:", error);
  localStorage.removeItem("user");
}

const initialState: UserState = {
  user: parsedUser,
  loading: false,
  error: null,
};

// Sử dụng getUser từ API service
export const refreshUser = createAsyncThunk(
  "user/refreshUser",
  async (_, { rejectWithValue }) => {
    try {
      const response = await getUser(); // Gọi API bằng getUser
      return response.data; // Trả về dữ liệu user từ API
    } catch (error: any) {
      return rejectWithValue(error.response?.data?.message || "Lỗi không xác định");
    }
  }
);

export const userSlice = createSlice({
  name: "user",
  initialState,
  reducers: {
    setUserDetail: (state, action) => {
      state.user = action.payload;
      localStorage.setItem("user", JSON.stringify(action.payload));
    },
    logout: (state) => {
      state.user = null;
      localStorage.removeItem("user");
      localStorage.removeItem("token");
    },
  },
  extraReducers: (builder) => {
    builder
      .addCase(refreshUser.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(refreshUser.fulfilled, (state, action) => {
        state.loading = false;
        state.user = action.payload;
        localStorage.setItem("user", JSON.stringify(action.payload));
      })
      .addCase(refreshUser.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload as string; // Ép kiểu ở đây vẫn ổn, nhưng không cần thiết cho logic dưới
        // Kiểm tra kiểu của action.payload trước khi gọi .includes()
        if (typeof action.payload === 'string' && action.payload.includes('Không thể lấy thông tin user')) {
          state.user = null;
          localStorage.removeItem('user');
          localStorage.removeItem('token');
          toast.error("Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.");
        }
      })
  },
});

export const { setUserDetail, logout } = userSlice.actions;
export default userSlice.reducer;