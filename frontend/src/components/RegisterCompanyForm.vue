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
      class="mainRegisterForm d-flex justify-center align-center"
      fluid
      fill-height
    >
      <div class="content">
        <div class="d-flex"> 
        <v-icon class="icon py-2 pr-1">bi bi-building</v-icon>
        <h2 class="title py-2 px-2">Cadastro</h2>
      </div>
        <h5 class="subtitle pb-2">
          Registre sua cantina e leve praticidade ao seu negócio. Facilite
          pedidos, otimize o atendimento e ofereça uma experiência única para
          seus clientes!
        </h5>
        <v-stepper
          editable
          prev-text="Voltar"
          next-text="Proximo"
          :items="['Empresa', 'Proprietário', 'Login']"
          class="teste"
        >
          <template v-slot:item.1>
            <h5 class="p-2 subtitle">Empresa</h5>
            <v-text-field
                  class="inputCustom"
                  variant="underlined"
                  v-model="NomeCantina"
                  :rules="NameCanteenRules"
                  label="Nome da Cantina"
                ></v-text-field>
            <v-row>
              <v-col>
                <v-text-field
                  class="inputCustom"
                  variant="underlined"
                  v-model="razaoSocial"
                  :rules="CorporateReasonRules"
                  label="Razão Social"
                ></v-text-field>
              </v-col>
              <v-col cols="4">
                <v-text-field
                  class="inputCustom"
                  variant="underlined"
                  v-model="cnpj"
                  label="CNPJ"
                  :rules="CnpjRules"
                  maxlength="18"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-text-field
              class="inputCustom"
              variant="underlined"
              v-model="telefoneAtendimento"
              label="Telefone de atendimento"
              :rules="TelefoneRules"
            ></v-text-field>
            <v-row>
              <v-col cols="2">
                <v-text-field
                  class="inputCustom"
                  variant="underlined"
                  v-model="estado"
                  label="IF"
                  :rules="StateRules"
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
              <v-col>
                <v-text-field
                  class="inputCustom"
                  variant="underlined"
                  v-model="bairro"
                  label="Bairro"
                  :rules="BairroRules"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-text-field
              class="inputCustom"
              variant="underlined"
              v-model="CEP"
              label="CEP"
              :rules="CepRules"
            ></v-text-field>
            <v-textarea
              clearable
              variant="underlined"
              v-model="descricao"
              label="Descrição da sua Cantina"
            ></v-textarea>
            <OpeningHoursComponent @update-opening-hours="updateOpeningHours" />
          </template>

          <template v-slot:item.2>
            <h5 class="p-2 subtitle">Proprietário</h5>
            <v-row>
              <v-col>
                <v-text-field
                  class="inputCustom"
                  variant="underlined"
                  v-model="nome"
                  :rules="NameRules"
                  label="Nome "
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
              maxlength="14"
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
              v-model="telefone"
              label="Telefone"
              :rules="TelefoneRules"
            ></v-text-field>
            <v-text-field
              class="inputCustom"
              variant="underlined"
              v-model="dataNascimento"
              label="Data de Nascimento"
              :rules="DataNascimentoRules"
              type="date"
            ></v-text-field>
          </template>
          <template v-slot:item.3>
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
              label="Senha"
              :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
              @click:append-inner="showPassword = !showPassword"
            ></v-text-field>
            <v-btn
              class="mt-2 registerButton"
              type="submit"
              block
              size="large"
              @click="submitForm()"
            >
              Registrar
            </v-btn>
          </template>
        </v-stepper>
      </div>
    </v-container>
  </div>
</template>

<script>
import { createCompanyUser } from "../services/HttpService";
import OpeningHoursComponent from "./OpeningHoursComponent.vue";

export default {
  name: "RegisterCompanyForm",
  components: { OpeningHoursComponent },
  data: () => ({
    //proprietario
    email: "",
    nome: "",
    sobrenome: "",
    cpf: "",
    telefone: "",
    endereco: "",
    dataNascimento: "",
    password: "",
    //empresa
    NomeCantina: "",
    razaoSocial: "",
    cnpj: "",
    telefoneAtendimento: "",
    estado: "",
    cidade: "",
    bairro: "",
    CEP: "",
    descricao: "",
    image: "",

    openingHours: [],

    showPassword: false,
    successSnackbar: false,
    errorSnackbar: false,

    EmailRules: [
      (value) => !!value || "O e-mail é obrigatório",
      (value) => /.+@.+\..+/.test(value) || "O e-mail é inválido",
    ],
    NameRules: [(value) => !!value || "O nome é obrigatório"],
    SurnameRules: [(value) => !!value || "O Sobrenome é obrigatório"],

    NameCanteenRules: [(value) => !!value || "O Nome da Cantina é obrigatorio"],
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
    StateRules: [
      (value) => !!value || "O IF é obrigatório",
      (value) => /^[A-Z]{2}$/.test(value) || "Estado inválido.",
      (value) => {
        const estadosValidos = [
          "AC",
          "AL",
          "AP",
          "AM",
          "BA",
          "CE",
          "DF",
          "ES",
          "GO",
          "MA",
          "MT",
          "MS",
          "MG",
          "PA",
          "PB",
          "PR",
          "PE",
          "PI",
          "RJ",
          "RN",
          "RS",
          "RO",
          "RR",
          "SC",
          "SP",
          "SE",
          "TO",
        ];
        return estadosValidos.includes(value) || "Estado inválido";
      },
    ],
    CityRules: [(value) => !!value || "A cidade é obrigatória"],
    BairroRules: [(value) => !!value || "O bairro é obrigatório"],
    CepRules: [
      (value) => !!value || "O CEP é obrigatório",
      (value) =>
        /^\d{5}-\d{3}$/.test(value) || "CEP inválido. Formato: 00000-000",
    ],
  }),
  methods: {
    async submitForm() {
      const formattedOpeningHours = this.openingHours
      .map(item => `${item.day} ${item.time}`)
      .join(',')

      const user = {
        name: this.nome,
        surname: this.sobrenome,
        cpf: this.cpf,
        adress: this.endereco,
        telephone: this.telefone,
        date_of_birth: this.dataNascimento,
        email: this.email,
        password: this.password,
        canteen_name: this.NomeCantina,
        corporate_reason: this.razaoSocial,
        cnpj: this.cnpj,
        cell_phone: this.telefoneAtendimento,
        state: this.estado,
        city: this.cidade,
        neighborhood: this.bairro,
        cep: this.CEP,
        name_of_person_responsible: this.nome,
        phone_of_responsible: this.telefone,
        description: this.descricao,
        opening_hours: formattedOpeningHours,
        open: false,
        img: this.image,
      };
      console.log(user);
      try {
        const response = await createCompanyUser(user);
        console.log("Usuário registrado com sucesso:", response);
        this.successSnackbar = true;
      } catch (error) {
        console.error("Erro ao registrar o usuário:", error);
        this.errorSnackbar = true;
      }
    },
    updateOpeningHours(formattedTimes) {
      this.openingHours = formattedTimes;
      console.log('Horários atualizados:', this.openingHours);
    }
  },
};
</script>

<style scoped>
.v-stepper :deep(.v-stepper-item__avatar) {
  background-color: #ffa600;
  color: #010100;
}
.v-stepper :deep(.v-stepper-item__avatar) {
  width: 40px !important;
  height: 40px !important;
  font-size: 1rem !important;
  background-color: #ffa600;
}
.v-stepper :deep(.v-stepper-actions__btn) {
  color: #010100;
  background-color: #ffa600;
}
.content {
  width: 1000px;
}
.icon{
  font-size: 45px;
  color: #ffa600;
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
