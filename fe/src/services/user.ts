import { api } from "../config/axios";

export type Users={
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    role: string;
    gender: string;
    date_of_birth: string | null;
    address: string;
    phone_number: string;
    created_at: string;
    updated_at: string;
}

export const getUser=()=>{
    return api.get("/user")
}
export const sendForgotPasswordEmail = (email: string) => {
    return api.post("/password/forgot", { email });
};

// Hàm gọi API reset mật khẩu với token, email, mật khẩu và xác nhận mật khẩu
export const resetPassword = (token: string, email: string, password: string, password_confirmation: string) => {
    return api.post('/password/reset', {
        email,
        password,
        password_confirmation,
        token
    });
}