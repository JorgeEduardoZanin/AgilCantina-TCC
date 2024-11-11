<template>
  <v-layout>
    <Sidebar/>
    <v-main>
      <Orders/>
    </v-main>
  </v-layout>
</template>

<script>
import Orders from '@/components/Orders.vue';
import Sidebar from '@/components/Sidebar.vue';
export default {
components:{
Sidebar,Orders
}
}
</script>

<style>

</style>