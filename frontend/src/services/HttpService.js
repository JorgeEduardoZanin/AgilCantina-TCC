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


export default HttpService;

