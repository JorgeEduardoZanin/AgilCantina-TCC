 <template>
  <div
      v-if="isLoading"
      class="d-flex justify-center align-center"
      style="height: 100vh"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>
  <v-navigation-drawer v-else expand-on-hover rail>
    <v-img
      class="mx-5"
      :src="logo"
      max-height="56"
      max-width="189"
      contain
      to="/"
    ></v-img>

    <v-divider></v-divider>
    
    <v-list>
      <v-list-item
        prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
        :title="nome + ' ' + sobrenome"
      ></v-list-item> 
    </v-list>

    <v-divider></v-divider>

    <v-list density="compact" nav>
      <v-list-item
        prepend-icon="mdi-chart-box-outline"
        title="Dashboard"
        value="dashboard"
        to="/dashboard"
      ></v-list-item>
      <v-list-item
        prepend-icon="mdi-domain"
        title="Perfil"
        value="profile"
        to="/dashboard/profile"
      ></v-list-item>
      <v-list-item
        prepend-icon="mdi-silverware-fork-knife"
        title="Cardapio"
        value="menu"
        to="/dashboard/menu"
      ></v-list-item>
      <v-list-item
        prepend-icon="mdi-order-bool-descending-variant"
        title="Pedidos"
        value="order"
        to="/dashboard/order"
      ></v-list-item>
    </v-list>

    <v-divider></v-divider>

    <v-list>
      <v-list-item
        prepend-icon="bi bi-box-arrow-left"
        title="Sair"
        @click="exitApp()"
      ></v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
import logo from "../assets/logos/agil-cantina-letras-pretas.png";
import store from "@/store/index";
import { mapGetters, mapActions } from "vuex";
import { GetUser } from "@/services/HttpService";

export default {
  name: "Sidebar",
  props: {
    isLoading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      logo,
      nome: store.getters.getName,
      sobrenome: store.getters.getSurname,
      isLoading: true,
    };
  },
  async mounted() {
    await this.getInfoUser();
  },
  methods:{
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

    exitApp() {
        store.dispatch('clearAuthData');
        this.$router.push('/');
      },
  },
  computed: {
    ...mapGetters(["getUserId"]),
  },
};
</script>

<style>
v-card {
  background: red;
}
</style>
