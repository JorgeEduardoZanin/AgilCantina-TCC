<template>
    <v-layout>
      <v-app>
        <HeaderDashboard />
        <v-main>
          <AdminRequests />
        </v-main>
      </v-app>
    </v-layout>
</template>

<script>
import AdminRequests from "@/components/AdminRequests.vue";
import HeaderDashboard from "@/components/HeaderDashboard.vue";

export default {
  components: { HeaderDashboard, AdminRequests },
};
</script>

<style></style>
