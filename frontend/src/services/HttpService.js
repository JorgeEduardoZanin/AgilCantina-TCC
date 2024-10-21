import axios from "axios";

const HttpService = axios.create({
  baseURL: "http://localhost:8000/api/",
  headers: {
    "Content-type": "application/json",
  },
});

export const createUser= async(user) =>{
  return await HttpService.post('register-user',user)
}
export const createCompanyUser = async(user) =>{
  return await HttpService.post('register-cantinas',user)
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
export const GetUser = async(id) =>{
  return await HttpService.get(`register-user/${id}`)
}

export const createProduct= async(id,product) =>{
  const token = localStorage.getItem('token');
  return await HttpService.post(`cantinas/${id}/products`,product,{headers:{Authorization : `Bearer ${token}`}})
}

export const getProducts= async(id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`cantinas/${id}/products`,{headers:{Authorization : `Bearer ${token}`}})
}

export const deleteProduct= async(id,product_id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.delete(`cantinas/${id}/products/${product_id}`,{headers:{Authorization : `Bearer ${token}`}})
}

export const editProduct= async(id,product_id,product) =>{
  const token = localStorage.getItem('token');
  return await HttpService.put(`cantinas/${id}/products/${product_id}`,product,{headers:{Authorization : `Bearer ${token}`}})
}


export default HttpService;

