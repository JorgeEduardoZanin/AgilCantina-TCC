<template>
  <div>
    <v-snackbar
      v-model="successSnackbar"
      timeout="15000"
      location="top"
      color="success"
    >
      Informações atualizadas com sucesso!
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
      Ocorreu um erro ao atualizar as informações.
      <template #actions>
        <v-btn variant="text" @click="errorSnackbar = false">X</v-btn>
      </template>
    </v-snackbar>

    <v-toolbar color="amber-accent-4">
      <v-toolbar-title class="d-flex align-center">
        <v-icon class="mr-2">mdi-domain</v-icon>
        Perfil
      </v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-container class="mainEditProfileForm" fluid>
      <v-form ref="form" v-model="valid">
        <v-text-field
          class="inputCustom"
          v-model="NomeCantina"
          :rules="NameCanteenRules"
          label="Nome da Cantina"
          variant="underlined"
        ></v-text-field>

        <v-textarea
          class="inputCustom"
          v-model="descricao"
          label="Descrição da Cantina"
          clearable
          variant="underlined"
        ></v-textarea>
        <v-row>
          <v-col>
            <v-text-field
              class="inputCustom"
              v-model="telefoneAtendimento"
              :rules="TelefoneRules"
              label="Telefone de Atendimento"
              variant="underlined"
            ></v-text-field>
          </v-col>
          <v-col>
            <v-text-field
              class="inputCustom"
              v-model="CEP"
              :rules="CepRules"
              label="CEP"
              variant="underlined"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="2">
            <v-text-field
              class="inputCustom"
              v-model="estado"
              :rules="StateRules"
              label="IF"
              variant="underlined"
            ></v-text-field>
          </v-col>
          <v-col>
            <v-text-field
              class="inputCustom"
              v-model="cidade"
              :rules="CityRules"
              label="Cidade"
              variant="underlined"
            ></v-text-field>
          </v-col>
          <v-col>
            <v-text-field
              class="inputCustom"
              v-model="bairro"
              :rules="BairroRules"
              label="Bairro"
              variant="underlined"
            ></v-text-field>
          </v-col>
        </v-row>
        <openingHoursComponent/>
        <v-btn
          class="mt-2 updateButton"
          :disabled="!valid"
          @click="submitForm"
          color="green"
        >
          Atualizar
        </v-btn>
      </v-form>
    </v-container>
  </div>
</template>

<script>
import { updateCompanyProfile } from "../services/HttpService";
import OpeningHoursComponent from "./OpeningHoursComponent.vue";

export default {
  name: "EditProfile",
  components:{OpeningHoursComponent},
  data() {
    return {
      valid: false,
      NomeCantina: "",
      descricao: "",
      telefoneAtendimento: "",
      estado: "",
      cidade: "",
      bairro: "",
      CEP: "",
      successSnackbar: false,
      errorSnackbar: false,

      NameCanteenRules: [
        (value) => !!value || "O Nome da Cantina é obrigatório",
      ],
      TelefoneRules: [
        (value) => !!value || "O Telefone é obrigatório",
        (value) =>
          /^\(\d{2}\) \d{4,5}-\d{4}$/.test(value) ||
          "Telefone inválido. Formato: (00) 00000-0000",
      ],
      StateRules: [
        (value) => !!value || "O IF é obrigatório",
        (value) => /^[A-Z]{2}$/.test(value) || "Estado inválido.",
      ],
      CityRules: [(value) => !!value || "A cidade é obrigatória"],
      BairroRules: [(value) => !!value || "O bairro é obrigatório"],
      CepRules: [
        (value) => !!value || "O CEP é obrigatório",
        (value) =>
          /^\d{5}-\d{3}$/.test(value) || "CEP inválido. Formato: 00000-000",
      ],
    };
  },
  methods: {
    async postEditCompany() {
      const updatedProfile = {
        NomeCantina: this.NomeCantina,
        descricao: this.descricao,
        telefoneAtendimento: this.telefoneAtendimento,
        estado: this.estado,
        cidade: this.cidade,
        bairro: this.bairro,
        CEP: this.CEP,
      };

      try {
        const response = await updateCompanyProfile(updatedProfile);
        console.log("Perfil atualizado com sucesso:", response);
        this.successSnackbar = true;
      } catch (error) {
        console.error("Erro ao atualizar perfil:", error);
        this.errorSnackbar = true;
      }
    },
    async getProfileCompany(){
        try{

            const response = await getCompanyProfile(id)
        }catch{

        }finally{
            
        }
    }
  },
};
</script>

<style scoped></style>
