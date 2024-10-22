<template>
  <v-layout>
    <v-progress-circular indeterminate v-if="isLoading"></v-progress-circular>
    <v-app v-else>
      <v-main fluid>
        <HeaderAuth />
        <Carrousel/>
        <HomeCantinas />
      </v-main>
    </v-app>
  </v-layout>
</template>

<script>
import HeaderAuth from "@/components/HeaderAuth.vue";
import HomeCantinas from "@/components/HomeCantinas.vue";
import Carrousel from "@/components/Carrousel.vue";

import { mapGetters, mapActions } from "vuex";
import { GetUser } from "@/services/HttpService";

export default {
  components: { HeaderAuth, HomeCantinas, Carrousel},
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

<style scoped></style>
