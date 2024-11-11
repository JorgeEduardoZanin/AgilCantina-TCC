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

        <!-- <v-text-field
          :loading="loading"
          append-inner-icon="mdi-magnify"
          density="compact"
          label="Pesquisar"
          variant="underlined"
          hide-details
          single-line
          @click:append-inner="onClick"
        ></v-text-field> -->

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
            <v-btn v-bind="props">
              <v-icon>bi bi-person-plus</v-icon>
              <span>Criar Conta</span>
            </v-btn>
          </template>
          <v-list>
            <v-list-item
              v-for="(item, index) in itemsRegister"
              :key="index"
              :value="index"
              @click="navigateTo(item.route)"
            >
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>

        <v-btn value="Login" class="login" to="/login">
          <v-icon>bi bi-box-arrow-in-right</v-icon>
          <span>ENTRAR</span>
        </v-btn>

      </v-bottom-navigation>
    </v-app-bar>
  </div>
</template>

<script>
import logo from "../assets/logos/agil-cantina-letras-pretas.png";

export default {
  name: "Header",
  data() {
    return {
      logo,
      itemsRegister: [
        { title: "Cantina", route: "/register/company" },
        { title: "Usuario", route: "/register/user" },
      ],
    };
  },
  methods: {
    navigateTo(route) {
      this.$router.push(route);
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