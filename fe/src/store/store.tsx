import { configureStore } from '@reduxjs/toolkit'
import userReducer from "./useSlice"
export const store = configureStore({
  reducer: {
    user: userReducer
  },
})
export type RootState = ReturnType<typeof store.getState>;