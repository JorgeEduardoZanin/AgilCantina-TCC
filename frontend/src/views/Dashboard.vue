<template>
  <v-layout>
    <Sidebar />
    <v-main>
      <AdminDashboardMetrics />
      <AdminDashboardCharts />
    </v-main>
  </v-layout>
</template>

<script>
import Sidebar from "../components/Sidebar.vue";
import AdminDashboardMetrics from "@/components/AdminDashboardMetrics.vue";
import AdminDashboardCharts from "@/components/AdminDashboardCharts.vue";

export default {
  components: { Sidebar, AdminDashboardMetrics, AdminDashboardCharts },
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
</style>
