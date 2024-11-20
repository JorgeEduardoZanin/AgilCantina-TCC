<template> 
  <v-navigation-drawer v-model="internalDrawer" temporary left :width="500">
    <v-list>
      <v-list-item>
        <v-icon class="p-4">bi bi-basket3</v-icon>
        <span class="p-1">Cestinha</span>
        <v-spacer></v-spacer>
        <v-icon @click="internalDrawer = false" color="grey" class="p-4">mdi-close</v-icon>
      </v-list-item>

      <v-divider></v-divider>

      <div style="max-height: 650px; overflow-y: auto;">
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
                <v-icon color="red" @click="decrementQuantity(index)">mdi-minus</v-icon>
                {{ item.quantity }}
                <v-icon color="green" @click="incrementQuantity(index)">mdi-plus</v-icon>
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
          <v-list-item-title class="py-2">Total: R$ {{ totalPrice.toFixed(2) }}</v-list-item-title>
        </v-list-item-content>
        <v-btn v-if="!paymentLink" color="amber-accent-4" @click="checkout()" block size="large">
          <v-icon class="p-3">mdi-clipboard-check-outline</v-icon>
          Finalizar Compra
        </v-btn>
        <v-btn v-else color="light-blue-darken-1" :href="paymentLink" target="_blank" block size="large">
          <v-icon class="p-3">mdi-credit-card-outline</v-icon>
          Pagar Agora
        </v-btn>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
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
      internalDrawer: this.drawer,
      paymentLink: null,
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
    async newOrder() {
      const orderData = {
        products: this.cartItems.map(item => ({
          id: item.id,
          quantity: item.quantity,
        }))
      };

      try {
        const canteenId = this.$store.state.canteen_id;
        const response = await postOrder(canteenId, orderData);
        
        this.paymentLink = response.data.order.payment_link;
        
        console.log("Pedido criado com sucesso!");
      } catch (error) {
        console.error("Erro ao carregar dados da cantina:", error);
      }
    },
    checkout() {
      this.newOrder();
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
*{
  font-family: inter;
}
</style>