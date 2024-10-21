<template>
  <div>
    <v-progress-circular indeterminate v-if="isLoading"></v-progress-circular>
    <div class="main" v-else>
      <v-app>
        <HeaderAuth />

      </v-app>
    </div>
  </div>
</template>

<script>
import HeaderAuth from "@/components/HeaderAuth.vue";

import { mapGetters, mapActions } from "vuex";
import { GetUser } from "@/services/HttpService";

export default {
  components: { HeaderAuth},
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
