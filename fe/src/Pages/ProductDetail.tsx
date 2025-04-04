import { useEffect, useRef, useState } from "react";
import { Product, Products } from "../types/Product";
import { Link, useNavigate, useParams } from "react-router-dom";
import { getAllProduct, getProductDetail } from "../services/product";
import { useCart } from "../context/CartContext";
import toast from "react-hot-toast";
// import { getProductDetail } from "../axios/asiox";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import { getCommentsByProductId, postComment } from "../services/comments";
import { getReviewsByProductId, postReview, Review, ReviewPayload } from "../services/reviews";
import { getAllOrders, getDetailOrder, Order, OrdersDetail } from "../services/orders";
import Modal from 'react-modal';

const ProductDetail = () => {
  const { addToCart } = useCart();


  const isLoggedIn = localStorage.getItem("token") ? true : false;

  const nav = useNavigate();
  const [quantity, setQuantity] = useState<number>(1);

  const formatPrice = (price: string | number | undefined) => {
    if (typeof price === "number") {
      return price.toLocaleString("vi-VN") + " VND";
    }
    if (typeof price === "string") {
      return (
        Number(price.replace(/,/g, "").replace(" VND", "")).toLocaleString(
          "vi-VN"
        ) + " VND"
      );
    }
    return "0 VND";
  };
  const handleIncrease = () => {
    setQuantity(quantity + 1);
  };

  // Hàm để giảm số lượng
  const handleDecrease = () => {
    if (quantity > 1) {
      setQuantity(quantity - 1);
    }
  };
 const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
  const value = e.target.value;
  if (/^\d*$/.test(value)) {
    setQuantity(value === "" ? "" : parseInt(value, 10));
  }
};

  const [productId, setProductId] = useState<Products>();
  const [selectedDetail, setSelectedDetail] = useState<any>(null);
  const [selectedColor, setSelectedColor] = useState<string | null>(null);
  const [selectedSize, setSelectedSize] = useState<string | null>(null);
  const [totalAddedToCart, setTotalAddedToCart] = useState(0); // Tổng số lượng đã thêm vào giỏ hàng
  const { id } = useParams();
  useEffect(() => {
    if (selectedDetail) {
      setTotalAddedToCart(0);
    }
  }, [selectedDetail]);
  useEffect(() => {
    if (!id) return;
    getProductDetail(id).then(({ data }) => {
      console.log("data", data);
      setProductId(data.data);
      // setSelectedDetail(data.data.details[0]);
    });
  }, [id]);
  console.log("data", productId);

  // const handleVariantClick = (detail: any) => {
  //   if (detail) {
  //     setSelectedDetail(detail);
  //   } else {
  //     setSelectedDetail(null);
  //   }
  // };
  const colorSizeMap =
    productId?.details?.reduce((acc, detail) => {
      if (!acc[detail.color]) {
        acc[detail.color] = [];
      }
      acc[detail.color].push(detail.size);
      return acc;
    }, {} as Record<string, string[]>) || {};

   // Lấy danh sách ảnh từ biến thể
   const detailImages: string[] = productId?.details
   ?.map(detail => detail.image?.[0])
   .filter((img): img is string => typeof img === 'string') || [];

// Kiểm tra và thêm ảnh chính nếu chưa có trong biến thể
const allImages = [...detailImages];
if (productId?.image && !detailImages.includes(productId.image)) {
  allImages.unshift(productId.image);
}

// Lọc ảnh không trùng nhau
const uniqueImages = Array.from(new Set(allImages)).map(img => {
  return productId?.details.find(detail => detail.image[0] === img) || {
    image: [img], // ảnh chính không có detail nên tạo object giả
    size: '',
    color: '',
  };
});

// State điều hướng slider
const scrollRef = useRef<HTMLDivElement>(null);

const handleScroll = (direction: 'left' | 'right') => {
  const scrollContainer = scrollRef.current;
  if (scrollContainer) {
    const scrollAmount = 120; // px mỗi lần scroll
    scrollContainer.scrollBy({
      left: direction === 'right' ? scrollAmount : -scrollAmount,
      behavior: 'smooth',
    });
  }
};
  // Danh sách màu sắc không trùng lặp
  const uniqueColors = Object.keys(colorSizeMap);

  const getVariantImagesForColor = (color: string) => {
    const colorDetails =
      productId?.details.filter((detail) => detail.color === color) || [];
    return colorDetails.flatMap((detail) => detail.image); // flatMap giúp kết hợp tất cả các ảnh vào một mảng
  };
  const handleColorSelect = (color: string) => {
    setSelectedColor(color);
    setSelectedSize(colorSizeMap[color][0]); // Chọn size đầu tiên của màu đó
    const matchingDetail = productId?.details.find(
      (d) => d.color === color && d.size === colorSizeMap[color][0]
    );
    setSelectedDetail(matchingDetail || null);
  };

  // Xử lý chọn size
  const handleSizeSelect = (size: string) => {
    setSelectedSize(size);
    const matchingDetail = productId?.details.find(
      (d) => d.color === selectedColor && d.size === size
    );
    setSelectedDetail(matchingDetail || null);
  };
  const handleVariantClick = (detail: any) => {
    if (detail) {
      setSelectedDetail(detail);
    } else {
      setSelectedDetail(null);
    }
  };

  function getColorFromText(colorText: string): string {
    switch (colorText.toLowerCase()) {
      case "màu trắng":
        return "#FFFFFF"; // Trắng
      case "màu đen":
        return "#000000"; // Đen
      case "màu đỏ":
        return "#FF0000"; // Đỏ
      case "màu xanh dương":
        return "#0000FF"; // Xanh dương
      case "màu xanh lá":
        return "#008000"; // Xanh lá
      case "màu vàng":
        return "#FFFF00"; // Vàng
      case "màu cam":
        return "#FFA500"; // Cam
      case "màu tím":
        return "#800080"; // Tím
      case "màu hồng":
        return "#FFC0CB"; // Hồng
      case "màu nâu":
        return "#A52A2A"; // Nâu
      case "màu xám":
        return "#808080"; // Xám
      case "màu xanh ngọc":
        return "#00CED1"; // Xanh ngọc
      default:
        return "#000000"; // Mặc định là đen nếu không tìm thấy màu
    }
  }
  const [products, setProducts] = useState<Product[]>([]);
  useEffect(() => {
    getAllProduct().then(({ data }) => {
      setProducts(data.data);
    });
  }, []);

  // Bình luận 
  const [comments, setComments] = useState<Comment[]>([]);
  const [newComment, setNewComment] = useState<string>(''); // Sửa thành chuỗi
  const [totalComments, setTotalComments] = useState(0);
  // const [replyContent, setReplyContent] = useState<{ [key: number]: string }>({});
  // const [editContent, setEditContent] = useState<{ [key: number]: string }>({});
  // const [editingCommentId, setEditingCommentId] = useState<number | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // Lấy productId từ products
  const productIdNumber = products.find(product => product.id === parseInt(id || '0', 10))?.id || parseInt(id || '0', 10);

  // Lấy danh sách bình luận
  useEffect(() => {
    const fetchComments = async () => {
      if (!productIdNumber || isNaN(productIdNumber) || productIdNumber <= 0) {
        setError('Không có productID hợp lệ.');
        setLoading(false);
        return;
      }

      try {
        setLoading(true);
        setError(null);
        const response = await getCommentsByProductId(productIdNumber);
        console.log('Dữ liệu bình luận:', response.data);
        setComments(response.data.comments);
        setTotalComments(response.data.total_comments); // Lưu tổng số đánh giá
        console.log("Danh sách bình luận:", comments);
      } catch (error: any) {
        console.error('Lỗi khi lấy bình luận:', error);
        setError(error.response?.data?.message || 'Không thể tải bình luận. Vui lòng thử lại sau.');
      } finally {
        setLoading(false);
      }
    };

    if (products.length > 0) { // Đảm bảo products đã được tải
      fetchComments();
    }
  }, [productIdNumber, products]);

  // Đăng bình luận mới
  const handlePostComment = async () => {
    if (!newComment.trim()) {
      alert('Vui lòng nhập nội dung bình luận!');
      return;
    }

    if (!productIdNumber || isNaN(productIdNumber) || productIdNumber <= 0) {
      console.error('productID không hợp lệ:', productIdNumber);
      alert('productID không hợp lệ. Vui lòng kiểm tra lại.');
      return;
    }

    try {
      const response = await postComment(productIdNumber, newComment);
      console.log('Bình luận mới:', response.data);
      setComments([...comments, response.data.data]);
      setNewComment('');
    } catch (error: any) {
      console.error('Lỗi khi đăng bình luận:', error);
      alert(error.response?.data?.message || 'Lỗi khi đăng bình luận!');
    }
  };
  // đánh giá sản phẩm
  const [reviews, setReviews] = useState<Review[]>([]);
  const [totalReviews, setTotalReviews] = useState(0);
  const [newReview, setNewReview] = useState<string>(''); // Sửa thành chuỗi
  const [eligibleOrderId, setEligibleOrderId] = useState<string | null>(null); // OrderId hợp lệ
  const [rating, setRating] = useState<number>(0);       // Rating từ 1-5
  const [isModalOpen, setIsModalOpen] = useState(false);      // Trạng thái modal
  const [hasReviewed, setHasReviewed] = useState(false);
  // sao đánh giá
  // Hàm render sao (hiển thị và cho phép bấm trong form)
  const renderStars = (rating: number, editable: boolean = false) => {
    return Array.from({ length: 5 }, (_, index) => (
      <span
        key={index}
        style={{
          color: index < rating ? 'gold' : 'gray',
          cursor: editable ? 'pointer' : 'default',
          fontSize: '24px', // Tùy chỉnh kích thước sao
        }}
        onClick={editable ? () => setRating(index + 1) : undefined} // Bấm để chọn rating
      >
        ★
      </span>
    ));
  };

  useEffect(() => {
    const fetchReviews = async () => {
      if (!productIdNumber || isNaN(productIdNumber) || productIdNumber <= 0) {
        setError('Không có productID hợp lệ.');
        setLoading(false);
        return;
      }

      try {
        setLoading(true);
        setError(null);
        const response = await getReviewsByProductId(productIdNumber);
        console.log('Dữ liệu đánh giá:', response.data);
        setReviews(response.data.reviews);
        setTotalReviews(response.data.total_reviews); // Lưu tổng số đánh giá
        console.log("tong review:", totalReviews);

        console.log("Danh sách đánh giá:", reviews);
      } catch (error: any) {
        console.error('Lỗi khi lấy đánh giá:', error);
        setError(error.response?.data?.message || 'Không thể tải đánh giá. Vui lòng thử lại sau.');
      } finally {
        setLoading(false);
      }
    };

    if (products.length > 0) { // Đảm bảo products đã được tải
      fetchReviews();
    }
  }, [productIdNumber, products]);
  // tạo đánh giá mới
  useEffect(() => {
    const fetchOrders = async () => {
      try {
        setLoading(true);

        // 1️⃣ Lấy danh sách đơn hàng
        const response = await getAllOrders();
        const orders: Order[] = response.data;
        console.log("Danh sách đơn hàng:", orders);

        // 2️⃣ Lọc đơn hàng có trạng thái 'delivered'
        const deliveredOrders = orders.filter(order => order.status === "delivered");
        console.log("Đơn hàng có trạng thái 'delivered':", deliveredOrders);

        if (deliveredOrders.length === 0) {
          console.log("Không có đơn hàng nào được giao.");
          setEligibleOrderId(null);
          setLoading(false);
          return;
        }

        // 3️⃣ Gọi API getDetailOrder và kiểm tra lỗi
        const orderDetailsResponses = await Promise.all(
          deliveredOrders.map(async (order) => {
            try {
              console.log(`Gọi API getDetailOrder với order.id = ${order.id}`);
              const res = await getDetailOrder(order.id);
              return { orderId: order.id, data: res.data }; // Lưu cả orderId
            } catch (error) {
              console.error(`Lỗi khi gọi API getDetailOrder(${order.id}):`, error);
              return null;
            }
          })
        );

        // 4️⃣ Loại bỏ các response null (có lỗi 404)
        const validOrders = orderDetailsResponses.filter(item => item !== null);
        if (validOrders.length === 0) {
          console.log("Không có đơn hàng hợp lệ sau khi gọi API getDetailOrder.");
          setEligibleOrderId(null);
          setLoading(false);
          return;
        }

        // 5️⃣ Tìm đơn hàng chứa sản phẩm
        let eligibleOrderId = null;
        for (const order of validOrders) {
          const productIds = order.data.order_details
            .map((detail: OrdersDetail) => detail.product_detail?.product_id)
            .filter(id => id !== undefined);
          console.log(`Danh sách product_id trong đơn hàng ${order.orderId}:`, productIds);

          if (productIds.includes(productIdNumber)) {
            eligibleOrderId = order.orderId.toString(); // Lưu orderId thực sự
            break;
          }
        }

        console.log("Eligible Order ID:", eligibleOrderId);
        setEligibleOrderId(eligibleOrderId);

      } catch (err) {
        console.error("Lỗi khi lấy danh sách đơn hàng:", err);
        setError('Không thể tải danh sách đơn hàng');
      } finally {
        setLoading(false);
      }
    };

    fetchOrders();
  }, [productIdNumber]);




  console.log("id cua san pham", productIdNumber);

  const handlePostReview = async () => {
    // Kiểm tra nhanh các điều kiện đầu vào
    if (!newReview.trim()) {
      alert('Vui lòng nhập nội dung bình luận!');
      return;
    }
    if (!productIdNumber || isNaN(productIdNumber) || productIdNumber <= 0) {
      alert('productID không hợp lệ!');
      return;
    }
    if (!eligibleOrderId) {
      alert('Bạn chưa mua sản phẩm này hoặc đơn hàng chưa được giao.');
      return;
    }
    if (rating === 0) {
      alert('Vui lòng chọn số sao!');
      return;
    }

    const reviewData: ReviewPayload = {
      rating,
      content: newReview,
    };

    try {
      const newReviewResponse = await postReview(
        productIdNumber.toString(),
        eligibleOrderId,
        reviewData
      );
      console.log("New Review Response:", newReviewResponse); // In dữ liệu trả về
      setReviews((prev) => [...prev, newReviewResponse]); // Tối ưu cập nhật state
      toast.success("Đánh giá của bạn đã được đăng thành công!");
      setNewReview('');
      setRating(0);
      setHasReviewed(true);
      setIsModalOpen(false);
    } catch (error: any) {
      alert(error.message || 'Lỗi khi đăng đánh giá!');
    }
  };

  // if (loading) return <div>Đang kiểm tra đơn hàng...</div>;
  // if (error) return <div>{error}</div>;
  // Bind Modal với root element (cần cho accessibility)
  Modal.setAppElement('#root');
  useEffect(() => {
    console.log("Trạng thái modal:", isModalOpen);
  }, [isModalOpen]);
  return (
    <>
      <div className="menu_overlay"></div>
      <div className="main_section">
        <section className="breadcrumb_section nav">
          <div className="container">
            <nav aria-label="breadcrumb">
              <ol className="breadcrumb">
                <li className="breadcrumb-item text-capitalize">
                  <a href="earthyellow.html">Home</a>{" "}
                  <i className="flaticon-arrows-4"></i>
                </li>
                <li className="breadcrumb-item text-capitalize">
                  <a href="product_list_with_sidebar.html">Cửa hàng</a>{" "}
                  <i className="flaticon-arrows-4"></i>
                </li>
                <li className="breadcrumb-item active text-capitalize">
                  {productId?.name}
                </li>
              </ol>
            </nav>
            <h1 className="title_h1 font-weight-normal text-capitalize">
              {productId?.name}
            </h1>
          </div>
        </section>
        <section className="padding-top-text-60 padding-bottom-60 product_detail_section">
          {productId ? (
            <div className="container">
              <div className="main">
                {/* Phần bên trái với ảnh chính */}
                {/* <div className="main-left" data-wow-duration="1300ms">
                  <div className="imageProduct">
                    <img
                      src={selectedDetail?.image || productId.image}
                      alt="Product"
                    />
                  </div>
                  <div className="imageBienthe">
                  {productId.details.map((image)=>(
                      <img src={image.image}/>
                  ))}
                    </div>
                </div> */}
                <div className="main-left" data-wow-duration="1300ms">
    {/* Ảnh chính */}
    <div className="imageProduct">
      <img
        src={selectedDetail?.image[0] || productId.image}
        alt="Product"
      />
    </div>

    {/* Ảnh biến thể */}
    {/* <div className="imageBienthe">
  {uniqueImages.length > visibleCount && (
    <button className="nav-button" onClick={handlePrev} disabled={currentIndex === 0}>
      ‹
    </button>
  )}

  {visibleImages.map((detail, index) => (
    <img
      key={index}
      src={detail.image[0]}
      alt={`Variant ${index}`}
      className="variant-thumb"
      onClick={() => setSelectedDetail(detail)}
    />
  ))}

  {uniqueImages.length > visibleCount && (
    <button
      className="nav-button"
      onClick={handleNext}
      disabled={currentIndex + visibleCount >= uniqueImages.length}
    >
      ›
    </button>
  )}
</div> */}
<div className="imageBienthe-wrapper" style={{ position: 'relative' }}>
  {/* Nút chuyển trái */}
  {uniqueImages.length > 2 && (
    <a className="nav-button left" onClick={() => handleScroll('left')}>
      ‹
    </a>
  )}

  <div
    ref={scrollRef}
    className="imageBienthe overflow-x-auto whitespace-nowrap no-scrollbar"
    style={{ scrollBehavior: 'smooth' }}
  >
    {uniqueImages.map((detail, index) => (
      <img
        key={index}
        src={detail.image[0]}
        alt={`Variant ${index}`}
        className="inline-block w-24 h-24 object-cover mx-1 cursor-pointer rounded variant-thumb"
        onClick={() => setSelectedDetail(detail)}
      />
    ))}
  </div>

  {/* Nút chuyển phải */}
  {uniqueImages.length > 2 && (
    <a className="nav-button right" onClick={() => handleScroll('right')}>
      ›
    </a>
  )}
</div>
  </div>
                <div className="main-right" data-wow-duration="1300ms">
                  <div className="product_content">
                    <div className="product_title">
                      {/* Tên sản phẩm */}
                      <p className="product_price title_h4">
                        {selectedDetail?.name || productId.name}
                      </p>

                      {/* Thương hiệu */}
                      <p className="sku_text">
                        Thương hiệu:{" "}
                        <a className="font-bold">{productId.brand}</a>
                      </p>

                      {/* Giá sản phẩm */}
                      {/* <p className="text-color title_h4">
                          {selectedDetail?.discount_price ||
                            selectedDetail?.price ||
                            productId.price}
                        </p> */}
                      <p className="text-color title_h4">
                        {selectedDetail?.discount_price ? (
                          <>
                            <span className="original-price">
                              {selectedDetail?.default_price
                                ? Number(
                                  selectedDetail.default_price
                                    .replace(/,/g, "")
                                    .replace(" VND", "")
                                ).toLocaleString("vi-VN") + " VND"
                                : "0 VND"}
                            </span>{" "}
                            {/* Giá gốc */}
                            <span className="discount-price">
                              {selectedDetail?.default_price
                                ? Number(
                                  selectedDetail.default_price
                                    .replace(/,/g, "")
                                    .replace(" VND", "")
                                ).toLocaleString("vi-VN") + " VND"
                                : "0 VND"}
                            </span>{" "}
                            {/* Giá khuyến mại */}
                          </>
                        ) : (
                          <span className="default-price">
                            {formatPrice(
                              selectedDetail?.default_price || productId?.price
                            )}
                          </span>
                        )}
                      </p>

                      <p>Số lượng: {selectedDetail?.quantity}</p>
                      {/* Đánh giá */}
                      <div className="star">
                        <img
                          src="../public/src/images/star.png"
                          className="img-fluid"
                          alt="star"
                        />
                        ({totalReviews} review)
                      </div>
                    </div>

                    <form>
                      <div className="product_variant">
                        <div className="form-group color_box">
                          <label className="title_h5 text-capitalize">
                            Màu sắc
                          </label>

                          {/* Màu sắc */}

                          {uniqueColors.map((color, index) => (
                            <div key={index} className="radio p-2">
                              <input
                                type="radio"
                                name="color"
                                id={`color${index}`}
                                checked={selectedColor === color}
                                onChange={() => handleColorSelect(color)}
                              />
                              <label
                                htmlFor={`color${index}`}
                                style={{
                                  backgroundColor: getColorFromText(color),
                                }} // Dùng hàm getColorFromText để lấy mã màu
                              ></label>
                            </div>
                          ))}
                        </div>

                        <div className="form-group size_box">
                          <label className="title_h5 text-capitalize">
                            Kích thước
                          </label>
                          <select
                            className="form-control"
                            value={selectedSize || ""}
                            onChange={(e) => handleSizeSelect(e.target.value)}
                          >
                            {selectedColor &&
                              colorSizeMap[selectedColor].map((size, index) => (
                                <option key={index} value={size}>
                                  {size}
                                </option>
                              ))}
                          </select>
                        </div>

                        <div className="form-group quantity_box">
                          <label className="title_h5 text-capitalize">
                            Số lượng
                          </label>
                          <div className="qty_number">
                            <button
                              type="button"
                              onClick={handleDecrease}
                              style={{
                                padding: "5px 10px",
                                cursor: "pointer",
                              }}
                            >
                              -
                            </button>

                            <input type="text" value={quantity} onChange={handleChange}/>
                            <button
                              type="button"
                              onClick={handleIncrease}
                              style={{
                                padding: "5px 10px",
                                cursor: "pointer",
                              }}
                            >
                              +
                            </button>
                          </div>
                        </div>
                      </div>

                      <div className="product_btns">
                        <a
                          href="/wishlist"
                          className="wishlist_btn border-btn text-uppercase"
                        >
                          thêm vào ds yêu thích
                        </a>
                        {/* <a
                            href="/cart"
                            className="background-btn text-uppercase cart_btn"
                          >
                            add to bag
                          </a>
                         */}
                        <button
                          type="button"
                          className="background-btn text-uppercase cart_btn"
                          onClick={async () => {
                            if (!isLoggedIn) {
                              alert("Vui lòng đăng nhập trước khi thêm vào giỏ hàng!");
                              nav("/login");
                              return;
                            }

                            if (!selectedDetail) {
                              alert("Vui lòng chọn biến thể trước khi thêm vào giỏ hàng!");
                              return;
                            }

                            try {
                              const originalQuantity = Number(selectedDetail.quantity);

                              if (quantity <= 0) {
                                toast.error("Số lượng phải lớn hơn 0");
                                return;
                              }

                              const newTotalAddedToCart = totalAddedToCart + quantity;

                              if (newTotalAddedToCart > originalQuantity) {
                                toast.error(
                                  `Không thể thêm vào giỏ hàng. Tổng số lượng đã thêm (${newTotalAddedToCart}) vượt quá số lượng gốc (${originalQuantity}).`
                                );
                                return;
                              }

                              try {
                               addToCart(Number(selectedDetail.id), quantity);
                              } catch (error) {
                                console.error("Lỗi từ addToCart:", error);
                                toast.error("Có lỗi xảy ra khi thêm vào giỏ hàng: ");
                                return;
                              }

                              setTotalAddedToCart(newTotalAddedToCart);

                              toast.success("Thêm vào giỏ hàng thành công");

                              console.log(
                                "Dữ liệu gửi lên API:",
                                JSON.stringify({
                                  product_detail_id: selectedDetail.id,
                                  quantity,
                                })
                              );

                              console.log("Số lượng gốc:", originalQuantity);
                              console.log("Tổng số lượng đã thêm:", newTotalAddedToCart);
                            } catch (error) {
                              console.error("Lỗi:", error);
                              toast.error("Có lỗi xảy ra khi thêm vào giỏ hàng: ");
                            }
                          }}
                        >
                          Add to cart
                        </button>
                        {/* <div className="product_share">
                          <p>Share the love</p>
                          <ul className="social_icons">
                            <li className="text-center">
                              <a href="javascript:void(0);">
                                <i className="flaticon-facebook vertical_middle"></i>
                              </a>
                            </li>
                            <li className="text-center">
                              <a href="javascript:void(0);">
                                <i className="flaticon-pinterest vertical_middle"></i>
                              </a>
                            </li>
                            <li className="text-center">
                              <a href="javascript:void(0);">
                                <i className="flaticon-instagram-logo vertical_middle"></i>
                              </a>
                            </li>
                          </ul>
                        </div> */}
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div className="product_description">
                <div className="row">
                  <div
                    className="col-md-6 wow fadeInRight"
                    data-wow-duration="1300ms"
                  >
                    <h5 className="title_h5 text-capitalize">Mô tả sản phẩm</h5>
                    <p>{productId.description}</p>
                  </div>
                  <div
                    className="col-md-6 wow fadeInRight"
                    data-wow-duration="1300ms"
                  >
                    <h5 className="title_h5 text-capitalize">
                      Giao hàng nhanh, mọi lúc ,mọi nơi
                    </h5>
                    <p>
                      Nhằm mang đến trải nghiệm mua sắm thuận tiện nhất, chúng tôi cung cấp dịch vụ giao hàng nhanh chóng,
                      an toàn và linh hoạt trên toàn quốc.

                      Thời gian giao hàng:

                      Giao hàng tiêu chuẩn: 2-5 ngày làm việc.

                      Giao hàng nhanh: 24-48 giờ (áp dụng tại các thành phố lớn).

                      Giao hàng hỏa tốc: Nhận hàng trong ngày (chỉ áp dụng tại một số khu vực).

                      Đối tác vận chuyển:
                      Chúng tôi hợp tác với các đơn vị giao hàng uy tín như GHN, GHTK, Viettel Post, J&T Express…
                      nhằm đảm bảo đơn hàng được giao đúng thời gian, đúng địa điểm và trong tình trạng nguyên vẹn.

                      Chính sách kiểm tra hàng trước khi nhận:
                      Khách hàng có thể kiểm tra sản phẩm trước khi thanh toán. Nếu có bất kỳ lỗi sản xuất hoặc sai sót trong đơn hàng,
                      chúng tôi cam kết hỗ trợ đổi trả nhanh chóng mà không mất thêm phí.

                      Miễn phí vận chuyển:
                      Chúng tôi hỗ trợ miễn phí vận chuyển cho các đơn hàng từ [số tiền cụ thể] trở lên,
                      giúp khách hàng tiết kiệm chi phí khi mua sắm.
                    </p>
                  </div>
                  <div
                    className="col-md-12 wow fadeInUp"
                    data-wow-duration="1300ms"
                  >
                    <div id="accordion">
                      {/* <div className="card">
                        <div className="card-header" id="headingOne">
                          <h5 className="mb-0">
                            <button
                              className="title_h5 btn-link collapsed text-left"
                              data-toggle="collapse"
                              data-target="#collapseOne"
                              aria-expanded="true"
                              aria-controls="collapseOne"
                            >
                              BÌnh luận
                            </button>
                          </h5>
                        </div>
                        <div
                          id="collapseOne"
                          className="collapse"
                          aria-labelledby="headingOne"
                          data-parent="#accordion"
                        >
                          <div className="card-body">
                           
                          </div>
                        </div>
                      </div> */}

                      <div className="card">
                        <div className="card-header" id="headingOne">
                          <h5 className="mb-0">
                            <button
                              className="title_h5 btn-link collapsed text-left"
                              data-toggle="collapse"
                              data-target="#collapseOne"
                              aria-expanded="true"
                              aria-controls="collapseOne"
                            >
                              Bình luận({totalComments})
                            </button>
                          </h5>
                        </div>
                        <div id="collapseOne" className="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                          {/* <div className="card-body">
                            
                            {comments.length > 0 ? (
                              <div className="comment-section">
                              {comments.map((comment) => (
                                <div key={comment.id} className="comment-container">
                                  <img src={comment.avatar} alt="User Avatar" className="avatar" />
                                  <div className="comment-content">
                                    <div className="comment-header">
                                      <strong className="user-name">{comment.user_name}</strong>
                                      <span className="comment-time">{comment.time}</span>
                                    </div>
                                    <p className="comment-text">{comment.comment}</p>
                                  </div>
                                </div>
                              ))}
                            </div>
                            ) : (
                              <p>Bạn hãy là người đầu tiên bình luận!</p>
                            )}

                           
                            <div className="comment-input">
                              <input
                                type="text"
                                className="form-control"
                                placeholder="Viết bình luận..."
                                value={newComment}
                                onChange={(e) => setNewComment(e.target.value)}
                              />
                              <button className="btn btn-primary" >
                                Gửi
                              </button>
                            </div>
                          </div> */}
                          <div className="card-body">
                            {/* Hiển thị danh sách bình luận */}
                            {comments.length > 0 ? (
                              <div className="comment-section">
                                {comments.map((comment) => (
                                  <div key={comment.id} className="comment-container">
                                    <img
                                      src="../src/images/reivew_user.png "// Nếu không có avatar, dùng ảnh mặc định
                                      alt="User Avatar"
                                      className="avatar"
                                    />

                                    <div className="comment-content">
                                      <strong className="user-name">
                                        {comment.is_anonymous ? 'Ẩn danh' : comment.user_name}
                                      </strong>
                                      <p className="comment-text">{comment.content}</p>
                                      <div className="comment-header">
                                        <span className="comment-time">
                                          {comment.created_at}
                                        </span>
                                      </div>

                                    </div>
                                  </div>
                                ))}
                              </div>
                            ) : (
                              <p>Bạn hãy là người đầu tiên bình luận!</p>
                            )}

                            {/* Ô nhập bình luận */}
                            <div className="comment-input">
                              <input
                                type="text"
                                className="form-control"
                                placeholder="Viết bình luận..."
                                value={newComment}
                                onChange={(e) => setNewComment(e.target.value)}
                              />
                              <button className="btn btn-primary" onClick={handlePostComment}>
                                Gửi
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                      {/* <div className="card">
                        <div className="card-header" id="headingTwo">
                          <h5 className="mb-0">
                            <button
                              className="title_h5 btn-link collapsed text-left"
                              data-toggle="collapse"
                              data-target="#collapseTwo"
                              aria-expanded="false"
                              aria-controls="collapseTwo"
                            >
                              Size Chart
                            </button>
                          </h5>
                        </div>
                        <div
                          id="collapseTwo"
                          className="collapse"
                          aria-labelledby="headingTwo"
                          data-parent="#accordion"
                        >
                          <div className="card-body">
                            <p>
                              Lorem ipsum dolor sit amet, consectetur adipiscing
                              elit, sed do eiusmod tempor incididunt ut labore
                              et dolore magna aliqua. Ut enim ad minim veniam,
                              quis nostrud exercitation ullamco laboris nisi ut
                              aliquip ex ea commodo consequat.
                            </p>
                            <p>
                              Duis aute irure dolor in reprehenderit in
                              voluptate velit esse cillum dolore eu fugiat nulla
                              pariatur.
                            </p>
                          </div>
                        </div>
                      </div> */}
                      <div className="card">
                        <div className="card-header" id="headingThree">
                          <h5 className="mb-0">
                            <button
                              className=" btn-link title_h5 text-left"
                              data-toggle="collapse"
                              data-target="#collapseThree"
                              aria-expanded="false"
                              aria-controls="collapseThree"
                            >
                              Đánh giá ({totalReviews})
                            </button>
                          </h5>
                        </div>
                        <div
                          id="collapseThree"
                          className="collapse show"
                          aria-labelledby="headingThree"
                          data-parent="#accordion"
                        >
                          <div className="card-body">
                            <div className="review_title">
                              <h4 className="title_h4">Khách hàng đánh giá</h4>
                              <div className="star">
                                <img src="../src/images/star.png" className="img-fluid" alt="star" />
                                Dựa trên {totalReviews} đánh giá
                              </div>
                              <Link
                                to="#"
                                className="write_review_text"
                                onClick={(e) => {
                                  e.preventDefault();
                                  // if (!hasReviewed) {
                                  //   toast.error("Bạn đã đánh giá sản phẩm này rồi. Tiếp tục mua hàng để đánh giá thêm.");
                                  //   return;
                                  // }
                                  console.log("Mở modal");
                                  setIsModalOpen(true);
                                }}
                              >
                                Thêm đánh giá
                              </Link>
                            </div>

                            {reviews.map((review) => (
                              <div className="review_content" key={review.id}>
                                <div className="user_img rounded-circle">
                                  <img
                                    src="../src/images/reivew_user.png"
                                    className="img-fluid vertical_middle"
                                    alt="user"
                                  />
                                </div>
                                <div className="user_detail">
                                  <h5 className="title_h5">{review.user_name}</h5>
                                  <p>{renderStars(review.rating)}</p>
                                  <span className="review__date">{review.created_at}</span>
                                  <p>{review.content}</p>
                                </div>
                              </div>
                            ))}

                            {/* Modal đánh giá */}
                            <Modal
                              isOpen={isModalOpen}
                              onRequestClose={() => setIsModalOpen(false)}
                              className="review-modal"
                              overlayClassName="review-modal-overlay"
                            >
                              <h2>Thêm đánh giá của bạn</h2>
                              {!eligibleOrderId ? (
                                <p>Bạn cần mua và nhận sản phẩm này để đánh giá.</p>
                              ) : (
                                <div className="review-form">
                                  <label>Đánh giá (1-5 sao):</label>
                                  <div>{renderStars(rating, true)}</div>
                                  <label>Nội dung đánh giá:</label>
                                  <textarea
                                    value={newReview}
                                    onChange={(e) => setNewReview(e.target.value)}
                                    maxLength={500}
                                    placeholder="Viết đánh giá của bạn..."
                                    rows={4}
                                  />
                                  <div className="modal-buttons">
                                    <button onClick={handlePostReview} className="submit-btn">Gửi</button>
                                    <button onClick={() => setIsModalOpen(false)} className="cancel-btn">Hủy</button>
                                  </div>
                                </div>
                              )}
                            </Modal>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div className="featured_section padding-top-text-60 wow fadeIn">
                <h2 className="text-center title_h3">Bạn có thể thích</h2>
                <Swiper
                  modules={[Navigation]}
                  spaceBetween={20}
                  slidesPerView={4} // Hiển thị 4 sản phẩm mỗi lần
                  navigation // Bật mũi tên điều hướng
                  breakpoints={{
                    320: { slidesPerView: 1 },
                    768: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 },
                    1200: { slidesPerView: 4 },
                  }}
                >
                  {products.map((product) => (
                    <SwiperSlide key={product.id}>
                      <div className="featured_content">
                        <div className="featured_img_content">
                          <img
                            src={product.image}
                            alt="f_product"
                            className="img-product_detail"
                          />
                          <div className="featured_btn vertical_middle">
                            <a
                              href="cart.html"
                              className="text-uppercase background-btn add_to_bag_btn"
                            >
                              Thêm vào giỏ hàng
                            </a>
                            <a
                              href={`/product_detail/${product.id}`}
                              className="text-uppercase border-btn popup_btn"
                            >
                              Xem chi tiết
                            </a>
                          </div>
                          <a
                            href="javascript:void(0);"
                            className="heart rounded-circle text-center"
                          >
                            <i className="flaticon-heart vertical_middle"></i>
                          </a>
                        </div>
                        <div className="featured_detail_content">
                          <a href={`/product_detail/${product.id}`}>
                            <p className="featured_title text-capitalize text-center">
                              {product.name}
                            </p>
                          </a>
                          <p className="featured_price title_h5 text-center">
                            <span className="text-color">
                              {product?.price
                                ? Number(
                                  product.price
                                    .replace(/,/g, "")
                                    .replace(" VND", "")
                                ).toLocaleString("vi-VN") + " VND"
                                : "0 VND"}
                            </span>
                          </p>
                        </div>
                      </div>
                    </SwiperSlide>
                  ))}
                </Swiper>
              </div>
            </div>
          ) : (
            <div className="container">
              <p>Đang tải...</p>
            </div>
          )}
        </section>
      </div>

      <div id="modalone" className="modal">
        <div className="modal-content">
          <a href="javascript:void(0);" className="close_popup">
            <span>&times;</span>
          </a>
          <div className="product_detail_section">
            <div className="container">
              <div className="row">
                <div className="col-lg-6">
                  <div id="q_sync1" className="owl-carousel owl-theme">
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img2.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img3.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img4.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                  </div>
                  <div id="q_sync2" className="owl-carousel owl-theme">
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img2.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img3.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                    <div className="item">
                      <div className="product_img">
                        <img
                          src="src/images/blue_jacket_img4.png"
                          alt="blue_jacket_img"
                          className="vertical_middle img-fluid"
                        />
                      </div>
                    </div>
                  </div>
                </div>
                <div className="col-lg-6">
                  <div className="product_content">
                    <div className="product_title">
                      <span className="product_price title_h4"> $59.95</span>
                      <span className="stock text-right">In Stock</span>
                      <p className="sku_text">SKU: 01-2345678</p>
                      <div className="star">
                        <img
                          src="src/images/star.png"
                          className="img-fluid"
                          alt="star"
                        />
                        (1 Review)
                      </div>
                    </div>

                    <form>
                      <div className="product_variant">
                        <div className="form-group color_box">
                          <label className="title_h5 text-capitalize">
                            Color
                          </label>
                          <div className="radio text-uppercase text-center">
                            <input type="radio" name="color" id="color1" />
                            <label htmlFor="color1" className="color1"></label>
                          </div>
                          <div className="radio text-uppercase text-center">
                            <input type="radio" name="color" id="color2" />
                            <label htmlFor="color2" className="color2"></label>
                          </div>
                          <div className="radio text-uppercase text-center">
                            <input type="radio" name="color" id="color3" />
                            <label htmlFor="color3" className="color3"></label>
                          </div>
                          <div className="radio text-uppercase text-center">
                            <input type="radio" name="color" id="color4" />
                            <label htmlFor="color4" className="color4"></label>
                          </div>
                        </div>
                        <div className="form-group size_box">
                          <label className="title_h5 text-capitalize">
                            Size
                          </label>
                          <select className="form-control">
                            <option>XS</option>
                            <option>S</option>
                            <option>M</option>
                            <option>L</option>
                            <option>XL</option>
                          </select>
                        </div>
                        <div className="form-group quantity_box">
                          <label className="title_h5 text-capitalize">
                            Quantity
                          </label>
                          <div className="qty_number">
                            <input type="text" value="1" />
                          </div>
                        </div>
                      </div>

                      <div className="product_btns">
                        <a
                          href="wishlist.html"
                          className="wishlist_btn border-btn text-uppercase"
                        >
                          add to wishlist{" "}
                        </a>
                        <button
                          type="submit"
                          className="background-btn text-uppercase cart_btn"
                        >
                          add to bag
                        </button>
                        <a
                          href="/cart"
                          className="background-btn text-uppercase cart_btn"
                        >
                          add to bag
                        </a>
                        <div className="product_share">
                          <p>Share the love</p>
                          <ul className="social_icons">
                            <li className="text-center">
                              <a href="javascript:void(0);">
                                <i className="flaticon-facebook vertical_middle"></i>
                              </a>
                            </li>
                            <li className="text-center">
                              <a href="javascript:void(0);">
                                <i className="flaticon-pinterest vertical_middle"></i>
                              </a>
                            </li>
                            <li className="text-center">
                              <a href="javascript:void(0);">
                                <i className="flaticon-instagram-logo vertical_middle"></i>
                              </a>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div className="info_text">
                        <a href="product_list_detail.html">View full info</a>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default ProductDetail;