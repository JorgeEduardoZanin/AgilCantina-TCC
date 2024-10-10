import { createStore } from "vuex";

export default createStore({
  state: {
    token : '',
  },
  getters: {
    isAuthenticated : state => !!state.token,
    getToken: (state) => state.token
  },
  mutations: {
    SET_TOKEN(state,token){
      state.token = token ;
    }
  },
  actions: {
    setToken({commit},token){
      commit('SET_TOKEN',token);
    }
  },
  modules: {},
});
