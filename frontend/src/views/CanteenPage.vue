<template>
  <v-layout>
    <v-app>
      <v-main fluid>
        <HeaderAuth />
        <ProductsCanteen />
        <Footer/>
      </v-main>
    </v-app>
  </v-layout>
</template>

<script>
import Footer from "@/components/Footer.vue";
import HeaderAuth from "@/components/HeaderAuth.vue";
import ProductsCanteen from "@/components/ProductsCanteen.vue";

export default {
  components: { HeaderAuth, ProductsCanteen,Footer },
};
</script>

<style scoped></style>
