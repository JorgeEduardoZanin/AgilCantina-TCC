<template>
  <div v-if="isLoading" class="d-flex justify-center align-center">
  </div>
  <v-app v-else>
    <v-container>
      <div class="my-3">
        <v-row>
          <v-col col="2" md="3">
            <v-img
              :src="imagemCantina"
              height="200"
              class="rounded m-3"
              contain
            ></v-img>
          </v-col>
          <v-col>
            <h3 class="my-4 px-3">{{ canteen.canteen_name }}</h3>
            <v-card-subtitle class="text-wrap my-3">{{ canteen.description }}</v-card-subtitle>
            <v-card-subtitle class="text-wrap my-5">{{ canteen.opening_hours }}</v-card-subtitle>
          </v-col>
        </v-row>
      </div>
      <v-row>
        <v-col
          v-for="(product, index) in products"
          :key="index"
          cols="12"
          md="4"
        >
          <v-card>
            <v-row>
              <v-col>
                <v-img
                  :src="productImage"
                  height="100"
                  class="rounded m-3"
                  contain
                ></v-img>
              </v-col>
              <v-col class="d-flex flex-column justify-space-between">
                <div>
                  <v-card-title class="text-wrap">{{ product.name }}</v-card-title>
                  <v-card-subtitle class="text-wrap my-2">{{ product.description }}</v-card-subtitle>
                  <v-card-subtitle class="text-wrap">R$ {{ product.price }}</v-card-subtitle>
                </div>
                <v-card-actions class="mt-auto">
                  <v-btn color="dark" @click="openConfirmationModal(product)"
                    >Adicionar ao Carrinho<v-icon color="amber-darken-3 p-3"> bi bi-basket3 </v-icon>
                  </v-btn
                  >
                </v-card-actions>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

    <v-dialog
      :model-value="isModalOpen"
      max-width="500px"
      @update:modelValue="closeModal"
    >
      <v-card>
        <v-card-title>Confirmação</v-card-title>
        <v-card-text>
          Tem certeza de que deseja adicionar {{ selectedProduct?.name }} ao
          carrinho?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="confirmAddToCart"
            >Confirmar</v-btn
          >
          <v-btn color="red darken-1" text @click="closeModal">Cancelar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
import { mapState, mapActions } from "vuex";
import { getProducts, getShowCantina } from "@/services/HttpService";
import productImage from "@/assets/Carrousel/product.png";
import imagemCantina from "@/assets/Carrousel/food.png";

export default {
  name: "ProductsCanteen",
  data() {
    return {
      isLoading: true,
      productImage,
      imagemCantina,
      products: [],
      canteen: {},
      isModalOpen: false,
      selectedProduct: null,
      canteen_id: localStorage.getItem("canteen_id"),
    };
  },
  computed: {
    ...mapState(["canteen_id"]),
  },
  methods: {
    ...mapActions(["addToCart"]),

    async getCanteenInfo() {
      try {
        const responseCanteen = await getShowCantina(this.canteen_id);
        this.canteen = responseCanteen.data.cantina;
        await this.getProducts(); 
      } catch (error) {
        console.error("Erro ao carregar as informações da cantina:", error);
      } finally {
        this.isLoading = false;
      }
    },
    async getProducts() {
      try {
        const response = await getProducts(this.canteen_id);
        this.products = response.data;
      } catch (error) {
        console.error("Erro ao carregar produtos:", error);
      }
    },
    openConfirmationModal(product) {
      this.selectedProduct = product;
      this.isModalOpen = true;
    },
    confirmAddToCart() {
      const productToAdd = {
        id: this.selectedProduct.id,
        name: this.selectedProduct.name,
        quantity: 1,
        price: this.selectedProduct.price,
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
    this.getCanteenInfo();
  },
};
</script>

<style scoped>
*{
  font-family: Inter;
}
</style>