<template>
  <div>
    <v-snackbar v-model="successSnackbar" timeout="15000" top color="success">
      Verifique seu <strong>e-mail </strong>para confirmação
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

    <div
      class="mainRegisterForm d-flex justify-content-center align-items-center"
    >
      <div class="d-flex justify-content-center align-items-center">
        <v-form
          @submit.prevent="submitForm"
          class="card p-4 m-5"
          style="width: 550px"
        >
          <h2 class="title mx-auto">Registrar-se</h2>
          <h5 class="mx-auto pb-4 subtitle">Usuario</h5>

          <v-text-field
            class="inputCustom"
            variant="solo-filled"
            v-model="email"
            :rules="EmailRules"
            label="E-mail"
          ></v-text-field>

          <v-text-field
            class="inputCustom"
            variant="solo-filled"
            v-model="nome"
            :rules="NameRules"
            label="Nome"
          ></v-text-field>

          <v-text-field
            class="inputCustom"
            variant="solo-filled"
            v-model="cpf"
            label="CPF"
            :rules="CpfRules"
            outlined
            maxlength="14"
          ></v-text-field>

          <v-text-field
            class="inputCustom"
            variant="solo-filled"
            v-model="telefone"
            label="Telefone"
            :rules="TelefoneRules"
          ></v-text-field>

          <v-text-field
            class="inputCustom"
            variant="solo-filled"
            v-model="endereco"
            label="Endereço"
            :rules="EnderecoRules"
          ></v-text-field>

          <v-text-field
            class="inputCustom"
            variant="solo-filled"
            v-model="dataNascimento"
            label="Data de Nascimento"
            :rules="DataNascimentoRules"
            type="date"
            required
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
            >Registrar</v-btn
          >
        </v-form>
      </div>
    </div>
  </div>
</template>

<script>
import { createUser } from "../services/HttpService";

export default {
  name: "RegisterForm",
  data: () => ({
    email: "",
    nome: "",
    cpf: "",
    telefone: "",
    endereco: "",
    dataNascimento: "",
    password: "",
    showPassword: false,
    successSnackbar: false,
    errorSnackbar: false,

    // Validações de formulário...
    EmailRules: [
      (value) => {
        if (value) return true;
        return "O e-mail é obrigatório";
      },
      (value) => {
        if (/.+@.+\..+/.test(value)) return true;
        return "O e-mail é inválido";
      },
    ],
    NameRules: [
      (value) => {
        if (value) return true;
        return "O nome é obrigatório";
      },
    ],
    CpfRules: [
      (value) => {
        if (value) return true;
        return "O CPF é obrigatório";
      },
      (value) => {
        const pattern = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
        return pattern.test(value) || "CPF inválido. Formato: 000.000.000-00";
      },
    ],
    TelefoneRules: [
      (value) => {
        if (value) return true;
        return "O Telefone é obrigatório";
      },
      (value) => {
        const pattern = /^\(\d{2}\) \d{4,5}-\d{4}$/;
        return (
          pattern.test(value) || "Telefone inválido. Formato: (00) 00000-0000"
        );
      },
    ],
    EnderecoRules: [
      (value) => {
        if (value) return true;
        return "O Endereço é obrigatório";
      },
    ],
    DataNascimentoRules: [
      (value) => {
        if (!value) {
          return "A Data de Nascimento é obrigatória";
        }

        const today = new Date();
        const birthDate = new Date(value);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        const dayDiff = today.getDate() - birthDate.getDate();

        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
          age--;
        }

        if (age >= 16) {
          return true;
        }

        return "Você deve ter 16 anos ou mais.";
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
        name: this.nome,
        cpf: this.cpf,
        telephone: this.telefone,
        adress: this.endereco,
        date_of_birth: this.dataNascimento,
        password: this.password,
      };
      console.log(user);

      try {
        const response = await createUser(user);
        console.log("Usuário registrado com sucesso:", response);
        this.successSnackbar = true;
      } catch (error) {
        console.error("Erro ao registrar o usuário:", error);
        this.errorSnackbar = true;
      }
    },
  },
};
</script>

<style scoped>
.mainRegisterForm {
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
.subtitle {
  color: #949494;
}
</style>
