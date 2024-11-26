<template>
  <div>
    <div
      v-if="isLoading"
      class="d-flex justify-center align-center"
      style="height: 100vh"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>

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
        <v-row>
          <v-col
            v-if="imagePreview"
            class="d-flex justify-center align-center my-2"
            cols="3"
          >
            <v-img
              :src="imagePreview"
              max-width="200"
              max-height="200"
              class="mt-4"
              alt="Preview da imagem de perfil"
            ></v-img>
          </v-col>
          <v-col class="d-flex justify-center align-center">
            <v-file-input
              label="Escolha uma imagem de perfil da cantina"
              accept="image/*"
              prepend-icon="mdi-camera"
              @change="handleFileChange"
              variant="underlined"
            ></v-file-input>
          </v-col>
        </v-row>

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
        <openingHoursComponent />
        <v-btn
          class="mt-2 updateButton"
          :disabled="!valid"
          @click="postEditCompany"
          color="green"
        >
          Atualizar
        </v-btn>
      </v-form>
    </v-container>
  </div>
</template>

<script>
import {
  updateCompanyProfile,
  getCompanyProfile,
  postImageCompany,
} from "../services/HttpService";
import OpeningHoursComponent from "./OpeningHoursComponent.vue";
import { mapGetters } from "vuex";

export default {
  name: "EditProfile",
  components: { OpeningHoursComponent },
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
      isLoading: true,
      successSnackbar: false,
      errorSnackbar: false,
      profileImage: "",
      imagePreview: "",
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
  mounted() {
    this.getProfileCompany();
  },
  methods: {
    async postEditCompany() {
      this.postImageCompany();
      const canteenId = this.getCanteenId;
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
        const response = await updateCompanyProfile(canteenId,updatedProfile);
        console.log("Perfil atualizado com sucesso:", response);
        this.successSnackbar = true;
      } catch (error) {
        console.error("Erro ao atualizar perfil:", error);
        this.errorSnackbar = true;
      }
    },
    async getProfileCompany() {
      try {
        const canteenId = this.getCanteenId;
        const response = await getCompanyProfile(canteenId);

        const cantina = response.data.cantina;
        this.NomeCantina = cantina.canteen_name || "";
        this.descricao = cantina.description || "";
        this.telefoneAtendimento = cantina.cell_phone || "";
        this.estado = cantina.state || "";
        this.cidade = cantina.city || "";
        this.bairro = cantina.neighborhood || "";
        this.CEP = cantina.cep || "";
      } catch (error) {
      } finally {
        this.isLoading = false;
      }
    },
    async postImageCompany() {
      if (!this.profileImage) {
        console.error("Nenhuma imagem foi selecionada.");
        return;
      }
      try {
        const formData = new FormData();
        formData.append("image", this.profileImage);
        const response = await postImageCompany(formData);
      } catch (error) {
        console.error("Erro ao enviar a imagem:", error);
      }
    },
    handleFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.profileImage = file;
        const reader = new FileReader();
        reader.onload = (e) => {
          this.imagePreview = e.target.result;
        };
        reader.readAsDataURL(file);
        console.log(file);
      } else {
        this.profileImage = null;
        this.imagePreview = null;
      }
    },
  },
  computed: {
    ...mapGetters(["getCanteenId"]),
  },
};
</script>

<style scoped></style>
