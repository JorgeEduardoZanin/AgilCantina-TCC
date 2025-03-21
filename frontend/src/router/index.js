import { createRouter, createWebHistory } from "vue-router";
import store from "@/store/index";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      redirect: "/home",
    },
    {
      path: "/home",
      name: "home",
      component: () => import("../views/Home.vue"),
      meta: { title: "Home" },
    },
    {
      path: "/login",
      name: "Login",
      component: () => import("../views/Login.vue"),
      meta: { title: "Login" },
    },
    {
      path: "/register/user",
      name: "RegisterUser",
      component: () => import("../views/Register.vue"),
      meta: { title: "Cadastro Usuario" },
    },
    {
      path: "/register/company",
      name: "RegisterCompanyUser",
      component: () => import("../views/RegisterCompany.vue"),
      meta: { title: "Cadastro Cantina" },
    },
    {
      path: "/forget_password",
      name: "ForgetPassword",
      component: () => import("../views/ForgetPassword.vue"),
      meta: { title: "Recuperar Senha" },
    },
    {
      path: "/reset_password/:token",
      name: "ResetPassword",
      component: () => import("../views/ResetPassword.vue"),
      meta: { title: "Recuperar Senha" },
    },
    {
      path: "/dashboard",
      name: "Dashboard",
      component: () => import("../views/Dashboard.vue"),
      meta: { title: "Dashboard", requiresAuth: true },
    },
    {
      path: "/dashboard/profile",
      name: "Profile",
      component: () => import("../views/DashboardProfile.vue"),
      meta: { title: "Profile", requiresAuth: true },
    },
    {
      path: "/dashboard/menu",
      name: "Menu",
      component: () => import("../views/DashboardMenu.vue"),
      meta: { title: "Menu", requiresAuth: true },
    },
    {
      path: "/dashboard/order",
      name: "Pedidos",
      component: () => import("../views/DashboardOrder.vue"),
      meta: { title: "Pedidos", requiresAuth: true },
    },
    {
      path: "/auth",
      name: "AgilCantina",
      component: () => import("../views/HomeAuth.vue"),
      meta: { title: "AgilCantina", requiresAuth: true },
    },
    {
      path: "/cantina/:name",
      name: "Cantina",
      component: () => import("../views/CanteenPage.vue"),
      beforeEnter: (to, from, next) => {
        const cantinaName = decodeURIComponent(to.params.name);
        document.title = `${cantinaName}`;
        next();
      }
    },
    {
      path: "/status",
      name: "Status",
      component: () => import("../views/Status.vue"),
      meta: { title: "AgilCantina", requiresAuth: true },
    },
    {
      path: "/admin/dashboard",
      name: "Dashboard Admin",
      component: () => import("../views/AdminDashboard.vue"),
      meta: { title: "Controle de Cantinas",requiresAuth: true},
    },
    {
      path: "/cantinas",
      name: "Cantinas",
      component: () => import("../views/Canteens.vue"),
      meta: { title: "Cantinas ",requiresAuth: true},
    },
    {
      path: "/ajuda",
      name: "Ajuda",
      component: () => import("../views/Help.vue"),
      meta: { title: "Cantinas ",requiresAuth: true},
    },
  ],
});

router.beforeEach((to, from, next) => {
  document.title = to.meta.title;

  const token = store.state.token || localStorage.getItem("token");
  const roleId = store.state.role_id || localStorage.getItem("role_id");

  if (to.meta.requiresAuth && !token) {
    next({ name: "Login" });
  } else if (roleId) {
    if (roleId === "3") {
      if (to.path.startsWith("/dashboard")) {
        next({ name: "AgilCantina" });
      } else {
        next();
      }
    } else if (roleId === "1") {
      if (to.path === "/auth") {
        next({ name: "Dashboard" });
      } else {
        next();
      }
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
