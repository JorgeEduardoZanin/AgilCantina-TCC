import { createStore } from "vuex";
import { GetUser } from "../services/HttpService";

export default createStore({
  state: {
    token: "",
    user_id: localStorage.getItem('user_id')  || '',
    role_id: "",
    name: "",
    surname: "",
  },
  getters: {
    isAuthenticated: (state) => !!state.token,
    getToken: (state) => state.token,
    getRoleId: (state) => state.role_id,
    getUserId: (state) => state.user_id,
    getName: (state) => state.name,
    getSurname: (state) => state.surname,
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
    AUTH(state,userData){
      state.token = userData.token;
      state.user_id = userData.user_id;
      state.role_id = userData.role_id;
      state.name = userData.name;
      state.surname = userData.surname;
    },
  },
  actions: {
    async auth({commit}) {
      try {
        const user_id = localStorage.getItem("user_id");
        const response = await GetUser(user_id);
        const userData = response.data;

        console.log("executei");
        commit("AUTH",userData)

        console.log(response.data);
      } catch (error) {
        console.error("Erro ao obter dados do usuário:", error);
      }
    },
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
  },
  modules: {},
});
