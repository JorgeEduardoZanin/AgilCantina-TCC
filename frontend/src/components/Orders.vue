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
            <v-btn v-bind="props" outlined
              ><v-icon class="px-3">mdi-history</v-icon>Historico</v-btn
            >
          </template>
          <v-card>
            <v-card-title>
              <span class="text-h5 px-2"
                ><v-icon class="px-3">mdi-history</v-icon>Historico</span
              >
            </v-card-title>

            <v-card-text>
              <v-container>
                <h1>TESTE INFORMACOES</h1>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="error" variant="text" @click="close">Fechar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5">
              Tem certeza que deseja excluir este item?
            </v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="closeDelete"
                >Cancelar</v-btn
              >
              <v-btn
                color="blue-darken-1"
                variant="text"
                @click="deleteItemConfirm"
                >Sim</v-btn
              >
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon class="me-2" size="small" @click="editItem(item)"
        >mdi-pencil</v-icon
      >
    </template>
  </v-data-table>
</template>

<script>
import { getOrders } from "@/services/HttpService";

export default {
  data: () => ({
    dialog: false,
    dialogDelete: false,
    headers: [
      { title: "Numero do Pedido", align: "start", sortable: false, key: "id" },
      { title: "Produtos Pedidos", key: "products" },
      { title: "Valor Total(R$)", key: "price" },
      { title: "Status", key: "status" },
      { title: "Codigo de Retirada", key: "withdrawal_code" },
      { title: "", key: "actions", sortable: false },
    ],
    orders: [],
    defaultItem: {
      id: "",
      products: "",
      price: "",
      status: "",
      withdrawal_code: "",
    },
  }),

  computed: {},

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  created() {
    this.getOpenOrders();
  },

  methods: {
    async getOpenOrders() {
      try {
        const id = localStorage.getItem("user_id");
        const response = await getOrders(id);

        this.orders = response.data.map((order) => ({
          id: order.id,
          products: order.products,
          price: order.total_price,
          status: order.status,
          withdrawal_code: order.withdrawal_code,
        }));
      } catch (error) {
        console.error("Erro ao obter produtos:", error);
      }
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.selectedItem = null;
      });
    },
  },
};
</script>

<style scoped>
* {
  font-family: Inter;
}
</style>
