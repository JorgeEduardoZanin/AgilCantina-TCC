import axios from "axios";
import router from "@/router";

const HttpService = axios.create({
  baseURL: "http://3.15.225.151:8000/api/",
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
export const postImageUser = async(formData) =>{
  const token = localStorage.getItem('token');
  return await HttpService.post('/profile/uploadImageUser',formData,{headers:{Authorization : `Bearer ${token}`,'Content-Type':'multipart/form-data',}})
}
export const getImageUser = async() =>{
  const token = localStorage.getItem('token');
  return await HttpService.get('/profile/imageUser',{headers:{Authorization : `Bearer ${token}`}})
}
export const GetUser = async(id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`users/${id}`,{headers:{Authorization : `Bearer ${token}`}})
}
export const putUpdateUser = async(id,user) =>{
  const token = localStorage.getItem('token');
  return await HttpService.put(`users/${id}`,user,{headers:{Authorization : `Bearer ${token}`}})
}
export const createProduct= async(product) =>{
  const token = localStorage.getItem('token');
  return await HttpService.post(`products`,product,{headers:{Authorization : `Bearer ${token}`}})
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
  return await HttpService.get(`ordersCompleteCanteen`,{headers:{Authorization : `Bearer ${token}`}})
}
export const deleteProduct= async(product_id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.delete(`deleteProducts/${product_id}`,{headers:{Authorization : `Bearer ${token}`}})
}
export const editProduct= async(product_id,product) =>{
  const token = localStorage.getItem('token');
  return await HttpService.patch(`products/${product_id}`,product,{headers:{Authorization : `Bearer ${token}`}})
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
  return await HttpService.put(`canteens/${id}`,updatedProfile,{headers:{Authorization : `Bearer ${token}`}})
}

export const getCompanyProfile = async(id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`canteens/${id}`,{headers:{Authorization : `Bearer ${token}`}})
}

export const getMonthManagement = async() =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`indexMonthManagement`,{headers:{Authorization : `Bearer ${token}`}})
}

export const getAnnualManagement = async() =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`indexAnnualManagement`,{headers:{Authorization : `Bearer ${token}`}})
}

export const getDailyManagement = async() =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`indexDailyManagement`,{headers:{Authorization : `Bearer ${token}`}})
}

export const postAnnualManagement = async() =>{
  const token = localStorage.getItem('token');
  return await HttpService.post(`annualManagement`,{},{headers:{Authorization : `Bearer ${token}`}})
}

export const postMonthManagement = async() =>{
  const token = localStorage.getItem('token');
  return await HttpService.post(`monthManagement`,{},{headers:{Authorization : `Bearer ${token}`}})
}

export const postDailyManagement = async() =>{
  const token = localStorage.getItem('token');
  return await HttpService.post(`dailyManagement`,{},{headers:{Authorization : `Bearer ${token}`}})
}

export const getAnnualManagementPDF = async () => {
  const token = localStorage.getItem('token');
  return await HttpService.get(`annualManagementPDF`, {
    headers: { Authorization: `Bearer ${token}` },
    responseType: "arraybuffer",
  });
};

export const getMonthManagementPDF = async () => {
  const token = localStorage.getItem('token');
  return await HttpService.get(`monthManagementPDF`, {
    headers: { Authorization: `Bearer ${token}` },
    responseType: "arraybuffer",
  });
};

export const getCanteensPending = async() =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`getPending`,{headers:{Authorization : `Bearer ${token}`}})
}

export const patchAprovedCanteen = async(id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.patch(`cantinas/${id}/approve`,{},{headers:{Authorization : `Bearer ${token}`}})
}

export const patchDesapproveCanteen = async(id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.patch(`cantinas/${id}/desapprove`,{},{headers:{Authorization : `Bearer ${token}`}})
}

export const postCheckCode = async(code) =>{
  const token = localStorage.getItem('token');
  return await HttpService.post(`check_code`,code,{headers:{Authorization : `Bearer ${token}`}})
}

export const postImageCompany = async(formData) =>{
  const token = localStorage.getItem('token');
  return await HttpService.post('/profile/uploadImageCanteen',formData,{headers:{Authorization : `Bearer ${token}`,'Content-Type':'multipart/form-data',}})
}
export const postImageProduct = async(product_id,formData) =>{
  const token = localStorage.getItem('token');
  return await HttpService.post(`/profile/uploadImageProduct/${product_id}`,formData,{headers:{Authorization : `Bearer ${token}`,'Content-Type':'multipart/form-data',}})
}
export const getImageProduct = async(product_id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`/profile/imageProduct/${product_id}`,{headers:{Authorization : `Bearer ${token}`}})
}
export const getImageCanteen = async(canteen_id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`/profile/imageCanteen/${canteen_id}`,{headers:{Authorization : `Bearer ${token}`}})
}

export const getShowOrder = async(id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`orders/${id}`,{headers:{Authorization : `Bearer ${token}`}})
}
export const getTotalSalesOfTheLastMonths = async(id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`totalSalesOfTheLastMonths`,{headers:{Authorization : `Bearer ${token}`}})
}
export const getProfitOfTheLastMonths = async(id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`profitOfTheLastMonths`,{headers:{Authorization : `Bearer ${token}`}})
}
export const getDailyProfitOfTheLastDays = async(id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`dailyProfitOfTheLastDays`,{headers:{Authorization : `Bearer ${token}`}})
}
export const getTotalSalesOfTheLastDays = async(id) =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`totalSalesOfTheLastDays`,{headers:{Authorization : `Bearer ${token}`}})
}
export const getOrdersCompletUser = async() =>{
  const token = localStorage.getItem('token');
  return await HttpService.get(`ordersNotCompleteUser`,{headers:{Authorization : `Bearer ${token}`}})
}

export default HttpService;