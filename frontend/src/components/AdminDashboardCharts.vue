<template>
  <v-container>
    <v-row>
      <v-col cols="6" md="6">
        <v-card outlined>
          <v-card-title>Total de Vendas Mensais</v-card-title>
          <v-card-text>
            <line-chart :chart-data="chartData.totalVendasMensais" />
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="6" md="6">
        <v-card outlined>
          <v-card-title>Total de Lucro Mensal</v-card-title>
          <v-card-text>
            <line-chart :chart-data="chartData.totalLucroMensal" />
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="6" md="6">
        <v-card outlined>
          <v-card-title>Lucro Diário</v-card-title>
          <v-card-text>
            <line-chart :chart-data="chartData.lucroDiario" />
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="6" md="6">
        <v-card outlined>
          <v-card-title>Vendas do Dia</v-card-title>
          <v-card-text>
            <line-chart :chart-data="chartData.vendasDia" />
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { defineComponent, h } from "vue";
import { Line } from "vue-chartjs";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement,
} from "chart.js";

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement
);

const LineChart = defineComponent({
  name: "LineChart",
  components: { Line },
  props: {
    chartData: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const options = {
      responsive: true,
      maintainAspectRatio: false,
    };
    return () => h(Line, { data: props.chartData, options });
  },
});

export default {
  components: {
    LineChart,
  },
  data() {
    return {
      chartData: {
        totalVendasMensais: {
          labels: [
            "Jan",
            "Fev",
            "Mar",
            "Abr",
            "Mai",
            "Jun",
            "Jul",
            "Ago",
            "Set",
            "Out",
            "Nov",
            "Dez",
          ],
          datasets: [
            {
              label: "Total Vendas Mensais",
              data: [500, 600,1000, 750, 1100,2600,5000],
              backgroundColor: "#42A5F5",
              borderColor: "#1E88E5",
              fill: false,
            },
          ],
        },
        totalLucroMensal: {
          labels: [
            "Jan",
            "Fev",
            "Mar",
            "Abr",
            "Mai",
            "Jun",
            "Jul",
            "Ago",
            "Set",
            "Out",
            "Nov",
            "Dez",
          ],
          datasets: [
            {
              label: "Total Lucro Mensal",
              data: [200, 300, 250, 400, 500, 1000,850,1500],
              backgroundColor: "#66BB6A",
              borderColor: "#43A047",
              fill: false,
            },
          ],
        },
        lucroDiario: {
          labels: [
            "Dia 1",
            "Dia 2",
            "Dia 3",
            "Dia 4",
            "Dia 5",
            "Dia 6",
            "Dia 7",
          ],
          datasets: [
            {
              label: "Lucro Diário",
              data: [10, 20, 15, 75, 50],
              backgroundColor: "#FFCA28",
              borderColor: "#FBC02D",
              fill: false,
            },
          ],
        },
        vendasDia: {
          labels: [
            "Dia 1",
            "Dia 2",
            "Dia 3",
            "Dia 4",
            "Dia 5",
            "Dia 6",
            "Dia 7",
          ],
          datasets: [
            {
              label: "Vendas do Dia",
              data: [5, 18, 30, 25, 20],
              backgroundColor: "#AB47BC",
              borderColor: "#8E24AA",
              fill: false,
            },
          ],
        },
      },
    };
  },
};
</script>

<style scoped>
.v-card-title {
  font-weight: bold;
}
.v-card-text {
  height: 200px;
}
</style>
