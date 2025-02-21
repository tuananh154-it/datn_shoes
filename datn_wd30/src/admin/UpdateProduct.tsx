import { MdClose } from "react-icons/md";
import { FaCloudUploadAlt } from "react-icons/fa";
import { useState } from "react";
import toast from "react-hot-toast";
const UpdateProduct = ({ onClose }:any) => {
  interface Product {
    name: string;
    description: string;
    imageUrl: string;
    sizes: SizePrice[];
  }
  interface SizePrice {
    size: number;
    price: number;
  }
  const [productData, setProductData] = useState<Product>({
    name: "",
    description: "",
    imageUrl: "",
    sizes: [],
  });
  const [size, setSize] = useState<number>();
  const [price, setPrice] = useState<number>();
  // const [image, setImage] = useState<File | null>(null);
  const addSizePrice = () => {
    if (size && price) {
      setProductData((prevData) => ({
        ...prevData,
        sizes: [...prevData.sizes, { size, price }],
      }));
      setSize(0);
      setPrice(0);
    } else {
      toast.error("Vui lòng nhập size và giá");
    }
  };
  const handleSizePriceChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    if (name === "size") {
      setSize(Number(value));
    } else if (name === "price") {
      setPrice(Number(value));
    }
  };
  return (
    <div className="fixed bg-slate-200 top-0 right-0 bottom-0 left-0 bg-opacity-35 flex justify-center items-center overflow-hidden">
      <div className="bg-white p-4 rounded w-full max-w-[70%] h-full max-h[80%] overflow-y-scroll">
        <div className="flex">
          <div>
            <h1 className="font-bold text-2xl">Update sản phẩm</h1>
          </div>
          <button className="block ml-auto text-2xl" onClick={onClose}>
            <MdClose />
          </button>
        </div>
        <div className="border-b-2 border-gray-300 my-4"></div>
        <div className="p-4 flex w-full h-ful max-h-[70%] ">
          <aside className=" w-[50%]">
            <h2 className="text-xl font-bold">Thông tin chung</h2>
            <p>Ảnh sản phẩm</p>
            <div className="mt-4">
                <label htmlFor="uploadImage">
              <div className="flex justify-center mt-2">
                <div className="border-2 border-dashed border-gray-300 p-6 w-full max-w-md text-center rounded-lg cursor-pointer hover:border-blue-500 hover:bg-gray-100 transition-all">
                  <input
                    type="file"
                    id="uploadImage"
                    className="hidden"
                    accept="image/*"
                  />
                  <div className="text-gray-500 flex justify-center items-center">
                   <FaCloudUploadAlt/>
                   <p>Nhấp để chọn tệp...</p>
                  </div>
                </div>

              </div>
              </label>
            </div>
        
            <div>
                <img src="" width={130} height={150} className="bg-slate-200 border mt-6"/>
            </div>
          </aside>
          <main className="w-[50%]">
            <form className="grid p-4 gap-2">
              <label>Tên sản phẩm</label>
              <input
                placeholder="Tên sản phẩm mới..."
                className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              />
                <div className="flex pt-4 gap-2" >
                <label>Size:</label>
                <input
                  className="p-2 w-full max-w-24 max-h-11 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
                  type="number"
                  name="size"
                  value={size}
                  onChange={handleSizePriceChange}
                  required
                />
                <label>Price:</label>
                <input
                  className="p-2 w-full max-w-36 max-h-11 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
                  type="number"
                  name="price"
                  value={price}
                  onChange={handleSizePriceChange}
                  required
                />
                 <button
                type="button"
                onClick={addSizePrice}
                className="border-2 bg-blue-500 px-4 py-2 flex justify-center items-center text-white rounded-full hover:bg-blue-400"
              >
                Add size
              </button>
              </div>
              <div className="flex">
                <h3 className="text-xl font-bold">Sizes & giá:</h3>
                <ul className="grid font-normal justify-center items-center ml-6">
                  {productData.sizes.map((size, index) => (
                    <li key={index}>
                      Size: {size.size}, Price: ${size.price}
                    </li>
                  ))}
                </ul>
              </div>
              {/* <label>Giá</label>
              <input type="number"
                placeholder="Giá sản phẩm..."
                className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              />

              <label>Khuyến mại</label>
              <input
              type="number"
                placeholder="Giá khuyến mại..."
                className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              /> */}

              <label>Thương hiệu</label>
              <select className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota">
                <option>1</option>
                <option>2</option>
                <option>3</option>
              </select>

              <label>Danh mục</label>
              <select className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota">
                <option>1</option>
                <option>2</option>
                <option>3</option>
              </select>

              <label>Trạng thái</label>
              <select className="p-2 bg-slate-100 border rounded focus:outline-none focus:border-4 focus:border-blue-300 bordermota">
                <option>Sử dụng</option>
                <option>Không sử dụng</option>
              </select>
            </form>
          </main>
        </div>
        <div className="mt-6">
          <h2>Mô tả</h2>
          <div className="">
            <textarea
              className="border w-full h-[150px] focus:outline-none focus:border-4 focus:border-blue-300 bordermota"
              placeholder="Mô tả..."
            />
          </div>
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
          Upload sản phẩm
        </button>
      </div>
    </div>
  );
};

export default UpdateProduct;
