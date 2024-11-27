<template>
    <v-layout>
      <v-app>
        <v-main fluid>
          <v-container>
            <HelpIfo />
          </v-container>
        </v-main>
      </v-app>
    </v-layout>
  </template>
  
  <script>
  import HelpIfo from "@/components/HelpInfo.vue";
  
  export default {
    components: { HelpIfo,},
  };
  </script>
  