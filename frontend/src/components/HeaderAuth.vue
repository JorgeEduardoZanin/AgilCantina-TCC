<template>
  <div>
    <!-- Spinner de loading exclusivo para o HeaderAuth -->
    <div
      v-if="isLoading"
      class="d-flex justify-center align-center"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>

    <!-- Conteúdo do HeaderAuth -->
    <v-app-bar v-else scroll-behavior="elevate" class="header">
      <v-bottom-navigation mode="shift" class="header">
        <v-img
          class="mx-5 d-none d-md-flex"
          :src="logo"
          max-height="56"
          max-width="189"
          contain
          to="/"
        ></v-img>

        <v-spacer></v-spacer>

        <v-text-field
          append-inner-icon="mdi-magnify"
          density="compact"
          label="Pesquisar"
          variant="underlined"
          hide-details
          single-line
          @click:append-inner="onClick"
        ></v-text-field>

        <v-spacer></v-spacer>

        <v-btn value="Cantinas">
          <v-icon>bi bi-shop-window</v-icon>
          <span>Cantinas</span>
        </v-btn>

        <v-btn value="Carrinho" @click="drawer = !drawer">
          <v-icon>bi bi-basket3</v-icon>
          <span>Cestinha</span>
        </v-btn>

        <v-menu transition="fab-transition">
          <template v-slot:activator="{ props }">
            <v-list-item
              class="user"
              v-bind="props"
              prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
              :title="nome + ' ' + sobrenome"
            ></v-list-item>
          </template>

          <v-list>
            <v-list-item
              v-for="(item, index) in itemsRegister"
              :key="index"
              :value="index"
              @click="
                item.title === 'Sair' ? exitApp() : navigateTo(item.route)
              "
            >
              <div class="d-flex">
                <v-icon class="px-4">{{ item.icon }}</v-icon>
                <v-list-item-title>{{ item.title }}</v-list-item-title>
              </div>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-bottom-navigation>
    </v-app-bar>

    <CartDrawer :drawer="drawer" @update:drawer="drawer = $event" />
    <SettingsModal
      :isOpen="settingsModalOpen"
      @update:isOpen="settingsModalOpen = $event"
      @save="handleSaveSettings"
    />
  </div>
</template>

<script>
import logo from "../assets/logos/agil-cantina-letras-pretas.png";
import store from "@/store/index";
import CartDrawer from "@/components/CartDrawer.vue";
import SettingsModal from "./SettingsModal.vue";
import { GetUser } from "@/services/HttpService";
import { mapGetters } from "vuex";

export default {
  name: "HeaderAuth",
  components: { CartDrawer, SettingsModal },
  data() {
    return {
      logo,
      drawer: false,
      nome: "",
      sobrenome: "",
      isLoading: true,
      itemsRegister: [
        { title: "Configurações", route: "", icon: "mdi-cog" },
        { title: "Ajuda", route: "/ajuda", icon: "mdi-help-circle-outline" },
        { title: "Sair", icon: "bi bi-box-arrow-left" },
      ],
    };
  },
  mounted() {
    this.getInfoUser();

  },
  computed: {
    ...mapGetters(["getUserId"]),
  },
  methods: {
    async getInfoUser() {
      try {
        const user = this.getUserId
        const response = await GetUser(user);
        this.nome = response.data.name;
        this.sobrenome = response.data.surname;
      } catch (error) {
        console.error("Erro ao carregar dados do usuário:", error);
      } finally {
        this.isLoading = false;
      }
    },
    navigateTo(route) {
      this.$router.push(route);
    },
    exitApp() {
      store.dispatch("clearAuthData");
      this.$router.push("/");
    },
    openSettingsModal() {
      this.settingsModalOpen = true;
    },
    handleSaveSettings(updatedInfo) {
      this.nome = updatedInfo.nome;
      this.sobrenome = updatedInfo.sobrenome;
    },
  },
};
</script>

<style scoped>
.header {
  background: #fffdf8;
}
.user {
  background: #ffa600;
}

*::-webkit-scrollbar {
  width: 12px;
  height: 12px;
}

*::-webkit-scrollbar-track {
  background: #f9fafc;
}

*::-webkit-scrollbar-thumb {
  background-color: #ffa600;
  border-radius: 20px;
  border: 3px solid #ffffff;
}
</style>
