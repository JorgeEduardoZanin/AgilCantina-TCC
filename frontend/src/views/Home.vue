<template>
  <div>
    <div class="main">
      <v-app>
        <Header />
        <Carrousel />
        <TopCanteens />
      </v-app>
    </div>
  </div>
</template>

<script setup>
import Header from "@/components/Header.vue";
import Carrousel from "@/components/Carrousel.vue";
import TopCanteens from "@/components/TopCanteens.vue";
</script>

<style scoped></style>
