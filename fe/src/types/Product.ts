export interface Product{
  id:string,
  name:string,
  price: string;
  image:string,
  description:string,
  category:string,
  brand:string,
}
export interface Detail {
  id: number;
  image: string;
  size: string;
  color: string;
  quantity: number;
  default_price: string;  // Giá gốc của biến thể
  discount_price: string; // Giá giảm (nếu có)
}

export interface Productyeuthich {
  id: number;
  name: string;
  price: string | number;
  image: string;
  description: string;
  category: string;
  brand: string;
  details: Detail[];
}
export interface Productyeuthich1{
  id:number,
  name:string,
  price: string| number;
  image:string,
  description:string,
  category:string,
  brand:string,
  details: Detail[];
}
export interface Products {
  id: string;
  name: string;
  image: string;
  brand:string,
  price: number;
  description: string;
  details: Detail[];
}