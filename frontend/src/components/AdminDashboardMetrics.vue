<template>
  <div>
    <v-toolbar color="amber-accent-4">
      <v-toolbar-title class="d-flex align-center">
        <v-icon class="mr-2">mdi-chart-box-outline</v-icon>
        Dashboard
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-toolbar-title>
        <v-btn class="mr-2" variant="elevated" @click="updateData">
          <v-icon>mdi-restart</v-icon>
          Atualizar
        </v-btn>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ props }">
            <v-btn v-bind="props" variant="elevated" class="m-2">
              <v-icon class="px-3">mdi-file-pdf-box</v-icon>Gerar PDF
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="text-h5 px-2">
                <v-icon class="px-4">mdi-file-pdf-box</v-icon>Gerar PDF
              </span>
            </v-card-title>
            <v-card-text>
              <v-select
                v-model="pdfType"
                :items="['Mensal', 'Anual']"
                label="Selecione o periodo do PDF"
                variant="underlined"
              ></v-select>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn variant="text" @click="close">Fechar</v-btn>
              <v-btn variant="elevated" color="amber-accent-4" @click="close"
                >Gerar</v-btn
              >
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar-title>
    </v-toolbar>

    <v-row class="p-5">
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
          <v-card-text class="text-h4">{{
            produtoMaisVendidoMensal
          }}</v-card-text>
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
          <v-card-text class="text-h4">{{
            produtoMaisVendidoDiario
          }}</v-card-text>
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
          <v-card-text class="text-h4">{{
            produtoMaisVendidoAnual
          }}</v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
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
      dialog: false,
      pdfType: null,

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
        const [monthData, annualData, dailyData] = await Promise.all([
          getMonthManagement(),
          getAnnualManagement(),
          getDailyManagement(),
        ]);
        const monthManagement = monthData.data.$management;
        this.totalVendasMensais = monthManagement.total_monthly_sales || 0;
        this.totalLucroMensal = monthManagement.monthly_profit || 0;
        this.mediaVendasMensais =
          monthManagement.average_value_of_monthly_sales || 0;
        this.produtoMaisVendidoMensal =
          monthManagement.monthly_best_seling_product || null;

        const annualManagement = annualData.data.$management;
        this.totalVendasAnuais = annualManagement.total_sales_for_the_year || 0;
        this.totalLucroAnual = annualManagement.annual_profit || 0;
        this.mediaVendasAnuais =
          annualManagement.average_value_of_annual_sales || 0;
        this.produtoMaisVendidoAnual =
          annualManagement.annual_best_seling_product || null;

        const dailyManagement = dailyData.data.$management;
        this.lucroDiario = dailyManagement.day_profit || 0;
        this.totalVendasDiaria = dailyManagement.total_sales_for_the_day || 0;
        this.mediaVendasDiaria =
          dailyManagement.average_value_of_day_sales || 0;
        this.produtoMaisVendidoDiario =
          dailyManagement.day_best_seling_product || null;
      } catch (error) {
        console.error("Erro ao buscar os dados:", error);
      }
    },
    async updateData() {
      try {
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
    async generatePDF() {
      try {
        if (this.pdfType === "Mensal") {
          const response = await getMonthManagementPDF();
          this.downloadPDF(response, "relatorio-mensal.pdf");
        } else if (this.pdfType === "Anual") {
          const response = await getAnnualManagementPDF();
          this.downloadPDF(response, "relatorio-anual.pdf");
        }
      } catch (error) {
        console.error(
          `Erro ao gerar PDF ${this.pdfType.toLowerCase()}:`,
          error
        );
      }
    },
    downloadPDF(response, filename) {
      const blob = new Blob([response.data], { type: "application/pdf" });
      const link = document.createElement("a");
      link.href = window.URL.createObjectURL(blob);
      link.download = filename;
      link.click();
    },
    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    },
  },
  created() {
    this.fetchData(); // Buscar dados ao criar o componente
  },
};
</script>

<style scoped>
* {
  font-family: Inter;
}
</style>
