<template>
  <div>
    <v-app>
      <HeaderAuth />
      <Carrousel />
      <HomeCantinas />
      <Footer/>
    </v-app>
  </div>
</template>

<script>
import HeaderAuth from "@/components/HeaderAuth.vue";
import HomeCantinas from "@/components/TopCanteens.vue";
import Carrousel from "@/components/Carrousel.vue";
import Footer from "@/components/Footer.vue";

export default {
  components: { HeaderAuth, HomeCantinas, Carrousel,Footer},
};
</script>
