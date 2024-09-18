import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/Home.vue'),
      meta: {title: 'Home'}
    },
    {
      path: '/login/user',
      name: 'LoginUser',
      component: () => import('../views/Login.vue'),
      meta: {title: 'Login'}
    },
    {
      path: '/login/company',
      name: 'LoginCompany',
      component: () => import('../views/LoginCompany.vue'),
      meta: {title: 'Login Empresa'}
    },
    {
      path: '/register/user',
      name: 'RegisterUser',
      component: () => import ('../views/Register.vue'),
      meta: {title: 'Cadastro Usuario'}
    },
    {
      path: '/register/company',
      name: 'RegisterCompanyUser',
      component: () => import ('../views/RegisterCompany.vue'),
      meta: {title: 'Cadastro Cantina'}
    },

    {
      path: '/forget_password',
      name: 'ForgetPassword',
      component: () => import ('../views/ForgetPassword.vue'),
      meta: {title: 'Recuperar Senha'}
    },

    {
      path: '/dashboard',
      name: 'Dashboard',
      component: () => import ('../views/Dashboard.vue'),
      meta: {title: 'Dashboard'}
    },
    {
      path: '/dashboard/profile',
      name: 'Profile',
      component: () => import ('../views/DashboardProfile.vue'),
      meta: {title: 'Profile'}
    },
    {
      path: '/dashboard/menu',
      name: 'Menu',
      component: () => import ('../views/DashboardMenu.vue'),
      meta: {title: 'Menu'}
    },
    {
      path: '/dashboard/pedidos',
      name: 'Pedidos',
      component: () => import ('../views/DashboardOrder.vue'),
      meta: {title: 'Pedidos'}
    },
    
  ]
})

router.beforeEach((to, from, next) => {
  document.title = to.meta.title;
  next();
});

export default router
