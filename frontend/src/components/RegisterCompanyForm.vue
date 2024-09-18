<template>
  <div>
    <v-snackbar
      v-model="successSnackbar"
      timeout="15000"
      location="top"
      color="success"
    >
      Verifique seu <strong>e-mail </strong>para confirmação
      <template #actions>
        <v-btn variant="text" @click="successSnackbar = false">X</v-btn>
      </template>
    </v-snackbar>

    <v-snackbar
      v-model="errorSnackbar"
      timeout="15000"
      location="top"
      color="error"
    >
      Ocorreu um erro
      <template #actions>
        <v-btn variant="text" @click="errorSnackbar = false">X</v-btn>
      </template>
    </v-snackbar>

    <v-container
      class="mainRegisterForm d-flex justify-content-center align-center"
      fluid
      fill-height
    >
      <v-form
        @submit.prevent="submitForm"
        class="card p-4"
        style="max-width: 1080px; width: 100%"
      >
        <h2 class="title">Cadastro</h2>
        <v-row>
          <v-col cols="12" md="6">
            <h5 class="p-2 subtitle">Proprietário</h5>
            <v-text-field
              class="inputCustom"
              variant="solo"
              v-model="nome"
              :rules="NameRules"
              label="Nome do Proprietário"
            ></v-text-field>
            <v-text-field
              class="inputCustom"
              variant="solo"
              v-model="cpf"
              label="CPF"
              :rules="CpfRules"
              maxlength="14"
            ></v-text-field>
            <v-text-field
              class="inputCustom"
              variant="solo"
              v-model="endereco"
              label="Endereço"
              :rules="EnderecoRules"
            ></v-text-field>
            <v-text-field
              class="inputCustom"
              variant="solo"
              v-model="telefone"
              label="Telefone"
              :rules="TelefoneRules"
            ></v-text-field>
            <v-text-field
              class="inputCustom"
              variant="solo"
              v-model="dataNascimento"
              label="Data de Nascimento"
              :rules="DataNascimentoRules"
              type="date"
            ></v-text-field>
            <v-text-field
              class="inputCustom"
              variant="solo"
              v-model="email"
              :rules="EmailRules"
              label="E-mail"
            ></v-text-field>
            <v-text-field
              class="inputCustom"
              variant="solo"
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              :rules="PasswordRules"
              label="Senha"
              :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
              @click:append-inner="showPassword = !showPassword"
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="6">
            <h5 class="p-2 subtitle">Empresa</h5>
            <v-text-field
              class="inputCustom"
              variant="solo"
              v-model="razaoSocial"
              :rules="CorporateReasonRules"
              label="Razão Social"
            ></v-text-field>
            <v-text-field
              class="inputCustom"
              variant="solo"
              v-model="cnpj"
              label="CNPJ"
              :rules="CnpjRules"
              maxlength="18"
            ></v-text-field>
            <v-text-field
              class="inputCustom"
              variant="solo"
              v-model="telefoneAtendimento"
              label="Telefone de atendimento"
              :rules="TelefoneRules"
            ></v-text-field>
            
          </v-col>
        </v-row>
        <v-btn
          class="mt-2 registerButton"
          type="submit"
          block
          rounded="xl"
          size="large"
        >
          Registrar
        </v-btn>
      </v-form>
    </v-container>
  </div>
</template>

<script>
import { createUser } from "../services/HttpService";

export default {
  name: "RegisterCompanyForm",
  data: () => ({
    //proprietario
    email: "",
    nome: "",
    cpf: "",
    telefone: "",
    endereco: "",
    dataNascimento: "",
    password: "",
    //empresa
    razaoSocial: "",
    cnpj: "",
    telefoneAtendimento: "",
    showPassword: false,
    successSnackbar: false,
    errorSnackbar: false,

    EmailRules: [
      (value) => !!value || "O e-mail é obrigatório",
      (value) => /.+@.+\..+/.test(value) || "O e-mail é inválido",
    ],
    NameRules: [(value) => !!value || "O nome é obrigatório"],
    CpfRules: [
      (value) => !!value || "O CPF é obrigatório",
      (value) =>
        /^\d{3}\.\d{3}\.\d{3}-\d{2}$/.test(value) ||
        "CPF inválido. Formato: 000.000.000-00",
    ],
    TelefoneRules: [
      (value) => !!value || "O Telefone é obrigatório",
      (value) =>
        /^\(\d{2}\) \d{4,5}-\d{4}$/.test(value) ||
        "Telefone inválido. Formato: (00) 00000-0000",
    ],
    EnderecoRules: [(value) => !!value || "O Endereço é obrigatório"],
    DataNascimentoRules: [
      (value) => !!value || "A Data de Nascimento é obrigatória",
      (value) => {
        const today = new Date();
        const birthDate = new Date(value);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        const dayDiff = today.getDate() - birthDate.getDate();

        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) age--;

        return age >= 16 || "Você deve ter 16 anos ou mais.";
      },
    ],
    PasswordRules: [
      (value) => !!value || "A senha é obrigatória",
      (value) =>
        (value && value.length >= 8) ||
        "A senha deve ter pelo menos 8 caracteres",
    ],
    CorporateReasonRules: [
      (value) => !!value || "A Razão Social é obrigatória",
    ],
    CnpjRules: [
      (value) => !!value || "O CNPJ é obrigatório",
      (value) =>
        /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/.test(value) ||
        "CNPJ inválido. Formato: 00.000.000/0000-00",
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

.inputCustom {
  width: 100%;
}
.registerButton {
  background-color: #333;
  color: #f2f2f2;
}
.title {
  font-family: Inter;
  font-weight: 600;
  color: #333;
}
.card {
  background-color: #f2f2f2;
}
.subtitle {
  color: #474747;
}
@media (max-width: 959px) {
  /* Ajusta para telas menores que 768px */
  .mainRegisterForm {
    height: auto;
  }
}
</style>
