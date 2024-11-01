// src/components/StatusCompra.vue

<template>
  <v-container class="d-flex justify-center align-center" style="height: 100vh;">
    <v-card class="pa-5 text-center" max-width="400">
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

      <v-card-subtitle>Status: <strong>{{ statusText }}</strong></v-card-subtitle>
      
      <v-card-text>
        <p v-if="status === 'approved'" class="text-success">Sua compra foi aprovada!</p>
        <p v-else-if="status === 'pending'" class="text-warning">Sua compra está pendente.</p>
        <p v-else-if="status === 'rejected'" class="text-error">Sua compra foi rejeitada.</p>
        <p v-else-if="status === 'in_process'" class="text-warning">Sua compra está sendo processada.</p>
        <p v-else class="text-muted">Status desconhecido.</p>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      status: ''
    };
  },
  computed: {
    statusText() {
      // Texto do status para exibir no subtítulo
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
    this.status = this.$route.query.status || 'unknown';
  }
};
</script>
