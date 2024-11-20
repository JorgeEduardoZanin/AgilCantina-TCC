<template>
  <v-layout>
    <v-app>
      <v-main fluid>
        <HeaderAuth />
        <v-container>
          <Carrousel />
          <HomeCantinas />
        </v-container>
      </v-main>
    </v-app>
  </v-layout>
</template>

<script>
import HeaderAuth from "@/components/HeaderAuth.vue";
import HomeCantinas from "@/components/TopCanteens.vue";
import Carrousel from "@/components/Carrousel.vue";

export default {
  components: { HeaderAuth, HomeCantinas, Carrousel },
};
</script>
