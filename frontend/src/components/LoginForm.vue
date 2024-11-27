<template>
  <v-container fill-height class="d-flex align-center justify-center" style="height: 100vh;">
    <v-snackbar v-model="errorSnackbar" timeout="15000" top color="error">
      {{ errorMensage }}
      <template v-slot:actions>
        <v-btn flat variant="text" @click="errorSnackbar = false"> X </v-btn>
      </template>
    </v-snackbar>

    <v-form
      @submit.prevent="submitForm"
      class="card p-4 m-2"
      style="width: 550px; max-width: 100%;"
    >
      <div class="d-flex">
        <v-icon class="icon py-2 pr-1">bi bi-box-arrow-right</v-icon>
        <h2 class="title py-2 px-2">Login</h2>
      </div>

      <v-text-field
        class="inputCustom"
        variant="underlined"
        v-model="email"
        :rules="Emailrules"
        label="E-mail"
      ></v-text-field>

      <v-text-field
        class="inputCustom"
        variant="underlined"
        v-model="password"
        :type="showPassword ? 'text' : 'password'"
        :rules="PasswordRules"
        label="Password"
        :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
        @click:append="showPassword = !showPassword"
      ></v-text-field>

      <v-row>
        <v-col>
          <v-menu transition="fab-transition">
            <template v-slot:activator="{ props }">
              <a class="text-caption px-1 link" v-bind="props">
                Novo por aqui? Cadastre-se
              </a>
            </template>
            <v-list>
              <v-list-item @click="redirectRegisterUser">
                <v-list-item-title>
                  <v-icon class="px-4">bi bi-person</v-icon>
                  Usuário
                </v-list-item-title>
              </v-list-item>
              <v-list-item @click="redirectRegisterCompany">
                <v-list-item-title>
                  <v-icon class="px-4">bi bi-shop-window</v-icon>
                  Cantina
                </v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-col>

        <v-col class="text-right">
          <a
            class="text-caption px-1 link"
            @click="redirectForgetPassword"
            rel="noopener noreferrer"
            target="_blank"
          >
            Esqueceu sua senha?
          </a>
        </v-col>
      </v-row>

      <v-btn
        class="mt-2 registerButton"
        type="submit"
        block
        rounded="xl"
        size="large"
      >
        Login
      </v-btn>
    </v-form>
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { loginUser } from "../services/HttpService";

export default {
  name: "LoginForm",

  data: () => ({
    email: "",
    password: "",
    showPassword: false,
    errorSnackbar: false,
    errorMensage: "",
    Emailrules: [
      (value) => !!value || "O e-mail é obrigatório",
      (value) => /.+@.+\..+/.test(value) || "O e-mail é inválido",
    ],
    PasswordRules: [
      (value) => !!value || "A senha é obrigatória",
      (value) =>
        (value && value.length >= 8) ||
        "A senha deve ter pelo menos 8 caracteres",
    ],
  }),

  computed: {
    ...mapGetters(["getToken"]),
    token() {
      return this.getToken;
    },
  },

  methods: {
    ...mapActions(["setToken", "setRoleId", "setUserId","setCanteenId"]),

    async submitForm() {
      const user = {
        email: this.email,
        password: this.password,
      };
      try {
        const response = await loginUser(user);
        const { token, user_id, role_id, cantina_id} = response.data;

        await this.setToken(token);
        await this.setRoleId(role_id);
        await this.setUserId(user_id);
        await this.setCanteenId(cantina_id);

        if (role_id === 3) {
          this.$router.push("/auth");
        } else if (role_id === 1) {
          this.$router.push("/dashboard");
        } else if(role_id === 2) {
          this.$router.push("admin/dashboard");
        }
      } catch (error) {
        this.errorMensage= error.response.data.error;
        this.errorSnackbar = true;
      }
    },
    redirectForgetPassword() {
      this.$router.push("/forget_password");
    },
    redirectRegisterUser() {
      this.$router.push("/register/user");
    },
    redirectRegisterCompany() {
      this.$router.push("/register/company");
    },
  },
};
</script>

<style scoped>
* {
  font-family: Inter;
}
.title {
  font-family: Inter;
  font-weight: 600;
  color: #010100;
}
.icon {
  font-size: 45px;
  color: #ffa600;
}
.card {
  background-color: #f2f2f2;
}
.registerButton {
  background-color: #333;
  color: #f2f2f2;
}
.link {
  text-decoration: none;
  cursor: pointer;
  color: #ffa600;
}
.link:hover {
  text-decoration: underline;
}
</style>
