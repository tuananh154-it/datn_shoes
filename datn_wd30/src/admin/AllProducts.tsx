import { useState } from "react";
import UploadProduct from "./UploadProduct";
// import { MdModeEditOutline } from "react-icons/md";
// import { MdEditSquare } from "react-icons/md";
import UpdateProduct from "./UpdateProduct";
const AllProducts = () => {
  const [openUploadProduct, setOpenUploadProduct] = useState(false);
  const [updateProduct, setupdateProduct] = useState(false);

  return (
    <>
      {/* <div>
      <div classNameName='bg-white py-2 px-4 flex justify-between items-center'>
        <h2 classNameName='font-bold text-lg'>Danh sách sản phẩm</h2>
        <button classNameName='border-2 border-red-600 px-3 py-1 text-red-400 rounded-full hover:bg-red-300 hover:text-white' onClick={()=>setOpenUploadProduct(true)}>Thêm sản phẩm</button>
      </div>
    </div>
    <div>
      <table classNameName='w-full userTable'>
        <thead>
          <tr>
            <th>Tên Sản phẩm</th>
            <th>Ảnh sản phẩm</th>
            <th>Số phân loại</th>
            <th>Ghi chú</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Sản phẩm 1</td>
            <td><img src=""/></td>
            <td>100</td>
            <td>Sản phẩm 1</td>
            <td>Sử dụng</td>
            <td><button classNameName='bg-green-300 p-2 rounded-full hover:bg-green-500 hover:text-white' onClick={()=> setupdateProduct(true)}><MdEditSquare/></button></td>
          </tr>

        </tbody>
      </table>
    </div>
    <div> */}
      <section id="main-content">
        <section className="wrapper">
          <div className="row">
            <div className="col-lg-12">
              <section className="card">
                <header className="card-header">
                  Danh sách sản phẩm
                  <span className="table-add float-right mb-3 mr-2">
                    <a href="#!" onClick={()=>setOpenUploadProduct(true)} className="text-success">
                      <i className="fa fa-plus fa-2x" aria-hidden="true"></i>
                    </a>
                  </span>
                </header>
                <table className="table table-striped table-advance table-hover">
                  <thead>
                    <tr>
                      <th>
                        <i className="fa fa-bullhorn"></i> Tên sản phẩm
                      </th>
                      <th className="hidden-phone">
                        <i className="fa fa-question-circle"></i> Ảnh sản phẩm
                      </th>
                      <th>
                        <i className="fa fa-bookmark"></i> Số phân loại
                      </th>
                      <th>
                        <i className=" fa fa-tags"></i> Ghi chú
                      </th>
                      <th>
                        <i className=" fa fa-edit"></i> Hành động
                      </th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <a href="#">Sản phẩm 1</a>
                      </td>
                      <td className="hidden-phone">
                        <img
                          width={200}
                          height={200}
                          src="https://salt.tikicdn.com/cache/w1200/ts/product/c9/97/70/46f4d5b4ffc1fe8b29f272ac0261b773.jpg"
                        />
                      </td>
                      <td>100</td>
                      <td>
                        <span className="badge badge-info label-mini">Due</span>
                      </td>
                      <td>
                        <button className="btn btn-success btn-sm">
                          <i className="fa fa-check"></i>
                        </button>
                        <button className="btn btn-primary btn-sm">
                          <i className="fa fa-pencil"></i>
                        </button>
                        <button className="btn btn-danger btn-sm">
                          <i className="fa fa-trash-o "></i>
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <a href="#">Sản phẩm 1</a>
                      </td>
                      <td className="hidden-phone">
                        <img
                          width={200}
                          height={200}
                          src="https://salt.tikicdn.com/cache/w1200/ts/product/c9/97/70/46f4d5b4ffc1fe8b29f272ac0261b773.jpg"
                        />
                      </td>
                      <td>100</td>
                      <td>
                        <span className="badge badge-info label-mini">Due</span>
                      </td>
                      <td>
                        <button className="btn btn-success btn-sm">
                          <i className="fa fa-check"></i>
                        </button>
                        <button className="btn btn-primary btn-sm">
                          <i className="fa fa-pencil"></i>
                        </button>
                        <button className="btn btn-danger btn-sm">
                          <i className="fa fa-trash-o "></i>
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <a href="#">Sản phẩm 1</a>
                      </td>
                      <td className="hidden-phone">
                        <img
                          width={200}
                          height={200}
                          src="https://salt.tikicdn.com/cache/w1200/ts/product/c9/97/70/46f4d5b4ffc1fe8b29f272ac0261b773.jpg"
                        />
                      </td>
                      <td>100</td>
                      <td>
                        <span className="badge badge-info label-mini">Due</span>
                      </td>
                      <td>
                        <button className="btn btn-success btn-sm">
                          <i className="fa fa-check"></i>
                        </button>
                        <button className="btn btn-primary btn-sm">
                          <i className="fa fa-pencil"></i>
                        </button>
                        <button className="btn btn-danger btn-sm">
                          <i className="fa fa-trash-o "></i>
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <a href="#">Sản phẩm 1</a>
                      </td>
                      <td className="hidden-phone">
                        <img
                          width={200}
                          height={200}
                          src="https://salt.tikicdn.com/cache/w1200/ts/product/c9/97/70/46f4d5b4ffc1fe8b29f272ac0261b773.jpg"
                        />
                      </td>
                      <td>100</td>
                      <td>
                        <span className="badge badge-info label-mini">Due</span>
                      </td>
                      <td>
                        <button className="btn btn-success btn-sm">
                          <i className="fa fa-check"></i>
                        </button>
                        <button className="btn btn-primary btn-sm">
                          <i className="fa fa-pencil"></i>
                        </button>
                        <button className="btn btn-danger btn-sm">
                          <i className="fa fa-trash-o "></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </section>
            </div>
          </div>
        </section>
      </section>
      {openUploadProduct && (
        <UploadProduct onClose={() => setOpenUploadProduct(false)} />
      )}
      {updateProduct && (
        <UpdateProduct onClose={() => setupdateProduct(false)} />
      )}
    </>
  );
};

export default AllProducts;
