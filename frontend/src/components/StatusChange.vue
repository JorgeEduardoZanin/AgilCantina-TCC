<template>
  <v-container class="d-flex justify-center align-center" style="height: 100vh;">
    
    <v-card class="pa-5 text-center" max-width="400">
      <v-card-title>Status de Pagamento</v-card-title>
      <v-icon
        v-if="status === 'approved'"
        color="green"
        size="48"
      >mdi-check-circle</v-icon>
      <v-icon
        v-else-if="status === 'rejected'"
        color="red"
        size="48"
      >mdi-alert-circle</v-icon>
      <v-icon
        v-else-if="status === 'in_process'"
        color="orange"
        size="48"
      >mdi-clock-outline</v-icon>
      <v-icon
        v-else
        color="grey"
        size="48"
      >mdi-help-circle</v-icon>

      <v-card-subtitle class="py-3">Status: <strong>{{ statusText }}</strong></v-card-subtitle>
      
      <v-card-text>
        <p v-if="status === 'approved'" class="text-success">Sua compra foi aprovada!</p>
        <p v-else-if="status === 'pending'" class="text-warning">Sua compra está pendente.</p>
        <p v-else-if="status === 'rejected'" class="text-error">Sua compra foi rejeitada.</p>
        <p v-else-if="status === 'in_process'" class="text-warning">Sua compra está sendo processada.</p>
        <p v-else class="text-muted">Status desconhecido.</p>
      </v-card-text>
      
      <!-- Exibe as informações do pedido -->
      <v-card-text v-if="order">
        <p><strong>External Reference: </strong>{{ externalReference }}</p>
        <p>Retire seu pedido com o codigo de retirada na cantina escolhida</p>
        <p class="h3 py-4"><strong>Código de Retirada </strong>{{ order.withdrawal_code }}</p>
        <p><strong>Validade: </strong>{{ order.validity_code }}</p>
        
        <v-divider></v-divider>
        
        <p><strong>Produtos:</strong></p>
        <v-list dense>
          <v-list-item v-for="(product, index) in order.products" :key="index">
            <v-list-item-content>
              <v-list-item-title>{{ product.name }}</v-list-item-title>
              <v-list-item-subtitle>{{ product.quantity }} x R$ {{ product.unit_price }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-list>
        
        <p><strong>Total: </strong>R$ {{ order.total_price }}</p>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      status: '',
      externalReference: '',
      order: null  // Variável para armazenar as informações do pedido
    };
  },
  computed: {
    statusText() {
      const statusTexts = {
        approved: "Aprovado",
        pending: "Pendente",
        rejected: "Rejeitado",
        in_process: "Em Processamento",
        unknown: "Desconhecido"
      };
      return statusTexts[this.status] || "Desconhecido";
    }
  },
  mounted() {
    // Pega o status e external_reference da URL
    this.status = this.$route.query.status || 'unknown';
    this.externalReference = this.$route.query.external_reference || 'Não informado';
    
    // Chama o método para buscar as informações do pedido
    this.getOrder();
  },
  methods: {
    async getOrder() {
      try {
        // Simula a resposta do pedido com base no JSON que você forneceu
        const response = {
          order: {
            id: 40,
            products: [
              {
                name: "Coxinha de Frango",
                quantity: 1,
                unit_price: 10
              }
            ],
            total_price: 10,
            withdrawal_code: "4258",
            validity_code: "2024-11-27 23:59:59"
          }
        };
        
        // Atribui os dados do pedido à variável order
        this.order = response.order;
      } catch (error) {
        console.error("Erro ao obter as informações do pedido", error);
      }
    }
  }
};
</script>
