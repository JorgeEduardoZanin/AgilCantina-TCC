<template>
  <v-data-table
    :headers="headers"
    :items="orders"
    :sort-by="[{ key: 'id', order: 'asc' }]"
    class="p-1"
  >
    <template v-slot:top>
      <v-toolbar color="amber-accent-4">
        <v-toolbar-title class="d-flex align-center">
          <v-icon class="mr-2">mdi-order-bool-descending-variant</v-icon>
          Pedidos em Aberto
        </v-toolbar-title>
        <v-spacer></v-spacer>

        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ props }">
            <v-btn v-bind="props" outlined>
              <v-icon class="px-3">mdi-history</v-icon>Historico
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="text-h5 px-2">
                <v-icon class="px-3">mdi-history</v-icon>Historico
              </span>
            </v-card-title>
            <v-card-text>
              <v-container>
                COLOQUE AS INFORMAçoes AQUI
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="error" variant="text" @click="close">Fechar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-dialog v-model="dialogInfo" max-width="500px">
          <v-card>
            <v-card-title class="text-h5"
              ><v-icon class="px-4">mdi-information-outline</v-icon>Detalhes do
              Pedido</v-card-title
            >
            <v-card-text>
              <v-container>
                <p><strong>ID do Pedido:</strong> {{ selectedItem.id }}</p>
                <p>
                  <strong>Valor Total:</strong> R$
                  {{ selectedItem.total_price }}
                </p>
                <p>
                  <strong>Status do Pagamento:</strong>
                  {{ selectedItem.payment_status }}
                </p>
                <p>
                  <strong>Código de Retirada:</strong>
                  {{ selectedItem.withdrawal_code }}
                </p>
                <p>
                  <strong>Validade do Código:</strong>
                  {{ selectedItem.validity_code }}
                </p>
                <p>
                  <strong>Data de Retirada:</strong>
                  {{ selectedItem.withdrawal_at || "Não retirado" }}
                </p>
                <p>
                  <strong>Pedido Realizado Em:</strong>
                  {{ selectedItem.order_placed_in }}
                </p>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="error" variant="text" @click="closeInfo"
                >Fechar</v-btn
              >
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>

    <template v-slot:item.actions="{ item }">
      <v-icon class="me-2" size="small" @click="showDetails(item)"
        >mdi-information-outline</v-icon
      >
    </template>
  </v-data-table>
</template>

<script>
import { getOpenOrders } from "@/services/HttpService";

export default {
  data: () => ({
    dialog: false,
    dialogDelete: false,
    dialogInfo: false,
    headers: [
      { title: "Numero do Pedido", align: "start", sortable: false, key: "id" },
      { title: "Produtos Pedidos", key: "products" },
      { title: "Valor Total(R$)", key: "total_price" },
      { title: "Status de pagamento", key: "payment_status" },
      { title: "Codigo de Retirada", key: "withdrawal_code" },
      { title: "", key: "actions", sortable: false },
    ],
    orders: [],
    selectedItem: {},
  }),

  created() {
    this.getOpenOrders();
  },

  methods: {
    async getOpenOrders() {
      try {
        const response = await getOpenOrders();
        console.log(response);
        this.orders = response.data.map((order) => ({
          id: order.id,
          created_at: order.created_at,
          updated_at: order.updated_at,
          total_price: order.total_price,
          products: order.products,
          status: order.status,
          withdrawal_code: order.withdrawal_code,
          payment_status: order.payment_status,
          validity_code: order.validity_code,
          withdrawal_at: order.withdrawal_at,
          order_placed_in: order.order_placed_in,
        }));
      } catch (error) {
        console.error("Erro ao obter produtos:", error);
      }
    },
    async getCloseOrders() {
      try {
        const response = await getOpenOrders();
        this.orders = response.data.map((order) => ({
          id: order.id,
          created_at: order.created_at,
          updated_at: order.updated_at,
          total_price: order.total_price,
          status: order.status,
          withdrawal_code: order.withdrawal_code,
          payment_status: order.payment_status,
          validity_code: order.validity_code,
          withdrawal_at: order.withdrawal_at,
          order_placed_in: order.order_placed_in,
        }));
      } catch (error) {
        console.error("Erro ao obter produtos:", error);
      }
    },
    showDetails(item) {
      this.selectedItem = item;
      this.dialogInfo = true;
    },
    close() {
      this.dialog = false;
    },
    closeInfo() {
      this.dialogInfo = false;
    },
  },
};
</script>

<style scoped>
* {
  font-family: Inter;
}
</style>
