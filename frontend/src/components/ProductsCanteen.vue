<template>
  <div v-if="isLoading" class="d-flex justify-center align-center">
    <v-progress-circular indeterminate></v-progress-circular>
  </div>
  <v-app v-else>
    <h1>{{ canteenName }}</h1>
    <v-list>
      <v-list-item v-for="(product, index) in products" :key="index">
        <v-list-item-content>
          <v-list-item-title>{{ product.name }}</v-list-item-title>
          <v-list-item-subtitle>{{ product.description }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-app>
</template>

<script>
import { getProducts } from "@/services/HttpService";
import { mapState } from "vuex";

export default {
  computed: {
    ...mapState(["canteen_id"]),
  },
  data() {
    return {
      isLoading: true,
      products: [],
    };
  },
  mounted() {
    this.getProducts();
  },
  methods: {
    async getProducts() {
      try {
        const response = await getProducts(this.canteen_id);
        this.products = response;
      } catch (error) {
        console.error("Erro ao carregar produtos:", error);
      } finally {
        this.isLoading = false;
      }
    },
    async getInfoCanteen
  },
};
</script>
