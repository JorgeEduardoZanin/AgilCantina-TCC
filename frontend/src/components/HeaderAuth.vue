<template>
  <div>
    <div
      v-if="isLoading"
      class="d-flex justify-center align-center"
      style="height: 100vh"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>

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
            ></v-list-item>
          </template>

          <v-list>
            <v-list-item
              v-for="(item, index) in itemsRegister"
              :key="index"
              :value="index"
              @click="
                item.title === 'Sair'
                  ? exitApp()
                  : item.title === 'Configurações'
                  ? openSettingsModal()
                  : navigateTo(item.route)
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

    <v-dialog v-model="settingsModalOpen" max-width="700">
      <v-card>
        <v-card-text>
          <v-card-title>Configurações do Usuário</v-card-title>
          <v-container>
            <v-row>
              <v-col
                v-if="imagePreview"
                class="d-flex justify-center align-center"
              >
                <v-img
                  :src="imagePreview"
                  max-width="200"
                  max-height="200"
                  class="mt-4"
                  alt="Preview da imagem de perfil"
                ></v-img>
              </v-col>
              <v-col class="d-flex justify-center align-center">
                <v-file-input
                  v-model="profileImage"
                  label="Escolha uma imagem de perfil"
                  accept="image/*"
                  prepend-icon="mdi-camera"
                  @change="previewImage(), handleFileUpload()"
                  variant="underlined"
                ></v-file-input>
              </v-col>
            </v-row>
            <v-row>
              <v-col>
                <v-text-field
                  v-model="formData.name"
                  label="Nome"
                  variant="underlined"
                ></v-text-field>
              </v-col>
              <v-col>
                <v-text-field
                  v-model="formData.surname"
                  label="Sobrenome"
                  variant="underlined"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col>
                <v-text-field
                  v-model="formData.adress"
                  label="Endereço"
                  variant="underlined"
                ></v-text-field>
              </v-col>
              <v-col>
                <v-text-field
                  v-model="formData.city"
                  label="Cidade"
                  variant="underlined"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col>
                <v-text-field
                  v-model="formData.telephone"
                  label="Telefone"
                  variant="underlined"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="saveSettings"> Salvar </v-btn>
          <v-btn color="red" text @click="settingsModalOpen = false">
            Fechar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import logo from "../assets/logos/agil-cantina-letras-pretas.png";
import store from "@/store/index";
import CartDrawer from "@/components/CartDrawer.vue";
import { GetUser, putUpdateUser } from "@/services/HttpService";
import { mapGetters } from "vuex";

export default {
  name: "HeaderAuth",
  components: { CartDrawer },
  data() {
    return {
      profileImage: null,
      imagePreview: null,
      logo,
      drawer: false,
      settingsModalOpen: false,
      formData: {
        id: "",
        name: "",
        surname: "",
        cpf: "",
        adress: "",
        telephone: "",
        date_of_birth: "",
        email: "",
        city: "",
        role_id: "",
        created_at: "",
        updated_at: "",
      },
      isLoading: true,
      itemsRegister: [
        { title: "Configurações", route: "", icon: "mdi-cog" },
        { title: "Ajuda", route: "/ajuda", icon: "mdi-help-circle-outline" },
        { title: "Sair", icon: "bi bi-box-arrow-left" },
      ],
    };
  },
  mounted() {
    this.loadInfoUser();
  },
  computed: {
    ...mapGetters(["getUserId"]),
  },
  methods: {
    async loadInfoUser() {
      try {
        const userId = this.getUserId;
        const response = await GetUser(userId);
        if (response && response.data) {
          this.formData = { ...response.data };
        }
      } catch (error) {
        console.error("Erro ao carregar dados do usuário:", error);
      } finally {
        this.isLoading = false;
      }
    },
    async postImage() {
      const formData = new FormData();
      formData.append("image", this.profileImage);
      try {
        await postImageUser(formData);
      } catch (error) {
        console.error("Erro ao enviar imagem:", error);
      }
    },
    async saveSettings() {
      const userId = this.getUserId;
      const userData = {
        name: this.formData.name,
        surname: this.formData.surname,
        adress: this.formData.adress,
        telephone: this.formData.telephone,
        email: this.formData.email,
        city: this.formData.city,
      };
      try {
        await putUpdateUser(userId, user);
        this.postImage();
        this.loadInfoUser();
      } catch (error) {
        console.log(error);
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
    handleFileUpload(event) {
      this.profileImage = event.target.files[0];
    },
    previewImage() {
      if (this.profileImage) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.imagePreview = e.target.result;
        };
        reader.readAsDataURL(this.profileImage);
      } else {
        this.imagePreview = null;
      }
    },
  },
};
</script>

<style scoped>
*{
  font-family: Inter;
}
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
