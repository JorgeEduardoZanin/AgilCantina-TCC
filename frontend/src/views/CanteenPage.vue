<template>
  <v-layout>
    <div v-if="isLoading" class="d-flex justify-center align-center">
      <v-progress-circular indeterminate></v-progress-circular>
    </div>
    <v-app v-else>
      <v-main fluid>
        <HeaderAuth />
        <ProductsCanteen />
      </v-main>
    </v-app>
  </v-layout>
</template>

<script>
import HeaderAuth from "@/components/HeaderAuth.vue";
import ProductsCanteen from "@/components/ProductsCanteen.vue";

export default {
  components: { HeaderAuth, ProductsCanteen },
};
</script>

<style scoped></style>
