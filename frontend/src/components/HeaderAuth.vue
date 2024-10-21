<template>
    <div>
      <v-app-bar scroll-behavior="elevate" class="header">
        <v-bottom-navigation mode="shift" class="header">
          <v-img
            class="mx-5 d-none d-md-flex"
            :src="logo"
            max-height="56"
            max-width="189"
            contain
            to="/"
          ></v-img>
  
          <v-spacer></v-spacer>
  
          <v-text-field
            :loading="loading"
            append-inner-icon="mdi-magnify"
            density="compact"
            label="Pesquisar"
            variant="underlined"
            hide-details
            single-line
            @click:append-inner="onClick"
          ></v-text-field>
  
          <v-spacer></v-spacer>
  
          <v-btn value="Cantinas">
            <v-icon>bi bi-shop-window</v-icon>
            <span>Cantinas</span>
          </v-btn>
  
          <v-btn value="Carrinho">
            <v-icon>bi bi-basket3</v-icon>
            <span>Carrinho</span>
          </v-btn>
  
          <v-menu>
            <template v-slot:activator="{ props }">
              <v-list-item
                v-bind="props"
                prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
                :title="nome + ' ' + sobrenome"
              ></v-list-item>
            </template>
            <v-list>
              <v-list-item
                v-for="(item, index) in itemsRegister"
                :key="index"
                :value="index"
                @click="item.title === 'Sair' ? exitApp() : navigateTo(item.route)"
              >
                <div class="d-flex">
                  <v-icon class="px-4">{{ item.icon }}</v-icon>
                  <v-list-item-title>{{ item.title }}</v-list-item-title>
                </div>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-bottom-navigation>
      </v-app-bar>
    </div>
  </template>
  
  <script>
  import logo from "../assets/logos/agil-cantina-letras-pretas.png";
  import store from "@/store/index";
  
  export default {
    name: "Header",
    data() {
      return {
        logo,
        nome: store.getters.getName,
        sobrenome: store.getters.getSurname,
        itemsRegister: [
          { title: "Configurações", route: "", icon: "mdi-cog" },
          { title: "Ajuda", route: "/ajuda", icon: "mdi-help-circle-outline" },
          { title: "Sair", icon: "bi bi-box-arrow-left"},
        ],
      };
    },
    methods: {
      navigateTo(route) {
        this.$router.push(route);
      },
      exitApp() {
        store.dispatch('clearAuthData');
        
        this.$router.push('/');
      },
    },
  };
  </script>
  
  <style scoped>
  .header {
    background: #fffdf8;
  }
  .login {
    background: #ffa600;
  }

  
  </style>
  