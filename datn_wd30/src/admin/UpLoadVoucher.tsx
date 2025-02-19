
import { MdClose } from "react-icons/md";

const UpLoadVoucher = ({ onClose}:any) => {
  return (
    <div className="fixed bg-slate-200 top-0 right-0 bottom-0 left-0 bg-opacity-35 flex justify-center items-center overflow-hidden">
      <div className="bg-white p-4 rounded w-full max-w-[40%] max-h[200px] overflow-y-scroll">
        <div className="flex">
          <div>
            <h1 className="font-bold text-2xl">Thêm mới vocher</h1>
          </div>
          <button className="block ml-auto text-2xl" onClick={onClose}>
            <MdClose />
          </button>
        </div>
        <div className="p-4 flex w-full h-ful max-h-[70%] ">
          <main className="w-full">
            <form className="grid p-4 gap-2">
              <label>Tên mã giảm giá</label>
              <input
                placeholder="Tên mã giảm giá..."
                className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              />
              <label>Số lượng</label>
              <input
                type="number"
                placeholder="Số lượng..."
                className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              />

              <label>Ngày bắt đầu</label>
              <input
              type="date"
                placeholder=""
                className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              />
               <label>Ngày kết thúc</label>
              <input
              type="date"
                placeholder=""
                className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              />
              <label>Trạng thái</label>
              <select className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota">
                <option>Sử dụng</option>
                <option>Không sử dụng</option>
              </select>
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
            </form>
          </main>
        </div>
      </div>
    </div>
  );
};

export default UpLoadVoucher;
