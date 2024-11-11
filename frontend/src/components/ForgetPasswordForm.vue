<template>
  <v-container fill-height class="d-flex align-center justify-center">
    <v-snackbar v-model="successSnackbar" timeout="15000" top color="success">
      Verifique seu <strong>e-mail </strong>para alteração de senha
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

        <v-form
          @submit.prevent="submitForm"
          class="card p-4 m-2"
          style="width: 550px"
        >
        <div class="d-flex">
            <v-icon class="icon py-2 pr-1">mdi-lock-reset</v-icon>
            <h2 class="title py-2 px-2">Recuperar Senha</h2>
          </div>
          <v-text class="py-2">Esqueceu sua senha? Vamos ajudar!</v-text>
          <v-text class="py-2"><strong>1. Informe seu e-mail:</strong> Digite o e-mail da sua conta no campo abaixo.</v-text>
          <v-text class="py-2"><strong>2. Receba um link de verificação:</strong> Enviaremos um e-mail com um link para verificar sua identidade.</v-text>
          <v-text class="py-2"><strong>3. Abra seu e-mail:</strong> Vá até sua caixa de entrada e clique no link que enviamos.</v-text>
          <v-text class="py-2"><strong>4. Redefina sua senha:</strong> Após clicar no link, você será redirecionado para uma página onde poderá criar uma nova senha.</v-text>
          <v-text class="py-2"><strong>5. Senha atualizada:</strong> Pronto! Agora é só fazer login com sua nova senha.</v-text>
          <v-text-field
            class="inputCustom"
            variant="underlined"
            v-model="email"
            :rules="Emailrules"
            label="E-mail"
          ></v-text-field>
          <v-btn
            class="mt-2 registerButton"
            type="submit"
            block
            rounded="xl"
            size="large"
            >Enviar</v-btn
          >
        </v-form>
  </v-container>
</template>

<script>
import {forgetPassword} from "../services/HttpService";

export default {
  name: "ForgetPasswordForm",

  data: () => ({
    email: "",
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
  }),
  methods: {
    async submitForm() {
      const user = {
        email: this.email,
      };
      try {
        const response = await forgetPassword(user);
      } catch (error) {

      }finally{
        this.successSnackbar = true
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
</style>
