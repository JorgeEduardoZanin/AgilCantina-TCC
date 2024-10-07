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
      path: '/login',
      name: 'Login',
      component: () => import('../views/Login.vue'),
      meta: {title: 'Login'}
    },
    {
      path: '/registro',
      name: 'Registro',
      component: () => import ('../views/Register.vue'),
      meta: {title: 'Registro'}
    },
    {
      path: '/payment',
      name: 'Payment',
      component: () => import ('../components/PaymentTest.vue'),
      meta: {title: 'Registro'}
    }
  ]
})

router.beforeEach((to, from, next) => {
  document.title = to.meta.title;
  next();
});

export default router
