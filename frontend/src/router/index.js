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
      name: 'Login',
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
      path: '/registro',
      name: 'Registro',
      component: () => import ('../views/Register.vue'),
      meta: {title: 'Registro'}
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
