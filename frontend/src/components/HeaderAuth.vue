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

        <v-btn value="Carrinho" @click="drawer = !drawer">
          <v-icon>bi bi-basket3</v-icon>
          <span>Carrinho ({{ totalItems }})</span>
        </v-btn>

        <v-menu transition="fab-transition">
          <template v-slot:activator="{ props }">
            <v-list-item
              class="user"
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
              @click="
                item.title === 'Sair' ? exitApp() : navigateTo(item.route)
              "
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

    <v-navigation-drawer v-model="drawer" temporary left :width="500">
  <v-list>
    <v-list-item>
      <v-icon class="px-3">bi bi-basket3</v-icon>
      <span class="p-3">Cestinha</span>
    </v-list-item>
    
    <v-divider></v-divider>
    <div style="max-height: 650px; overflow-y: auto;">
      <v-list-item
        v-for="(item, index) in cartItems"
        :key="index"
      >
        <v-row justify="space-between">
          <v-col>
            <v-list-item-title>{{ item.name }}</v-list-item-title>
          </v-col>
          <v-col>
            <v-list-item-title class="text-end">
              R$ {{ (item.price * item.quantity).toFixed(2) }}
            </v-list-item-title>
          </v-col>
        </v-row>

        <v-spacer></v-spacer>

        <v-row>
          <v-col>
            <v-text>
              <v-icon color="red" @click="decrementQuantity(index)">mdi-minus</v-icon>
              {{ item.quantity }}
              <v-icon color="green" @click="incrementQuantity(index)">mdi-plus</v-icon>
            </v-text>
          </v-col>
          <v-col class="text-end p-3">
            <v-icon @click="removeItem(index)">
              <v-icon size="x-small" color="red">mdi-delete</v-icon>
            </v-icon>
          </v-col>
        </v-row>
        <v-divider></v-divider>
      </v-list-item>
    </div>

    <v-divider></v-divider>

    <v-list-item>
      <v-list-item-content>
        <v-list-item-title class="py-2">Total: R$ {{ totalPrice.toFixed(2) }}</v-list-item-title>
      </v-list-item-content>
      <v-btn color="primary" @click="checkout" block size="large">
        Finalizar Compra
      </v-btn>
    </v-list-item>
  </v-list>
</v-navigation-drawer>

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
      drawer: false,
      nome: store.getters.getName,
      sobrenome: store.getters.getSurname,
      itemsRegister: [
        { title: "Configurações", route: "", icon: "mdi-cog" },
        { title: "Ajuda", route: "/ajuda", icon: "mdi-help-circle-outline" },
        { title: "Sair", icon: "bi bi-box-arrow-left" },
      ],
      cartItems: [
        { name: "Item 1", price: 10, quantity: 1 },
        { name: "Item 2", price: 20, quantity: 1 },
        { name: "Item 2", price: 20, quantity: 1 },
        { name: "Item 2", price: 20, quantity: 1 },
        { name: "Item 2", price: 20, quantity: 1 },
        { name: "Item 2", price: 20, quantity: 1 },
        { name: "Item 2", price: 20, quantity: 1 },
        { name: "Item 2", price: 20, quantity: 1 },
        { name: "Item 2", price: 20, quantity: 1 },


      ],
    };
  },
  computed: {
    totalItems() {
      return this.cartItems.reduce((acc, item) => acc + item.quantity, 0);
    },
    totalPrice() {
      return this.cartItems.reduce(
        (acc, item) => acc + item.price * item.quantity,
        0
      );
    },
  },
  methods: {
    navigateTo(route) {
      this.$router.push(route);
    },
    exitApp() {
      store.dispatch("clearAuthData");
      this.$router.push("/");
    },
    updateQuantity(index) {
      const item = this.cartItems[index];
      if (item.quantity < 1) {
        item.quantity = 1;
      }
    },
    incrementQuantity(index) {
      this.cartItems[index].quantity++;
    },
    decrementQuantity(index) {
      if (this.cartItems[index].quantity > 1) {
        this.cartItems[index].quantity--;
      }
    },
    removeItem(index) {
      this.cartItems.splice(index, 1);
    },
    checkout() {
      alert("Compra finalizada! Total: R$ " + this.totalPrice.toFixed(2));
    },
  },
};
</script>

<style scoped>
.header {
  background: #fffdf8;
}
.user {
  background: #ffa600;
}

*::-webkit-scrollbar {
  width: 12px;
  height: 12px;
}

*::-webkit-scrollbar-track {
  background: #f9fafc;
}

*::-webkit-scrollbar-thumb {
  background-color: #ffa600;
  border-radius: 20px;
  border: 3px solid #ffffff;
}
</style>
