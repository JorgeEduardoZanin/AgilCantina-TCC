<template>
  <div class="mainHeader px-4 py-3">
    <v-menu>
      <template v-slot:activator="{ props }">
        <v-btn v-bind="props" variant="text"> Criar Conta </v-btn>
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
    <v-menu>
      <template v-slot:activator="{ props }">
        <v-btn v-bind="props" class="login"> Entrar </v-btn>
      </template>
      <v-list>
        <v-list-item
          v-for="(item, index) in itemsLogin"
          :key="index"
          :value="index"
          @click="navigateTo(item.route)"
        >
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>

<script>
export default {
  name: "Header",
  data() {
    return {
      itemsRegister: [
        { title: "Empresa", route: "/register/company" },
        { title: "Usuario", route: "/register/user" },
      ],
      itemsLogin: [
        { title: "Empresa", route: "/login/company" },
        { title: "Usuario", route: "/login/user" },
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
.mainHeader {
  display: flex;
  flex-direction: row;
  justify-content: end;
}
.login {
  background: linear-gradient(to top, #ffa600, #ffd900);
}
</style>
