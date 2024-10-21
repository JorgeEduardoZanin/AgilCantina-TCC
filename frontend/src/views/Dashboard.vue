<template>
  <v-layout>
    <v-progress-circular indeterminate v-if="isLoading"></v-progress-circular>
    <div v-else>
      <Sidebar />
      <v-main>
        <h1 class="title">Dashboard</h1>
        <MenuItems />
      </v-main>
    </div>
  </v-layout>
</template>

<script>
import Sidebar from "../components/Sidebar.vue";
import { mapGetters, mapActions } from "vuex";
import { GetUser } from "@/services/HttpService";
import MenuItems from "@/components/MenuItems.vue";

export default {
  components: { Sidebar, MenuItems },
  data() {
    return {
      isLoading: true,
    };
  },
  async mounted() {
    await this.getInfoUser();
  },
  methods: {
    ...mapActions(["setName", "setSurname"]),

    async getInfoUser() {
      try {
        const userId = this.getUserId;
        const response = await GetUser(userId);

        const { name, surname } = response.data;

        await this.setName(name);
        await this.setSurname(surname);
      } catch (error) {
        console.error("Erro ao carregar dados do usuário:", error);
      } finally {
        this.isLoading = false;
      }
    },
  },
  computed: {
    ...mapGetters(["getUserId"]),
  },
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
