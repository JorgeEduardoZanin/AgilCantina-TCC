<template>
  <div>
    <v-snackbar v-model="errorSnackbar" timeout="15000" top color="error">
      {{ errorMensage }}
      <template v-slot:actions>
        <v-btn flat variant="text" @click="errorSnackbar = false"> X </v-btn>
      </template>
    </v-snackbar>

    <v-data-table
      :headers="headers"
      :items="orders"
      :sort-by="[{ key: 'id', order: 'asc' }]"
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
              <v-btn v-bind="props" variant="elevated" class="m-2">
                <v-icon class="px-3">mdi-history</v-icon>Historico
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="text-h5 px-2">
                  <v-icon class="px-4">mdi-history</v-icon>Historico
                </span>
              </v-card-title>
              <v-card-text>
                <v-container> Historico AQUI </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn variant="text" @click="close"
                  >Fechar</v-btn
                >
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-dialog v-model="dialogWithdrawal" max-width="500px">
            <template v-slot:activator="{ props }">
              <v-btn v-bind="props" variant="elevated" class="m-2">
                <v-icon class="px-3">mdi-barcode-scan</v-icon>Código de Retirada
              </v-btn>
            </template>
            <v-card>
              <v-card-title class="text-h5">
                <v-icon class="px-4">mdi-barcode-scan</v-icon>Código de Retirada
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-text-field
                    v-model="withdrawalCode"
                    label="Digite o Código de Retirada"
                    variant="underlined"
                    autofocus
                  />
                </v-container>
              </v-card-text>
              <v-card-text v-if="isCodeValidated">
                <v-container>
                  <p><strong>ID do Pedido:</strong> {{ selectedItem.id }}</p>
                  <p>
                    <strong>Valor Total:</strong> R$
                    {{ selectedItem.total_price }}
                  </p>
                  <p><strong>Produtos:</strong></p>
                  <ul>
                    <li
                      v-for="(product, index) in selectedItem.products"
                      :key="index"
                    >
                      {{ product }}
                    </li>
                  </ul>
                </v-container>
              </v-card-text>
              <v-card-text v-else>
                <v-container>
                  <p>
                    Por favor, insira o código de retirada para ver os detalhes
                    do pedido.
                  </p>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn variant="text" @click="closeWithdrawal"
                  >Fechar</v-btn
                >
                <v-btn color="success" variant="elevated" @click="postCode()"
                  >Validar</v-btn
                >
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-dialog v-model="dialogInfo" max-width="500px">
            <v-card>
              <v-card-title class="text-h5"
                ><v-icon class="px-4">mdi-information-outline</v-icon>Detalhes
                do Pedido</v-card-title
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
  </div>
</template>

<script>
import { getOpenOrders, postCheckCode } from "@/services/HttpService";

export default {
  data: () => ({
    dialog: false,
    dialogDelete: false,
    dialogInfo: false,
    dialogWithdrawal: false,
    errorSnackbar: false,
    errorMensage: "",
    isCodeValidated: false,
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
    withdrawalCode: "",
  }),

  created() {
    this.getOpenOrders();
  },

  methods: {
    async getOpenOrders() {
      try {
        const response = await getOpenOrders();
        this.orders = response.data.map((order) => ({
          id: order.id,
          created_at: order.created_at,
          updated_at: order.updated_at,
          total_price: order.total_price,
          products: order.products
            .map((product) => `${product.pivot.quantity} x ${product.name}`)
            .join("\n"),
          status: order.status,
          withdrawal_code: order.withdrawal_code,
          payment_status: order.payment_status,
          validity_code: order.validity_code,
          withdrawal_at: order.withdrawal_at,
          order_placed_in: order.created_at,
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
    async postCode() {
      try {
        const code = this.withdrawalCode;
        const payload = { withdrawal_code: code };
        const response = await postCheckCode(payload);
        this.selectedItem = {
          id: response.data.order.id,
          total_price: response.data.order.total_price,
          products: response.data.order.products.map(
            (product) =>
              `${product.quantity} x ${product.name} - R$${product.unit_price}`
          ),
        };
        this.isCodeValidated = true;
      } catch (error) {
        this.errorMensage = error.response.data.message;
        this.errorSnackbar = true;
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
    closeWithdrawal() {
      this.dialogWithdrawal = false;
    },
  },
};
</script>

<style scoped>
* {
  font-family: Inter;
}
</style>
