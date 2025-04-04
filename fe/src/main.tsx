import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import './index.css'
import App from './App.tsx'
import { BrowserRouter } from 'react-router-dom'

import { Provider } from 'react-redux'
import { store } from './store/store'
import { CartProvider } from './context/CartContext.tsx'
import { WishlistProvider } from './context/WishlistContext.tsx'


createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <Provider store={store}>
    <BrowserRouter>
    <CartProvider> {/* Bọc toàn bộ ứng dụng */}
      <WishlistProvider>
      <App />
      </WishlistProvider>
    </CartProvider>
    </BrowserRouter>
    </Provider>
   
  </StrictMode>,
)
