<template>
  <div>
    <div
      v-if="isLoading"
      class="d-flex justify-center align-center"
      style="height: 50vh"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>

    <div v-else>
      <h1 class="p-3">Cantinas</h1>
      <v-row>
        <v-col
          v-for="canteen in bestCantinas"
          :key="canteen.id"
          cols="12"
          md="3"
        >
          <v-card
            class="m-3"
            @click="goToCanteen(canteen.canteen_name, canteen.id)"
            elevation="5"
          >
            <v-row>
              <v-col
                class="d-flex justify-end align-center"
                style="flex: 0 0 auto"
              >
                <v-img
                  :src="canteen.imageUrl"
                  height="200"
                  class="rounded-lg m-3"
                ></v-img>
              </v-col>
              <v-col class="d-flex flex-column justify-center">
                <v-card-title>{{ canteen.canteen_name }}</v-card-title>
                <v-card-subtitle class="text-wrap p-3">{{
                  canteen.description
                }}</v-card-subtitle>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script>
import { getCantinas, getImageCanteen } from "@/services/HttpService";
import { mapActions } from "vuex";

export default {
  name: "TopCanteens",
  data() {
    return {
      isLoading: true,
      bestCantinas: [],
    };
  },
  mounted() {
    this.getBestCanteens();
  },
  methods: {
    ...mapActions(["setCanteenId"]),

    async getBestCanteens() {
      try {
        const response = await getCantinas();
        const cantinas = response.data;

        for (const cantina of cantinas) {
          const image = await this.getImageCanteen(cantina.id);
          cantina.imageUrl = image;
        }

        this.bestCantinas = cantinas;
        console.log(this.bestCantinas);
      } catch (error) {
        console.error("Erro ao carregar dados da cantina:", error);
      } finally {
        this.isLoading = false;
      }
    },

    async getImageCanteen(canteenId) {
      try {
        const response = await getImageCanteen(canteenId);
        let imageUrl = response.data.image_url;

        // Remove barras escapadas (\) e adiciona o prefixo "http://localhost"
        if (imageUrl) {
          imageUrl = imageUrl.replace(/\\/g, ""); // Remove as barras escapadas
          imageUrl = `http://${imageUrl}`; // Adiciona o prefixo correto
        }

        return imageUrl;
      } catch (error) {
        console.error(
          `Erro ao carregar imagem da cantina ${canteenId}:`,
          error
        );
        return null;
      }
    },

    goToCanteen(canteenName, canteenId) {
      this.setCanteenId(canteenId);
      const formattedName = encodeURIComponent(canteenName);
      this.$router.push(`/cantina/${formattedName}`);
    },
  },
};
</script>

<style scoped>
* {
  font-family: Inter;
}
</style>
