<template>
  <v-carousel class="px-4" show-arrows="hover" hide-delimiters>
    <v-carousel-item :src="coxinha" cover> </v-carousel-item>

    <v-carousel-item :src="paodequeijo" cover></v-carousel-item>

    <v-carousel-item :src="pastel" cover></v-carousel-item>
  </v-carousel>
</template>

<script>
import coxinha from "../assets/Carrousel/coxinha.jpg";
import paodequeijo from "../assets/Carrousel/paodequeijo.jpg";
import pastel from "../assets/Carrousel/pastel.jpg";

export default {
  name: "Carrousel",
  data() {
    return {
      coxinha,
      paodequeijo,
      pastel,
    };
  },
};
</script>

<style scoped>
</style>
