<template>
  <div>
    <v-app>
      <HeaderAuth />
      <HomeCantinas />
      <Footer/>
    </v-app>
  </div>
  </template>
  
  <script>
  import Footer from "@/components/Footer.vue";
import HeaderAuth from "@/components/HeaderAuth.vue";
  import HomeCantinas from "@/components/TopCanteens.vue";
  
  export default {
    components: { HeaderAuth, HomeCantinas,Footer},
  };
  </script>
  