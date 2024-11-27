<template>
  <div>
    <v-snackbar v-model="errorSnackbar" timeout="15000" top color="error">
      {{ errorMensage }}
      <template v-slot:actions>
        <v-btn flat variant="text" @click="errorSnackbar = false"> X </v-btn>
      </template>
    </v-snackbar>

    <v-navigation-drawer v-model="internalDrawer" temporary left :width="500">
      <v-list>
        <v-list-item>
          <v-row class="align-center">
            <v-col cols="auto" class="d-flex align-center">
              <v-icon class="p-4">bi bi-basket3</v-icon>
              <span class="p-2 mt-2">Cestinha</span>
            </v-col>
            <v-col class="d-flex justify-end">
              <v-icon @click="internalDrawer = false" color="red" class="p-4"
                >mdi-close</v-icon
              >
            </v-col>
          </v-row>
        </v-list-item>

        <v-divider></v-divider>

        <div style="max-height: 650px; overflow-y: auto">
          <v-list-item v-for="(item, index) in cartItems" :key="item.id">
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
                  <v-icon color="red" @click="decrementQuantity(index)"
                    >mdi-minus</v-icon
                  >
                  {{ item.quantity }}
                  <v-icon color="green" @click="incrementQuantity(index)"
                    >mdi-plus</v-icon
                  >
                </v-text>
              </v-col>
              <v-col class="text-end p-3">
                <v-icon @click="removeItem(item.id)">
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
            <v-list-item-title class="py-2"
              >Total: R$ {{ totalPrice.toFixed(2) }}</v-list-item-title
            >
          </v-list-item-content>
          <v-btn
            v-if="!paymentLink"
            :disabled="isLoading"
            color="amber-darken-2"
            @click="openConfirmationDialog"
            block
            size="large"
          >
            <v-progress-circular
              v-if="isLoading"
              indeterminate
              color="white"
              size="20"
              class="me-2"
            ></v-progress-circular>
            <template v-else>
              <v-icon class="p-3">mdi-clipboard-check-outline</v-icon>
              Finalizar Compra
            </template>
          </v-btn>
          <v-btn
            v-else
            color="light-blue-darken-1"
            @click="handlePayment"
            block
            size="large"
          >
            <v-icon class="p-3">mdi-credit-card-outline</v-icon>
            Pagar Agora
          </v-btn>
        </v-list-item>
      </v-list>

      <v-dialog v-model="showConfirmationDialog" max-width="500">
        <v-card>
          <v-card-title class="headline">Confirmar Pedido</v-card-title>
          <v-card-text>
            Você tem certeza que deseja finalizar a compra?
          </v-card-text>
          <v-card-text>
            <p>Confira os itens do seu pedido:</p>
            <v-divider></v-divider>
            <v-list dense>
              <v-list-item v-for="item in cartItems" :key="item.id">
                <v-list-item-content>
                  <v-list-item-title>{{ item.name }}</v-list-item-title>
                  <v-list-item-subtitle>
                    Quantidade: {{ item.quantity }} - Total: R$
                    {{ (item.price * item.quantity).toFixed(2) }}
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
            <v-divider></v-divider>
            <p>
              Total da compra: <strong>R$ {{ totalPrice.toFixed(2) }}</strong>
            </p>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="dark" text @click="showConfirmationDialog = false"
              >Cancelar</v-btn
            >
            <v-btn
              text
              @click="confirmCheckout"
              variant="elevated"
              color="amber-darken-2"
              >Finalizar</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-navigation-drawer>
  </div>
</template>

<script>
import router from "@/router";
import { postOrder } from "@/services/HttpService";
import { mapGetters, mapActions } from "vuex";

export default {
  name: "CartDrawer",
  props: {
    drawer: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      isLoading: false,
      internalDrawer: this.drawer,
      paymentLink: null,
      showConfirmationDialog: false,
      errorSnackbar: false,
      errorMensage: "",
    };
  },
  computed: {
    ...mapGetters(["getCart"]),
    cartItems() {
      return this.getCart;
    },
    totalPrice() {
      return this.cartItems.reduce(
        (acc, item) => acc + item.price * item.quantity,
        0
      );
    },
  },
  methods: {
    ...mapActions(["removeFromCart"]),
    incrementQuantity(index) {
      this.cartItems[index].quantity++;
      localStorage.setItem("cart", JSON.stringify(this.cartItems));
    },
    decrementQuantity(index) {
      if (this.cartItems[index].quantity > 1) {
        this.cartItems[index].quantity--;
        localStorage.setItem("cart", JSON.stringify(this.cartItems));
      }
    },
    removeItem(productId) {
      this.removeFromCart(productId);
    },
    openConfirmationDialog() {
      this.showConfirmationDialog = true;
    },
    async confirmCheckout() {
      this.showConfirmationDialog = false;
      this.isLoading = true;
      try {
        await this.newOrder();
      } finally {
        this.isLoading = false;
      }
    },
    clearCart() {
      this.cartItems = [];
      console.log(this.cartItems);
    },
    async handlePayment() {
      try {
        if (this.paymentLink) {
          window.location.href = this.paymentLink;
        } else {
          console.error("Link de pagamento não encontrado");
        }
      } catch (error) {
        this.errorMensage =
          error.response?.data?.message || "Erro ao processar pagamento.";
        this.errorSnackbar = true;
      }
    },
    async newOrder() {
      const orderData = {
        products: this.cartItems.map((item) => ({
          id: item.id,
          quantity: item.quantity,
        })),
      };
      try {
        const canteenId = this.$store.state.canteen_id;
        const response = await postOrder(canteenId, orderData);
        this.paymentLink = response.data.order.payment_link;
      } catch (error) {
        this.errorMensage = error.response.data.message;
        this.errorSnackbar = true;
      }
    },
  },
  watch: {
    drawer(newVal) {
      this.internalDrawer = newVal;
    },
    internalDrawer(newVal) {
      this.$emit("update:drawer", newVal);
    },
  },
};
</script>

<style scoped>
* {
  font-family: inter;
}
</style>
