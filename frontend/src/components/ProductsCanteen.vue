<template>
  <div v-if="isLoading" class="d-flex justify-center align-center">
    <v-progress-circular indeterminate></v-progress-circular>
  </div>
  <v-app v-else>
    <h1>{{ canteenName }}</h1>
    <v-container>
      <v-row>
        <v-col v-for="(product, index) in products" :key="index" cols="12" md="4">
          <v-card>
            <v-card-title>{{ product.name }}</v-card-title>
            <v-card-subtitle>{{ product.description }}</v-card-subtitle>
            <v-card-actions>
              <v-btn color="primary" @click="openConfirmationModal(product)">Adicionar ao Carrinho</v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

    <!-- Modal de Confirmação -->
    <v-dialog :model-value="isModalOpen" max-width="500px" @update:modelValue="closeModal">
      <v-card>
        <v-card-title>Confirmação</v-card-title>
        <v-card-text>
          Tem certeza de que deseja adicionar {{ selectedProduct?.name }} ao carrinho?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="confirmAddToCart">Confirmar</v-btn>
          <v-btn color="red darken-1" text @click="closeModal">Cancelar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
import { mapState, mapActions } from "vuex";
import { getProducts } from "@/services/HttpService";

export default {
  name: "ProductsCanteen",
  data() {
    return {
      isLoading: true,
      products: [],
      isModalOpen: false,
      selectedProduct: null,
    };
  },
  computed: {
    ...mapState(["canteen_id"]),
  },
  methods: {
    ...mapActions(["addToCart"]),
    
    async getProducts() {
      try {
        const response = await getProducts(this.canteen_id);
        this.products = response.data;
      } catch (error) {
        console.error("Erro ao carregar produtos:", error);
      } finally {
        this.isLoading = false;
      }
    },
    openConfirmationModal(product) {
      this.selectedProduct = product;
      this.isModalOpen = true;
    },
    confirmAddToCart() {
      // Adicionando o ID e o nome do produto ao carrinho
      const productToAdd = {
        id: this.selectedProduct.id,
        name: this.selectedProduct.name,
        quantity: 1,
        price: this.selectedProduct.price
      };
      this.addToCart(productToAdd);
      this.closeModal();
    },
    closeModal() {
      this.isModalOpen = false;
      this.selectedProduct = null;
    },
  },
  mounted() {
    this.getProducts();
  },
};
</script>

<style scoped>
/* Adicione estilos conforme necessário */
</style>
