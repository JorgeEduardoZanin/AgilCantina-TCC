<template>
  <div
    v-if="isLoading"
    class="d-flex justify-center align-center"
    style="height: 90vh"
  >
    <v-progress-circular indeterminate></v-progress-circular>
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
            <v-card-subtitle class="text-wrap my-3">{{
              canteen.description
            }}</v-card-subtitle>
            <v-card-subtitle class="text-wrap my-5">{{
              canteen.opening_hours
            }}</v-card-subtitle>
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
              <v-col class="d-flex justify-center align-center">
                <v-img
                  :src="product.imageUrl"
                  height="100"
                  width="200"
                  class="rounded m-3"
                ></v-img>
              </v-col>
              <v-col class="d-flex flex-column justify-space-between">
                <div>
                  <v-card-title class="text-wrap">{{
                    product.name
                  }}</v-card-title>
                  <v-card-subtitle class="text-wrap my-2">{{
                    product.description
                  }}</v-card-subtitle>
                  <v-card-subtitle class="text-wrap"
                    >R$ {{ product.price }}</v-card-subtitle
                  >
                </div>
                <v-card-actions class="mt-auto">
                  <v-btn color="dark" @click="openConfirmationModal(product)">
                    Adicionar ao Carrinho
                    <v-icon color="amber-darken-2 p-3">bi bi-basket3</v-icon>
                  </v-btn>
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
          <v-btn color="dark" text @click="closeModal">Cancelar</v-btn>
          <v-btn
            color="amber-darken-2"
            variant="elevated"
            text
            @click="confirmAddToCart"
          >
            Adicionar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
import { mapState, mapActions } from "vuex";
import {
  getImageCanteen,
  getImageProduct,
  getProducts,
  getShowCantina,
} from "@/services/HttpService";

export default {
  name: "ProductsCanteen",
  data() {
    return {
      isLoading: true,
      products: [],
      canteen: {},
      isModalOpen: false,
      selectedProduct: null,
      canteen_id: localStorage.getItem("canteen_id"),
      imagemCantina: "",
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

        // Carregar a imagem para cada produto
        for (let product of this.products) {
          const imageUrl = await this.getImageProduct(product.id);
          product.imageUrl = imageUrl;
        }

        console.log(this.products);
      } catch (error) {
        console.error("Erro ao carregar produtos:", error);
      }
    },
    async getImageCanteen() {
      try {
        const response = await getImageCanteen(this.canteen_id);
        let imageUrl = response.data.image_url;

        if (imageUrl) {
          imageUrl = `http://${imageUrl.replace(/\\/g, "")}`;
        }

        this.imagemCantina = imageUrl;
      } catch (error) {
        console.error("Erro ao carregar imagem da cantina:", error);
      }
    },

    async getImageProduct(productId) {
      try {
        const response = await getImageProduct(productId);
        let imageUrl = response.data.image_url;

        // Formatar o URL corretamente
        if (imageUrl) {
          imageUrl = `http://${imageUrl.replace(/\\/g, "")}`; // Prefixo HTTP e corrigir barras
        }

        return imageUrl;
      } catch (error) {
        console.error("Erro ao carregar imagem do produto:", error);
        return null;
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
    this.getImageCanteen();
  },
};
</script>

<style scoped>
* {
  font-family: Inter;
}
</style>
