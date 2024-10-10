<template>
  <v-layout>
    <Sidebar />
    <v-main>
      <h1 class="p-4">Cardapio</h1>
      <MenuItems/>
    </v-main>
  </v-layout>
</template>

<script>
import MenuItems from '@/components/MenuItems.vue';
import Sidebar from '@/components/Sidebar.vue';

export default {
  components: {
    Sidebar,
    MenuItems
  }
};
</script>
