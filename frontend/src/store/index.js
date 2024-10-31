import { createStore } from "vuex";
import { GetUser } from "../services/HttpService";

export default createStore({
  state: {
    token: localStorage.getItem("token") || "",
    user_id: localStorage.getItem("user_id") || "",
    role_id: "",
    name: "",
    surname: "",
    canteen_id: localStorage.getItem("canteen_id") || "",
    cart: JSON.parse(localStorage.getItem("cart")) || [], 
  },
  getters: {
    isAuthenticated: (state) => !!state.token,
    getToken: (state) => state.token,
    getRoleId: (state) => state.role_id,
    getUserId: (state) => state.user_id,
    getName: (state) => state.name,
    getSurname: (state) => state.surname,
    getCanteenId: (state) => state.canteen_id,
    getCart: (state) => state.cart,
  },
  mutations: {
    SET_TOKEN(state, token) {
      state.token = token;
      localStorage.setItem("token", token);
    },
    SET_ROLE_ID(state, role_id) {
      state.role_id = role_id;
      localStorage.setItem("role_id", role_id);
    },
    SET_USER_ID(state, user_id) {
      state.user_id = user_id;
      localStorage.setItem("user_id", user_id);
    },
    SET_NAME(state, name) {
      state.name = name;
    },
    SET_SURNAME(state, surname) {
      state.surname = surname;
    },
    SET_CANTEEN_ID(state, canteen_id) {
      state.canteen_id = canteen_id;
      localStorage.setItem("canteen_id", canteen_id);
    },
    AUTH(state, userData) {
      state.token = userData.token;
      state.user_id = userData.user_id;
      state.role_id = userData.role_id;
      state.name = userData.name;
      state.surname = userData.surname;
    },
    CLEAR_AUTH_DATA(state) {
      state.token = "";
      state.user_id = "";
      state.role_id = "";
      state.name = "";
      state.surname = "";
      localStorage.removeItem("token");
      localStorage.removeItem("user_id");
      localStorage.removeItem("role_id");
    },
    ADD_TO_CART(state, product) {
      const existingProduct = state.cart.find(item => item.id === product.id);
      if (existingProduct) {
        existingProduct.quantity += 1; // Incrementa a quantidade
      } else {
        state.cart.push({ ...product, quantity: 1 }); // Adiciona o novo produto com quantidade 1
      }
      localStorage.setItem("cart", JSON.stringify(state.cart)); // Atualiza o localStorage
    },
    REMOVE_FROM_CART(state, productId) {
      state.cart = state.cart.filter(item => item.id !== productId);
      localStorage.setItem("cart", JSON.stringify(state.cart)); // Atualiza o localStorage
    },
    CLEAR_CART(state) {
      state.cart = [];
      localStorage.removeItem("cart"); // Remove o carrinho do localStorage
    },
  },
  actions: {
    setToken({ commit }, token) {
      commit("SET_TOKEN", token);
    },
    setRoleId({ commit }, role_id) {
      commit("SET_ROLE_ID", role_id);
    },
    setUserId({ commit }, user_id) {
      commit("SET_USER_ID", user_id);
    },
    setName({ commit }, name) {
      commit("SET_NAME", name);
    },
    setSurname({ commit }, surname) {
      commit("SET_SURNAME", surname);
    },
    setCanteenId({ commit }, id) {
      commit("SET_CANTEEN_ID", id);
    },
    clearAuthData({ commit }) {
      commit("CLEAR_AUTH_DATA");
    },
    addToCart({ commit }, product) {
      commit("ADD_TO_CART", product);
    },
    removeFromCart({ commit }, productId) {
      commit("REMOVE_FROM_CART", productId);
    },
    clearCart({ commit }) {
      commit("CLEAR_CART");
    },
  },
  modules: {},
});
