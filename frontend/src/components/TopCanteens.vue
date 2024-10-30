<template>
  <div>
    <div v-if="isLoading" class="d-flex justify-center align-center">
      <v-progress-circular indeterminate></v-progress-circular>
    </div>

    <div>
      <h1 class="p-3">Melhores Cantinas</h1>
      <v-row>
        <v-col v-for="canteen in bestCantinas" :key="canteen.id" cols="12" md="3">
          <v-card
            variant="tonal"
            class="m-3"
            @click="goToCanteen(canteen.canteen_name, canteen.id)"
          >
            <v-card-title>{{ canteen.canteen_name }}</v-card-title>
            <v-card-subtitle>{{ canteen.description }}</v-card-subtitle>
          </v-card>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script>
import { getCantinas } from '@/services/HttpService';
import { mapActions } from 'vuex';

export default {
  name: "TopCanteens",
  data() {
    return {
      isLoading: true,
      bestCantinas: [],
    };
  },
  mounted() {
    this.getBestCantinas();
  },
  methods: {
    ...mapActions(['setCanteenId']),

    async getBestCantinas() {
      try {
        const response = await getCantinas();
        this.bestCantinas = response.data;
        console.log(response);
      } catch (error) {
        console.error("Erro ao carregar dados da cantina:", error);
      } finally {
        this.isLoading = false;
      }
    },
    goToCanteen(canteenName, canteenId) {
      this.setCanteenId(canteenId);
      const formattedName = encodeURIComponent(canteenName);
      this.$router.push(`/cantina/${formattedName}`);
    }
  }
};
</script>
