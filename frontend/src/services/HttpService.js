import axios from "axios";
import router from "@/router";

const HttpService = axios.create({
  baseURL: "http://localhost:8000/api/",
  headers: {
    "Content-type": "application/json",
  },
});

HttpService.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.data.message === "Token has expired") {
      router.push({ name: 'Login' });
    }
    console.log("Interceptor executado");
    return Promise.reject(error);
  }
);

export const createUser= async(user) =>{
  return await HttpService.post('users',user)
}
export const createCompanyUser = async(user) =>{
  return await HttpService.post('canteens',user)
}
export const loginUser = async(user) =>{
  return await HttpService.post('login',user)
}
export const loginUserCompany = async(user) =>{
  return await HttpService.post('',user)
}
export const forgetPassword = async(user) =>{
  return await HttpService.post('forget-password',user)
}
export const resetPassword = async(user) =>{
  return await HttpService.post('reset-password',user)
}
export const postImageUser = async(image) =>{
  return await HttpService.post('/profile/upload-image',formData,{headers:{'Content-Type':'multipart/form-data'}})
}
export const getImageUser = async() =>{
  return await HttpService.get('/profile/image')
}
export const GetUser = async(id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`users/${id}`,{headers:{Authorization : `Bearer ${token}`}})
}
export const createProduct= async(id,product) =>{
  const token = localStorage.getItem('token');
  return await HttpService.post(`cantinas/${id}/products`,product,{headers:{Authorization : `Bearer ${token}`}})
}
export const getProducts= async(id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`cantinas/${id}/products`,{headers:{Authorization : `Bearer ${token}`}})
}
export const getOpenOrders= async() =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`ordersNotCompleteCanteen`,{headers:{Authorization : `Bearer ${token}`}})
}
export const getClosedOrders= async() =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`ordersNotCompleteCanteen`,{headers:{Authorization : `Bearer ${token}`}})
}
export const deleteProduct= async(id,product_id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.delete(`cantinas/${id}/products/${product_id}`,{headers:{Authorization : `Bearer ${token}`}})
}
export const editProduct= async(id,product_id,product) =>{
  const token = localStorage.getItem('token');
  return await HttpService.put(`cantinas/${id}/products/${product_id}`,product,{headers:{Authorization : `Bearer ${token}`}})
}
export const getCantinas= async() =>{
  return await HttpService.get(`canteens`)
}
export const getShowCantina = async(id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`canteens/${id}`,{headers:{Authorization : `Bearer ${token}`}})
}
export const postOrder = async(id,order) =>{
  const token = localStorage.getItem('token');
  return await HttpService.post(`cantinas/${id}/orders`,order,{headers:{Authorization : `Bearer ${token}`}})
}
export const updateCompanyProfile = async(id,updatedProfile) =>{
  const token = localStorage.getItem('token');
  return await HttpService.put(`canteens/${id}`,order,{headers:{Authorization : `Bearer ${token}`}})
}
export const getCompanyProfile = async(id) =>{
  return await HttpService.put(`canteens/${id}`)
}

export default HttpService;