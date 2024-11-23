<template>
    <v-container>
      <div class="d-flex justify-space-between align-center mb-4">
        <h1 class="title">Dashboard</h1>
        <div>
          <v-btn class="mr-2" color="primary" @click="updateData">
            Atualizar Dados
          </v-btn>
          <v-btn class="mr-2" color="success" @click="downloadMonthlyPDF">
            Gerar PDF Mensal
          </v-btn>
          <v-btn color="success" @click="downloadAnnualPDF">
            Gerar PDF Anual
          </v-btn>
        </div>
      </div>
  
      <v-row>
        <!-- Dados Mensais -->
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
            <v-card-title>Média de Vendas Mensais</v-card-title>
            <v-card-text class="text-h4">R$ {{ mediaVendasMensais }}</v-card-text>
          </v-card>
        </v-col>
        <v-col cols="12" md="3">
          <v-card outlined>
            <v-card-title>Produto mais vendido do mês</v-card-title>
            <v-card-text class="text-h4">{{ produtoMaisVendidoMensal }}</v-card-text>
          </v-card>
        </v-col>
  
        <!-- Dados Diários -->
        <v-col cols="12" md="3">
          <v-card outlined>
            <v-card-title>Total de Vendas Diárias</v-card-title>
            <v-card-text class="text-h4">R$ {{ totalVendasDiaria }}</v-card-text>
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
            <v-card-title>Média de Vendas Diárias</v-card-title>
            <v-card-text class="text-h4">R$ {{ mediaVendasDiaria }}</v-card-text>
          </v-card>
        </v-col>
        <v-col cols="12" md="3">
          <v-card outlined>
            <v-card-title>Produto mais vendido do dia</v-card-title>
            <v-card-text class="text-h4">{{ produtoMaisVendidoDiario }}</v-card-text>
          </v-card>
        </v-col>
  
        <!-- Dados Anuais -->
        <v-col cols="12" md="3">
          <v-card outlined>
            <v-card-title>Vendas Totais do Ano</v-card-title>
            <v-card-text class="text-h4">R$ {{ totalVendasAnuais }}</v-card-text>
          </v-card>
        </v-col>
        <v-col cols="12" md="3">
          <v-card outlined>
            <v-card-title>Lucro Anual</v-card-title>
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
            <v-card-title>Produto mais vendido do ano</v-card-title>
            <v-card-text class="text-h4">{{ produtoMaisVendidoAnual }}</v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </template>
  
  <script>
  import {
    postMonthManagement,
    postDailyManagement,
    postAnnualManagement,
    getMonthManagement,
    getAnnualManagement,
    getDailyManagement,
    getMonthManagementPDF,
    getAnnualManagementPDF,
  } from "@/services/HttpService";
  
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
              // Buscar os dados do banco de dados
              const [monthData, annualData, dailyData] = await Promise.all([
                  getMonthManagement(),
                  getAnnualManagement(),
                  getDailyManagement(),
              ]);
  
              // Mensais
              const monthManagement = monthData.data.$management;
              this.totalVendasMensais = monthManagement.total_monthly_sales || 0;
              this.totalLucroMensal = monthManagement.monthly_profit || 0;
              this.mediaVendasMensais = monthManagement.average_value_of_monthly_sales || 0;
              this.produtoMaisVendidoMensal = monthManagement.monthly_best_seling_product || null;
  
              // Anuais
              const annualManagement = annualData.data.$management;
              this.totalVendasAnuais = annualManagement.total_sales_for_the_year || 0;
              this.totalLucroAnual = annualManagement.annual_profit || 0;
              this.mediaVendasAnuais = annualManagement.average_value_of_annual_sales || 0;
              this.produtoMaisVendidoAnual = annualManagement.annual_best_seling_product || null;
  
              // Diários
              const dailyManagement = dailyData.data.$management;
              this.lucroDiario = dailyManagement.day_profit || 0;
              this.totalVendasDiaria = dailyManagement.total_sales_for_the_day || 0;
              this.mediaVendasDiaria = dailyManagement.average_value_of_day_sales || 0;
              this.produtoMaisVendidoDiario = dailyManagement.day_best_seling_product || null;
  
              console.log("Dados buscados com sucesso!");
          } catch (error) {
              console.error("Erro ao buscar os dados:", error);
          }
      },
      async updateData() {
          try {
              // Atualizar os dados no banco de dados
              await Promise.all([
                  postMonthManagement(),
                  postAnnualManagement(),
                  postDailyManagement(),
              ]);
  
              console.log("Dados atualizados no banco de dados!");
  
              // Buscar os dados atualizados do banco de dados
              await this.fetchData();
          } catch (error) {
              console.error("Erro ao atualizar os dados:", error);
          }
      },
      async downloadMonthlyPDF() {
          try {
              const response = await getMonthManagementPDF();
              const blob = new Blob([response.data], { type: "application/pdf" });
              const link = document.createElement("a");
              link.href = window.URL.createObjectURL(blob);
              link.download = "relatorio-mensal.pdf";
              link.click();
          } catch (error) {
              console.error("Erro ao gerar PDF mensal:", error);
          }
      },
      async downloadAnnualPDF() {
          try {
              const response = await getAnnualManagementPDF();
              const blob = new Blob([response.data], { type: "application/pdf" });
              const link = document.createElement("a");
              link.href = window.URL.createObjectURL(blob);
              link.download = "relatorio-anual.pdf";
              link.click();
          } catch (error) {
              console.error("Erro ao gerar PDF anual:", error);
          }
      },
  },
  created() {
      this.fetchData(); // Buscar dados ao criar o componente
  },
  };
  </script>
  
  
  <style scoped>
  .v-card-title {
    font-weight: bold;
  }
  .title {
    font-family: Inter, sans-serif;
  }
  </style>
  