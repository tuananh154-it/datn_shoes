// import { Outlet } from "react-router-dom"
// import Header from "./Header"
// import Footer from "./Footer"
// import { useEffect, useState } from "react"
// import { getAllOrders } from "../services/Orders"

// const Layout = () => {
//   const [order,setOrder]= useState([])
//   useEffect(()=>{
//     getAllOrders().then(({data})=>{
//       setOrder(data)
//     })
//   })
//   return (
//     <div>
//         <Header/>
//         <Outlet/>
//         <Footer/>
//     </div>
//   )
// }

// export default Layout

import { Outlet } from "react-router-dom"
import Header from "./Header"
import Footer from "./Footer"


const Layout = () => {

  return (
    <div>
      <Header />
      <Outlet />
      <Footer />
    </div>
  )
}

export default Layout;

// import { useState } from "react";
// import { Input } from "shadcn";
// import { Button } from "shadcn";
// import { Card, CardContent } from "shadcn";
// import { Send, MessageCircle } from "lucide-react";
// import { Outlet } from "react-router-dom";
// import Header from "./Header";
// import Footer from "./Footer";

// const Chatbot = () => {
//   const [messages, setMessages] = useState([
//     { text: "Xin chào! Tôi có thể giúp gì cho bạn hôm nay?", sender: "bot" }
//   ]);
//   const [input, setInput] = useState("");
//   const [isOpen, setIsOpen] = useState(false);

//   const handleSendMessage = async () => {
//     if (!input.trim()) return;
    
//     const newMessages = [...messages, { text: input, sender: "user" }];
//     setMessages(newMessages);
//     setInput("");

//     // Giả lập phản hồi từ AI (cần thay bằng API thực tế)
//     setTimeout(() => {
//       setMessages([...newMessages, { text: "Để tôi tìm sản phẩm phù hợp cho bạn...", sender: "bot" }]);
//     }, 1000);
//   };

//   return (
//     <div className="fixed bottom-5 left-5">
//       {!isOpen ? (
//         <Button className="rounded-full p-3 shadow-lg" onClick={() => setIsOpen(true)}>
//           <MessageCircle size={24} />
//         </Button>
//       ) : (
//         <Card className="w-96 shadow-lg rounded-2xl overflow-hidden">
//           <CardContent className="h-80 overflow-y-auto p-4 space-y-2">
//             {messages.map((msg, index) => (
//               <div key={index} className={`text-sm p-2 rounded-lg ${msg.sender === "bot" ? "bg-gray-200" : "bg-blue-500 text-white ml-auto"}`}> 
//                 {msg.text}
//               </div>
//             ))}
//           </CardContent>
//           <div className="p-2 flex gap-2 border-t">
//             <Input value={input} onChange={(e) => setInput(e.target.value)} placeholder="Nhập câu hỏi của bạn..." />
//             <Button onClick={handleSendMessage}><Send size={16} /></Button>
//           </div>
//           <Button className="w-full border-t py-2 text-gray-500" onClick={() => setIsOpen(false)}>Đóng</Button>
//         </Card>
//       )}
//     </div>
//   );
// };

// const Layout = () => {
//   return (
//     <div>
//       <Header />
//       <Outlet />
//       <Footer />
//       <Chatbot />
//     </div>
//   );
// };

// export default Layout;