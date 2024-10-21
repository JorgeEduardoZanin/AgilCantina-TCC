<template>
  <div>
    <v-snackbar v-model="errorSnackbar" timeout="15000" top color="error">
      Ocorreu um erro
      <template v-slot:actions>
        <v-btn flat variant="text" @click="errorSnackbar = false"> X </v-btn>
      </template>
    </v-snackbar>

    <div class="mainLoginForm d-flex justify-content-center align-items-center">
      <div class="d-flex justify-content-center align-items-center">
        <v-form
          @submit.prevent="submitForm"
          class="card p-4 m-2"
          style="width: 550px"
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
          <a
            class="text-caption px-1 link"
            @click="redirectForgetPassword()"
            rel="noopener noreferrer"
            target="_blank"
          >
            Esqueceu sua senha?</a
          >

          <v-btn
            class="mt-2 registerButton"
            type="submit"
            block
            rounded="xl"
            size="large"
            >Login</v-btn
          >
        </v-form>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import router from "@/router";
import { loginUser, GetUser } from "../services/HttpService";

export default {
  name: "LoginForm",

  data: () => ({
    email: "",
    password: "",
    showPassword: false,
    errorSnackbar: false,
    Emailrules: [
      (value) => {
        if (value) return true;
        return "O e-mail é obrigatório";
      },
      (value) => {
        if (/.+@.+\..+/.test(value)) return true;
        return "O e-mail é inválido";
      },
    ],
    PasswordRules: [
      (value) => {
        if (value) return true;
        return "A senha é obrigatória";
      },
      (value) => {
        if (value.length >= 8) return true;
        return "A senha deve ter pelo menos 8 caracteres";
      },
    ],
  }),

  computed: {
    ...mapGetters(["getToken"]),
    token() {
      return this.getToken;
    },
  },

  methods: {
    ...mapActions(["setToken", "setRoleId", "setUserId"]),

    async submitForm() {
      const user = {
        email: this.email,
        password: this.password,
      };
      try {
        const response = await loginUser(user);
        console.log("Login feito com sucesso");

        const { token, user_id, role_id } = response.data;

        await this.setToken(token);
        await this.setRoleId(role_id);
        await this.setUserId(user_id);

        console.log("User ID salvo:", user_id);
        console.log("Token salvo:", token);
        console.log("Role ID salvo:", role_id);

        if (role_id === 3) {
          this.$router.push("/auth");
        } else if (role_id === 1) {
          this.$router.push("/dashboard");
        }
      } catch (error) {
        this.errorSnackbar = true;
      }
    },
    redirectForgetPassword() {
      this.$router.push("/forget_password");
    },
  },
};
</script>

<style>
.mainLoginForm {
  height: 100vh;
}
.title {
  font-family: Inter;
  font-weight: 600;
  color: #010100;
}
.icon{
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
