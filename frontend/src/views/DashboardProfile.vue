<template>
  <v-layout>
    <Sidebar />
    <v-main>
      <h1 class="p-5">Perfil</h1>
    </v-main>
  </v-layout>
</template>

<script>
import Sidebar from '@/components/Sidebar.vue';

export default {
  components: {
    Sidebar,
  }
};
</script>
