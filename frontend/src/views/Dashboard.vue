<template>
  <v-layout>
    <Sidebar />
    <v-main>
      <h1>Dashboard</h1>
    </v-main>
  </v-layout>
</template>

<script>
import DashboardHome from "@/components/DashboardHome.vue";
import Sidebar from "../components/Sidebar.vue";

export default {
  components: { DashboardHome,Sidebar},
};
</script>

<style>
.mainDashboard {
  background-color: #f2f2f2;
  font-family: Inter;
}
.title {
  font-size: 36px;
}
.foto{
    background-color: #ffa600;
}
</style>
