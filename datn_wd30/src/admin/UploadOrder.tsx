import { MdClose } from "react-icons/md";
const UploadOrder = ({ onClose }:any) => {
  return (
    <div className="fixed bg-slate-200 top-0 right-0 bottom-0 left-0 bg-opacity-35 flex justify-center items-center overflow-hidden">
      <div className="bg-white p-4 rounded w-full max-w-[70%] h-full max-h[80%] overflow-y-scroll">
        <div className="flex">
          <div>
            <h1 className="font-bold text-2xl">Thêm đơn hàng mới</h1>
          </div>
          <button className="block ml-auto text-2xl" onClick={onClose}>
            <MdClose />
          </button>
        </div>
        <div className="p-4 flex w-full h-ful max-h-[70%] ">
          <aside className=" w-[50%]">
            <h2 className="text-xl font-bold">Thông tin đơn hàng</h2>
            <form className="grid p-4 gap-2">
              <label>Người dùng</label>
              <select className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota">
                <option>Chọn người dùng</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
              </select>
              <label>Địa chỉ</label>
              <input
                placeholder="Nhập địa chỉ..."
                className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              />

              <label>Số điện thoại</label>
              <input
                placeholder="Nhập số điện thoại..."
                className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              />
              <label>Trạng thái</label>
              <input
                placeholder="Trạng thái..."
                className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              />

              <label>Tổng tiền</label>
              <input
                placeholder="Tổng tiền..."
                className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              />
            </form>
          </aside>
          <main className="w-[50%]">
            <form className="grid p-4 gap-2">
              <label>Trạng thái thanh toán</label>
              <input
                placeholder="Trạng thái thanh toán..."
                className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              />

              <label>Voucher</label>
              <select className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota">
                <option>Chọn Voucher</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
              </select>

              <label>Nhân viên</label>
              <select className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota">
                <option>Chọn nhân viên</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
              </select>

              <label>Hình thức thanh toán</label>
              <select className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota">
                <option>Chọn hình thức thanh toán</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
              </select>

              <label>Phương thức vận chuyển</label>
              <select className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota">
                <option>Chọn hình thức vận chuyển</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
              </select>
            </form>
          </main>
        </div>
        <div className="mt-1">
          <h2>Ghi chú</h2>
          <div className="">
            <textarea
              className="border top-0 w-full h-[100px] focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              placeholder="Ghi chú..."
            />
          </div>
        </div>
        <button className="border-2 mt-7 bg-blue-500 px-3 py-2 block ml-auto text-white rounded-full hover:bg-blue-400">
          Thêm mới
        </button>
      </div>
    </div>
  );
};

export default UploadOrder;
