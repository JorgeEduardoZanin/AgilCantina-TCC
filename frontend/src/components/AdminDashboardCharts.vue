<template>
  <div>
    <div
      v-if="isLoading"
      class="d-flex justify-center align-center"
      style="height: 50vh"
    ><v-progress-circular indeterminate></v-progress-circular></div>

    <v-container v-else>
      <v-row>
        <v-col cols="6" md="6">
          <v-card outlined>
            <v-card-text>
              <line-chart :chart-data="chartData.ProfitOfTheLastMonths" />
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="6" md="6">
          <v-card outlined>
            <v-card-text>
              <line-chart :chart-data="chartData.TotalSalesOfTheLastMonths" />
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="6" md="6">
          <v-card outlined>
            <v-card-text>
              <line-chart :chart-data="chartData.DailyProfitOfTheLastDays" />
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="6" md="6">
          <v-card outlined>
            <v-card-text>
              <line-chart :chart-data="chartData.TotalSalesOfTheLastDays" />
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
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
import {
  getDailyProfitOfTheLastDays,
  getProfitOfTheLastMonths,
  getTotalSalesOfTheLastDays,
  getTotalSalesOfTheLastMonths,
} from "@/services/HttpService";

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
      isLoading: true,
      chartData: {
        ProfitOfTheLastMonths: {
          labels: [],
          datasets: [
            {
              label: "Total de Lucro nos Ultimos Meses",
              data: [],
              backgroundColor: "#FFCA28",
              borderColor: "#FBC02D",
              fill: false,
            },
          ],
        },
        TotalSalesOfTheLastMonths: {
          labels: [],
          datasets: [
            {
              label: "Total de Vendas nos Ultimos Meses",
              data: [],
              backgroundColor: "#FFCA28",
              borderColor: "#FBC02D",
              fill: false,
            },
          ],
        },
        DailyProfitOfTheLastDays: {
          labels: [],
          datasets: [
            {
              label: "Total de Lucro nos Ultimos Dias",
              data: [],
              backgroundColor: "#FFCA28",
              borderColor: "#FBC02D",
              fill: false,
            },
          ],
        },
        TotalSalesOfTheLastDays: {
          labels: [],
          datasets: [
            {
              label: "Total de Vendas nos Ultimos Dias",
              data: [],
              backgroundColor: "#FFCA28",
              borderColor: "#FBC02D",
              fill: false,
            },
          ],
        },
      },
    };
  },

  methods: {
    async getInfo() {
      try {
        const responseProfitMonth = await getProfitOfTheLastMonths();
        const responseTotalSalesMonth = await getTotalSalesOfTheLastMonths();
        const responseProfitDaily = await getDailyProfitOfTheLastDays();
        const responseTotalSalesDaily = await getTotalSalesOfTheLastDays();


        this.chartData.ProfitOfTheLastMonths.labels = responseProfitMonth.data.labels;
        this.chartData.ProfitOfTheLastMonths.datasets[0].data =
          responseProfitMonth.data.data;

        this.chartData.TotalSalesOfTheLastMonths.labels =
          responseTotalSalesMonth.data.labels;
        this.chartData.TotalSalesOfTheLastMonths.datasets[0].data =
          responseTotalSalesMonth.data.data;

        this.chartData.DailyProfitOfTheLastDays.labels = responseProfitDaily.data.labels;
        this.chartData.DailyProfitOfTheLastDays.datasets[0].data = responseProfitDaily.data.data;

        this.chartData.TotalSalesOfTheLastDays.labels = responseTotalSalesDaily.data.labels;
        this.chartData.TotalSalesOfTheLastDays.datasets[0].data =
          responseTotalSalesDaily.data.data;
      } catch (error) {
        console.error("Erro ao buscar dados:", error);
      } finally {
       this.isLoading = false;
      }
    },
  },

  mounted() {
    this.getInfo();
  },
};
</script>

<style scoped>
* {
  font-family: Inter;
}
.v-card-text {
  height: 200px;
}
</style>
