<template>
  <div v-if="isLoading" class="d-flex justify-center align-center">
    <v-progress-circular indeterminate></v-progress-circular>
  </div>
  <v-app v-else>
    <h1>{{ canteenName }}</h1>
  </v-app>
</template>

<script>
import { getProducts } from "@/services/HttpService";
import { mapState } from "vuex";

export default {
  computed: {
    ...mapState(["canteen_id"]),
    canteenName() {
      return decodeURIComponent(this.$route.params.canteenName);
    },
  },
  data() {
    return {
      isLoading: true,
    };
  },
  mounted() {
    const id = this.canteen_id;
    console.log(id);
    this.getProducts(id);
  },
  methods: {
    async getProducts(id) {
      try {
        const response = await getProducts(id);
        console.log(response);
      } catch (error) {
        console.error("Erro ao carregar produtos:", error);
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>
