<template>
    <div class="mainLoginForm d-flex justify-content-center align-items-center">
      <div class="d-flex justify-content-center align-items-center">
        <v-form
          @submit.prevent="submitForm"
          class="card p-4 m-2"
          style="width: 550px"
        >
          <h2 class="title mx-auto pb-3">Login Empresa</h2>
          <v-text-field
            class="inputCustom"
            variant="solo-filled"
            v-model="cnpj"
            label="CNPJ"
            :rules="CnpjRules"
            outlined
            maxlength="18"
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
  </template>
  
  <script>
  import {} from "../services/HttpService";
  
  export default {
    name: "LoginForm",
  
    data: () => ({
      cnpj: "",
      password: "",
      showPassword: false,
      CnpjRules: [
        (value) => {
          if (value) return true;
          return "O CNPJ é obrigatório";
        },
        (value) => {
          const pattern = /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/;
          return pattern.test(value) || "CNPJ inválido. Formato: 00.000.000/0000-00";
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
          cnpj: this.cnpj,
          password: this.password,
        };
  
        try {
          const response = await loginUserCompany(user);
          console.log("Login realizado com sucesso:", response);

        } catch (error) {
          console.error("Erro ao realizar login:", error);
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
  .registerButton {
    background-color: #333;
    color: #f2f2f2;
  }
  </style>
  