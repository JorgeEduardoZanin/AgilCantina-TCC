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
          <h2 class="title mx-auto pb-3">Login</h2>
          <v-text-field
            class="inputCustom"
            variant="solo-filled"
            v-model="email"
            :rules="Emailrules"
            label="E-mail"
          ></v-text-field>

          <v-text-field
            class="inputCustom"
            variant="solo-filled"
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
import { mapActions, mapGetters } from "vuex"; // Importa mapGetters também
import router from "@/router";
import { loginUser } from "../services/HttpService";

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
    ...mapGetters(['getToken']),
    token() {
      return this.getToken; 
    },
  },

  methods: {
    ...mapActions(['setToken']),

    async submitForm() {
      const user = {
        email: this.email,
        password: this.password,
      };
      try {
        const response = await loginUser(user);
        console.log("Login feito com sucesso");
        const token = response.data.token; 
        await this.setToken(token); 
        console.log("Token salvo:", this.token);
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
  background: linear-gradient(to top, #ffa600, #ffd900);
  height: 100vh;
}
.title {
  font-family: Inter;
  font-weight: 600;
  color: #333;
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
}
.link:hover {
  text-decoration: underline;
}
</style>
