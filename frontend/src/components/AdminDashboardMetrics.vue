<template>
  <v-container>
    <h1 class="title p-4">Dashboard</h1>
    <v-row>
      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Total de Vendas Mensais</v-card-title>
          <v-card-text class="text-h4">R$ {{ totalVendasMensais }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Total de Lucro Mensal</v-card-title>
          <v-card-text class="text-h4">R$ {{ totalLucroMensal }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Lucro Diário</v-card-title>
          <v-card-text class="text-h4">R$ {{ lucroDiario }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Vendas do Dia</v-card-title>
          <v-card-text class="text-h4">{{ vendasDia }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Total de Vendas</v-card-title>
          <v-card-text class="text-h4">{{ totalVendas }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Lucro Total</v-card-title>
          <v-card-text class="text-h4">R$ {{ lucroTotal }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Despesas Totais Mensais</v-card-title>
          <v-card-text class="text-h4"
            >R$ {{ despesasTotaisMensais }}</v-card-text
          >
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Média de Vendas</v-card-title>
          <v-card-text class="text-h4">{{ mediaVendas }}</v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      totalVendasMensais: 5000,
      totalLucroMensal: 1500,
      lucroDiario: 50,
      vendasDia: 20,
      totalVendas: 15000,
      lucroTotal: 4500,
      despesasTotaisMensais: 1000,
      mediaVendas: 100,
      mesReferencia: "Novembro",
      produtoMaisVendido: "Produto X",
    };
  },
};
</script>

<style scoped>
.v-card-title {
  font-weight: bold;
}
* {
  font-family: Inter;
}
</style>
