<template>
  <v-layout>
      <HeaderDashboard/>
    </v-layout>
        <AdminDashboardMetrics/>
        <AdminDashboardCharts/>
</template>

<script>
import AdminDashboardCharts from '@/components/AdminDashboardCharts.vue';
import AdminDashboardMetrics from '@/components/AdminDashboardMetrics.vue';
import HeaderDashboard from '@/components/HeaderDashboard.vue';

export default {
    components:{AdminDashboardMetrics,AdminDashboardCharts,HeaderDashboard}
}
</script>

<style>

</style>