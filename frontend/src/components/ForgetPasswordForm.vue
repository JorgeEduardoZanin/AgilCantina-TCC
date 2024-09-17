<template>
  <div>
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

    <div class="mainLoginForm d-flex justify-content-center align-items-center">
      <div class="d-flex justify-content-center align-items-center">
        <v-form
          @submit.prevent="submitForm"
          class="card p-4 m-2"
          style="width: 550px"
        >
          <h2 class="title mx-auto pb-3">Recuperar Senha</h2>
          <v-text-field
            class="inputCustom"
            variant="solo-filled"
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
      </div>
    </div>
  </div>
</template>

<script>
import {} from "../services/HttpService";

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
      } catch (error) {}
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
</style>
