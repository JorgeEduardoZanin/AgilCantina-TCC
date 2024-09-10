<template>
  <div>
    <div class="main"> 
      <Header />
      <Carrousel />
    </div>
    <Sidebar />
  </div>
</template>

<script setup>
import Sidebar from "@/components/Sidebar.vue";
import Header from "@/components/Header.vue";
import Carrousel from "@/components/Carrousel.vue";
</script>

<style scoped>
.main {
  margin-left: 300px;
}
</style>
