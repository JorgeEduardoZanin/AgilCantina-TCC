<template>
  <div>
    <v-app>
      <HeaderAuth />
      <StatusChange />
      <Footer />
    </v-app>
  </div>
</template>

<script>
import Footer from "@/components/Footer.vue";
import HeaderAuth from "@/components/HeaderAuth.vue";
import StatusChange from "@/components/StatusChange.vue";

export default {
  components: { StatusChange, Footer, HeaderAuth },
};
</script>

<style></style>
