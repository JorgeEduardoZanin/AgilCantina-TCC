<template>
  <div class="mainLoginForm d-flex justify-content-center align-items-center">
    <div class="d-flex justify-content-center align-items-center">
      <v-form @submit.prevent="submitForm" class="card p-4 m-2" style="width: 550px;">
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

        <v-btn class="mt-2" type="submit" block>Entrar</v-btn>
      </v-form>
      <pre>{{ this.data }}</pre>
    </div>
  </div>
</template>

<script>
import { loginUser } from '../services/HttpService';

export default {
  name: "LoginForm",

  data: () => ({
    email: "",
    password: "",
    showPassword: false,
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
      const user = {
        email: this.email,
        password: this.password,
      };

      try {
        const response = await loginUser(user);
        console.log('Login realizado com sucesso:', response);
        // Aqui você pode redirecionar o usuário ou exibir uma mensagem de sucesso
      } catch (error) {
        console.error('Erro ao realizar login:', error);
        // Aqui você pode exibir uma mensagem de erro ao usuário
      }
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
</style>
