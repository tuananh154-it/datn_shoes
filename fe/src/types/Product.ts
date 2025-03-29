export interface Product{
  id:string,
  name:string,
  price: string| number;
  image:string,
  description:string,
  category:string,
  brand:string,
}
export interface Productyeuthich{
  id:number,
  name:string,
  price: string| number;
  image:string,
  description:string,
  category:string,
  brand:string,
}

export interface Products {
  id: string;
  name: string;
  image: string;
  brand:string,
  price: string;
  description: string;
  details: Detail[];
}

export interface Detail {
  id: string;
  // name: string;
  color:string,
  default_price: number;
  discount_price:number,
  description: string;
  image: string;
  quantity:number,
  size:number
}