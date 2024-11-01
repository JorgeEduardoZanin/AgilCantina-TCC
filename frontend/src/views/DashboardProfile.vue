<template>
  <v-layout>
    <Sidebar />
    <v-main>
      <EditProfileCompany/>
    </v-main>
  </v-layout>
</template>

<script>
import EditProfileCompany from '@/components/EditProfileCompany.vue';
import Sidebar from '@/components/Sidebar.vue';

export default {
  components: {
    Sidebar, EditProfileCompany
  }
};
</script>
