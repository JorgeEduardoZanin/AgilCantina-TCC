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
          <v-card-title>Media de vendas mensais</v-card-title>
          <v-card-text class="text-h4">R$ {{ mediaVendasMensais }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Produto mais vendido do mes</v-card-title>
          <v-card-text class="text-h4">{{ produtoMaisVendidoMensal }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Total de vendas diaria</v-card-title>
          <v-card-text class="text-h4">R$ {{ totalVendasDiaria }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Lucro diario</v-card-title>
          <v-card-text class="text-h4">R$ {{ lucroDiario }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Media de vendas diaria</v-card-title>
          <v-card-text class="text-h4">R$ {{ mediaVendasDiaria }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Produto mais vendido do dia </v-card-title>
          <v-card-text class="text-h4">{{ produtoMaisVendidoDiario }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Vendas totais do ano</v-card-title>
          <v-card-text class="text-h4"
            >R$ {{ totalVendasAnuais }}</v-card-text
          >
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Lucro anual</v-card-title>
          <v-card-text class="text-h4">R$ {{ totalLucroAnual }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Média de Vendas Anuais</v-card-title>
          <v-card-text class="text-h4">R$ {{ mediaVendasAnuais }}</v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card outlined>
          <v-card-title>Produto mais vendido do ano </v-card-title>
          <v-card-text class="text-h4">{{ produtoMaisVendidoAnual }}</v-card-text>
        </v-card>
      </v-col>

    </v-row>
  </v-container>
</template>

<script>
import {
  getMonthManagement,
  getAnnualManagement,
  getDailyManagement,
} from "@/services/HttpService"; // Atualize o caminho conforme sua estrutura

export default {
  data() {
    return {
      totalVendasMensais: 0,
      totalLucroMensal: 0,
      mediaVendasMensais: 0,
      produtoMaisVendidoMensal: 0,

      lucroDiario: 0,
      totalVendasDiaria: 0,
      mediaVendasDiaria: 0,
      produtoMaisVendidoDiario: 0,

      totalVendasAnuais: 0,
      totalLucroAnual: 0,
      mediaVendasAnuais: 0,
      produtoMaisVendidoAnual: 0,
    };
  },
  methods: {
    async fetchData() {
      try {
        // Dados mensais
        const monthData = await getMonthManagement();
        const monthManagement = monthData.data.$management;
        this.totalVendasMensais = monthManagement.total_monthly_sales || 0;
        this.totalLucroMensal = monthManagement.monthly_profit || 0;
        this.mediaVendasMensais = monthManagement.average_value_of_monthly_sales || 0;
        this.produtoMaisVendidoMensal = monthManagement.monthly_best_seling_product  || null;

        // Dados anuais
        const annualData = await getAnnualManagement();
        const annualManagement = annualData.data.$management;
        this.totalVendasAnuais = annualManagement.total_sales_for_the_year || 0;
        this.totalLucroAnual = annualManagement.annual_profit || 0;
        this.mediaVendasAnuais = annualManagement.average_value_of_annual_sales || 0;
        this.produtoMaisVendidoAnual = annualManagement.annual_best_seling_product || null;

        // Dados diários
        const dailyData = await getDailyManagement();
        const dailyManagement = dailyData.data.$management;
        this.lucroDiario = dailyManagement.day_profit || 0;
        this.totalVendasDiaria = dailyManagement.total_sales_for_the_day || 0;
        this.mediaVendasDiaria = dailyManagement.average_value_of_day_sales || 0;
        this.produtoMaisVendidoDiario = dailyManagement.day_best_seling_product || null;
      } catch (error) {
        console.error("Erro ao buscar dados:", error);
      }
    },
  },
  created() {
    this.fetchData();
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
