<template>
  <v-container fill-height class="d-flex align-center justify-center">
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

    <v-container class="container" fluid>
      <v-row>
        <v-icon class="icon py-2 px-3">bi bi-person-fill</v-icon>
        <h2 class="title py-2 px-2">Cadastro</h2>
      </v-row>
      <v-row>
        <h5 class="subtitle pb-4 mx-2">
          Encontre a sua cantina favorita e faça pedidos de forma rápida e
          prática. Garanta uma experiência personalizada, com mais conveniência
          e agilidade no seu dia a dia!
        </h5>
      </v-row>
      <v-form class="form p-3">
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
        <v-row>
          <v-col>
            <v-text-field
              class="inputCustom"
              variant="underlined"
              v-model="endereco"
              label="Endereço"
              :rules="EnderecoRules"
            ></v-text-field>
          </v-col>
          <v-col>
            <v-text-field
              class="inputCustom"
              variant="underlined"
              v-model="cidade"
              label="Cidade"
              :rules="CityRules"
            ></v-text-field>
          </v-col>
        </v-row>

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
      </v-form>
    </v-container>
  </v-container>
</template>

<script>
import { createUser,postImageUser } from "../services/HttpService";

export default {
  name: "RegisterForm",
  data: () => ({
    profileImage: null,
    imagePreview: null,

    email: "",
    nome: "",
    sobrenome: "",
    cpf: "",
    telefone: "",
    endereco: "",
    cidade: "",
    dataNascimento: "",
    password: "",
    showPassword: false,
    successSnackbar: false,
    errorSnackbar: false,

    EmailRules: [
    (value) => !!value || "O e-mail é obrigatório",
    (value) => /.+@.+\..+/.test(value) || "O e-mail é inválido",
    ],
    NameRules: [
      (value) => !!value || "O nome é obrigatório",
      (value) =>
      /^[^!@#$%^&*(),.?":{}|<>]*$/.test(value) || "O nome não pode conter caracteres especiais",
      (value) =>
        /^[A-Za-zÀ-ÿ\s]+$/.test(value) || "O nome não pode conter números",
    ],
    SurnameRules: [
    (value) => !!value || "O sobre nome é obrigatório",
    (value) =>
    /^[^!@#$%^&*(),.?":{}|<>]*$/.test(value) || "O sobre nome não pode conter caracteres especiais",
      (value) =>
        /^[A-Za-zÀ-ÿ\s]+$/.test(value) || "O sobre nome não pode conter números",
    ],
    CpfRules: [
      (value) => !!value || "O CPF é obrigatório",
      (value) =>
        /^\d{3}\.\d{3}\.\d{3}-\d{2}$/.test(value) ||
        "CPF inválido. Formato: 000.000.000-00",
        (value) => !/[a-zA-Z]/.test(value) || "O CPF não pode conter letras", 
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
      if (!value) return "O Endereço é obrigatório";
    
    // Verifica se o valor contém apenas letras, números e espaços (sem caracteres especiais)
      if (/[^A-Za-z0-9\s]/.test(value)) {
      return "O Endereço não pode conter caracteres especiais";
    }
      return true;
    },
    ],
    CityRules: [
      (value) => !!value || "A cidade é obrigatória",
      (value) =>
        /^[A-Za-zÀ-ÿ\s]+$/.test(value) || "A cidade não pode conter números", 
        (value) =>
        /^[^!@#$%^&*(),.?":{}|<>]*$/.test(value) || "A cidade não pode conter caracteres especiais",
        ],
    DataNascimentoRules: [
      (value) => {
      if (!value) {
        return "A Data de Nascimento é obrigatória";
      }

      const today = new Date();
      const birthDate = new Date(value);
      const birthYear = birthDate.getFullYear();

      
      if (birthYear < 1910) {
        return "A data de nascimento deve ser a partir de 1920";
      }

      let age = today.getFullYear() - birthYear;
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
    (value) => !!value || "A senha é obrigatória",
      (value) =>
        (value && value.length >= 8) ||
        "A senha deve ter pelo menos 8 caracteres",
      (value) =>
        /[A-Z]/.test(value) || "A senha deve conter pelo menos uma letra maiúscula", 
      (value) =>
        /[0-9]/.test(value) || "A senha deve conter pelo menos um número",
      (value) =>
        /[a-z]/.test(value) || "A senha deve conter pelo menos uma letra minúscula",
      (value) =>
        /[!@#$%^&*(),.?":{}|<>]/.test(value) || "A senha deve conter pelo menos um caractere especial", 
    ],
  }),
  methods: {
    async submitRegisterForm() {
      const user = {
        email: this.email,
        name: this.nome,
        surname: this.sobrenome,
        cpf: this.cpf,
        telephone: this.telefone,
        adress: this.endereco,
        city: this.cidade,
        date_of_birth: this.dataNascimento,
        password: this.password,
      };
      try {
        await createUser(user);
        this.successSnackbar = true;
        await this.postImage();
      } catch (error) {
        console.error("Erro ao registrar o usuário:", error);
        this.errorSnackbar = true;
      }
    },
    async postImage(){
      const formData = new FormData();
      formData.append('image', this.profileImage);
      try{
         await postImageUser(formData);
      }catch(error){
        console.error('Erro ao enviar imagem:', error);
      }
    },
    handleFileUpload(event) {
      this.profileImage = event.target.files[0];
    },
    previewImage() {
      if (this.profileImage) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.imagePreview = e.target.result;
        };
        reader.readAsDataURL(this.profileImage);
      } else {
        this.imagePreview = null;
      }
    },
  },
};
</script>

<style scoped>
.form {
  background-color: white;
  box-shadow: 0px 3px 1px -2px var(--v-shadow-key-umbra-opacity, rgba(0, 0, 0, 0.2)),
    0px 2px 2px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.14)),
    0px 1px 5px 0px var(--v-shadow-key-ambient-opacity, rgba(0, 0, 0, 0.12));
}
.container {
  width: 900px;
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
.icon {
  font-size: 45px;
  color: #ffa600;
}
.subtitle {
  color: #0a0a0a;
  font-family: Inter;
  font-weight: 300;
}
</style>