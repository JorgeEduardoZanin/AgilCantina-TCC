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

    <v-container
      class="mainRegisterForm d-flex justify-center align-center"
      fluid
      fill-height
    >
    <div class="content">
      <div class="d-flex"> 
        <v-icon class="icon py-2 pr-1">bi bi-person-fill</v-icon>
        <h2 class="title py-2 px-2">Cadastro</h2>
      </div>
      <h5 class="subtitle pb-2">
        Encontre a sua cantina favorita e faça pedidos de forma rápida e
        prática. Garanta uma experiência personalizada, com mais conveniência e
        agilidade no seu dia a dia!
      </h5>
      <div class="form p-4">
      <v-row>
        <v-col>
          <v-text-field
            class="inputCustom"
            variant="underlined"
            v-model="nome"
            :rules="NameRules"
            label="Nome"
          ></v-text-field>
        </v-col>
        <v-col>
          <v-text-field
            class="inputCustom"
            variant="underlined"
            v-model="sobrenome"
            :rules="SurnameRules"
            label="Sobrenome"
          ></v-text-field>
        </v-col>
      </v-row>

      <v-text-field
        class="inputCustom"
        variant="underlined"
        v-model="cpf"
        label="CPF"
        :rules="CpfRules"
        outlined
        maxlength="14"
      ></v-text-field>

      <v-text-field
        class="inputCustom"
        variant="underlined"
        v-model="telefone"
        label="Telefone"
        :rules="TelefoneRules"
      ></v-text-field>

      <v-text-field
        class="inputCustom"
        variant="underlined"
        v-model="endereco"
        label="Endereço"
        :rules="EnderecoRules"
      ></v-text-field>

      <v-text-field
        class="inputCustom"
        variant="underlined"
        v-model="dataNascimento"
        label="Data de Nascimento"
        :rules="DataNascimentoRules"
        type="date"
        required
      ></v-text-field>

      <v-text-field
        class="inputCustom"
        variant="underlined"
        v-model="email"
        :rules="EmailRules"
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
        @click="submitRegisterForm()"
        >Registrar</v-btn
      >
    </div>
    </div>
    </v-container>
  </div>
</template>

<script>
import { createUser } from "../services/HttpService";

export default {
  name: "RegisterForm",
  data: () => ({
    email: "",
    nome: "",
    sobrenome: "",
    cpf: "",
    telefone: "",
    endereco: "",
    dataNascimento: "",
    password: "",
    showPassword: false,
    successSnackbar: false,
    errorSnackbar: false,

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
    SurnameRules: [
      (value) => {
        if (value) return true;
        return "O Sobrenome é obrigatório";
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
    teste(){
      console.log("ta clickando")
    },
    async submitRegisterForm() {
      const user = {
        email: this.email,
        name: this.nome,
        surname: this.sobrenome,
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
.content {
  width: 1000px;
}
.form{
  background-color: white;
  box-shadow: 0px 3px 1px -2px var(--v-shadow-key-umbra-opacity, rgba(0, 0, 0, 0.2)), 0px 2px 2px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.14)), 0px 1px 5px 0px var(--v-shadow-key-ambient-opacity, rgba(0, 0, 0, 0.12));
}
.registerButton {
  background-color: #333333;
  color: #ffffff;
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
.subtitle {
  color: #0a0a0a;
  font-family: Inter;
  font-weight: 300;
}
@media (max-width: 959px) {
  .mainRegisterForm {
    height: auto;
  }
}
</style>
