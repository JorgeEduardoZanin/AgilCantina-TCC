<template>
  <div>
    <v-snackbar v-model="successSnackbar" timeout="15000" top color="success">
      Senha Alterada com Sucesso
      <template v-slot:actions>
        <v-btn flat variant="text" @click="successSnackbar = false"> X </v-btn>
      </template>
    </v-snackbar>

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
            <v-icon class="icon py-2 pr-1">mdi-lock-reset</v-icon>
            <h2 class="title py-2 px-2">Recuperar Senha</h2>
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

          <v-btn
            class="mt-2 registerButton"
            type="submit"
            block
            rounded="xl"
            size="large"
            >Recuperar</v-btn
          >
        </v-form>
      </div>
    </div>
  </div>
</template>

<script>
import { resetPassword } from "../services/HttpService";

export default {
  name: "ForgetPasswordForm",

  data: () => ({
    email: "",
    password: "",
    showPassword: false,
    successSnackbar: false,
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
  methods: {
    async submitForm() {
      const newPassword = {
        token: this.$route.params.token,
        email: this.email,
        password: this.password,
        password_confirmation : this.password,
      };
      try {
        const response = await resetPassword(newPassword);
      } catch (error) {
        this.errorSnackbar = true;
      } finally {
        this.successSnackbar = true;
        setTimeout(() => {
          this.$router.push("/login");
        }, 10000);
      }
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
</style>
