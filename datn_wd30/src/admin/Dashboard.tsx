import React from "react";
import { PieChart, Pie, Cell, ResponsiveContainer, BarChart, Bar, XAxis, YAxis, Tooltip, Legend } from "recharts";
import { FaBox, FaStar, FaShoppingCart, FaUsers } from "react-icons/fa";

const dataBar = [
  { name: "Th 1", revenue: 0 },
  { name: "Th 2", revenue: 0 },
  { name: "Th 3", revenue: 0 },
  { name: "Th 4", revenue: 0 },
  { name: "Th 5", revenue: 0 },
  { name: "Th 6", revenue: 0 },
  { name: "Th 7", revenue: 0 },
  { name: "Th 8", revenue: 0 },
  { name: "Th 9", revenue: 0 },
  { name: "Th 10", revenue: 0 },
  { name: "Th 11", revenue: 0 },
  { name: "Th 12", revenue: 1600000 },
];

const dataPie = [
  { name: "Chờ xác nhận", value: 10, color: "#FF9999" },
  { name: "Chờ lấy hàng", value: 20, color: "#ADD8E6" },
  { name: "Đang giao hàng", value: 15, color: "#FFD700" },
  { name: "Đã giao hàng", value: 30, color: "#87CEFA" },
  { name: "Hủy đơn", value: 5, color: "#FF3333" },
];

const Dashboard: React.FC = () => {
  return (
    <div className="w-full h-full ml-4">
      <h1 className="text-2xl font-bold mb-4 mt-4">Trang chủ</h1>
      {/* Cards */}
      <div className="grid grid-cols-4 gap-4">
        <StatCard title="TỔNG SỐ ĐƠN HÀNG" value="0" color="bg-blue-500" icon={<FaShoppingCart />} />
        <StatCard title="ĐÁNH GIÁ" value="0" color="bg-yellow-500" icon={<FaStar />} />
        <StatCard title="SẢN PHẨM" value="0" color="bg-green-500" icon={<FaBox />} />
        <StatCard title="THÀNH VIÊN" value="0" color="bg-red-500" icon={<FaUsers />} />
      </div>

      {/* Charts */}
      <div className="grid grid-cols-2 gap-4 mt-6">
        {/* Bar Chart */}
        <div className="bg-white p-4 shadow-md rounded-lg">
          <h2 className="text-lg font-semibold">Biểu đồ doanh thu theo từng tháng</h2>
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={dataBar}>
              <XAxis dataKey="name" />
              <YAxis />
              <Tooltip />
              <Legend />
              <Bar dataKey="revenue" fill="#8884d8" />
            </BarChart>
          </ResponsiveContainer>
        </div>

        {/* Pie Chart */}
        <div className="bg-white p-4 shadow-md rounded-lg">
          <h2 className="text-lg font-semibold">Thống kê trạng thái đơn hàng</h2>
          <ResponsiveContainer width="100%" height={300}>
            <PieChart>
              <Pie data={dataPie} dataKey="value" nameKey="name" cx="50%" cy="50%" outerRadius={100} label>
                {dataPie.map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={entry.color} />
                ))}
              </Pie>
              <Tooltip />
            </PieChart>
          </ResponsiveContainer>
        </div>
      </div>
    </div>
  );
};

const StatCard: React.FC<{ title: string; value: string; color: string; icon: React.ReactNode }> = ({ title, value, color, icon }) => (
  <div className={`p-4 text-white rounded-lg shadow-md ${color}`}>
    <div className="flex justify-between items-center">
      <div>
        <h3 className="text-lg font-semibold">{title}</h3>
        <p className="text-2xl font-bold">{value}</p>
      </div>
      <div className="text-3xl">{icon}</div>
    </div>
  </div>
);

export default Dashboard;