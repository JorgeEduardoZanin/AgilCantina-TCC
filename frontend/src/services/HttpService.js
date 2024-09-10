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
export const loginUser = async(user) =>{
  return await HttpService.post('login',user)
}
export const loginUserCompany = async(user) =>{
  return await HttpService.post('',user) //falta fazer ainda 
}


export default HttpService;

