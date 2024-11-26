<template>
  <v-container>
    <div
      v-if="isLoading"
      class="d-flex justify-center align-center"
      style="height: 100vh"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>

    <v-snackbar v-model="errorSnackbar" timeout="15000" top color="error">
      {{ errorMensage }}
      <template v-slot:actions>
        <v-btn flat variant="text" @click="errorSnackbar = false"> X </v-btn>
      </template>
    </v-snackbar>
    <v-snackbar v-model="mensageSnackbar" timeout="15000" top color="success">
      {{ Mensage }}
      <template v-slot:actions>
        <v-btn flat variant="text" @click="mensageSnackbar = false"> X </v-btn>
      </template>
    </v-snackbar>
    <h1>Controle de Cantinas
    </h1>
    <v-row>
      <v-col
        cols="12"
        md="6"
        lg="4"
        v-for="canteen in canteens"
        :key="canteen.id"
      >
        <v-card outlined>
          <v-card-title class="py-4">{{ canteen.canteen_name }}</v-card-title>
          <v-card-text>
            <v-row>
              <v-col cols="12" md="6">
                <strong>Razão Social:</strong> {{ canteen.corporate_reason }}
              </v-col>
              <v-col cols="12" md="6">
                <strong>CNPJ:</strong> {{ canteen.cnpj }}
              </v-col>
              <v-col cols="12" md="6">
                <strong>Responsável:</strong>
                {{ canteen.name_of_person_responsible }}
              </v-col>
              <v-col cols="12" md="6">
                <strong>Contato Responsável:</strong>
                {{ canteen.phone_of_responsible }}
              </v-col>
              <v-col cols="12" md="6">
                <strong>Telefone:</strong> {{ canteen.cell_phone }}
              </v-col>
              <v-col cols="12">
                <strong>Descrição:</strong> {{ canteen.description }}
              </v-col>
              <v-col cols="12">
                <strong>Endereço:</strong>
                {{ canteen.neighborhood }}, {{ canteen.city }},
                {{ canteen.state }} - CEP: {{ canteen.cep }}
              </v-col>
              <v-col cols="12">
                <strong>Horário de Funcionamento:</strong>
                {{ canteen.opening_hours }}
              </v-col>
              <v-col cols="12">
                <strong>Status:</strong> {{ canteen.status }}
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-btn
              color="success"
              variant="elevated"
              @click="openApproveModal(canteen.id)"
              >Aprovar</v-btn
            >
            <v-btn
              color="error"
              variant="outlined"
              @click="openRejectModal(canteen.id)"
              >Reprovar</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>

    <v-dialog v-model="approveDialog" max-width="400">
      <v-card>
        <v-card-title class="text-h6">Confirmar Aprovação</v-card-title>
        <v-card-text>
          Tem certeza que deseja aprovar esta cantina?
        </v-card-text>
        <v-card-actions>
          <v-btn variant="outlined" text @click="approveDialog = false">
            Cancelar
          </v-btn>
          <v-btn color="success" variant="elevated" @click="confirmApprove">
            Aprovar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="rejectDialog" max-width="400">
      <v-card>
        <v-card-title class="text-h6">Confirmar Reprovação</v-card-title>
        <v-card-text>
          Tem certeza que deseja reprovar esta cantina?
        </v-card-text>
        <v-card-actions>
          <v-btn variant="outlined" text @click="rejectDialog = false">
            Cancelar
          </v-btn>
          <v-btn color="error" variant="elevated" @click="confirmReject">
            Reprovar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import {
  getCanteensPending,
  patchAprovedCanteen,
  patchDesapproveCanteen,
} from "@/services/HttpService";

export default {
  data() {
    return {
      isLoading: true,
      errorMensage: "",
      errorSnackbar: false,
      Mensage: "",
      mensageSnackbar: false,
      canteens: [],
      approveDialog: false,
      rejectDialog: false,
      selectedCanteenId: null,
    };
  },
  methods: {
    openApproveModal(canteenId) {
      this.selectedCanteenId = canteenId;
      this.approveDialog = true;
    },
    openRejectModal(canteenId) {
      this.selectedCanteenId = canteenId;
      this.rejectDialog = true;
    },
    async confirmApprove() {
      this.approveDialog = false;
      await this.approveCanteen();
    },
    async confirmReject() {
      this.rejectDialog = false;
      await this.rejectCanteen();
    },
    async approveCanteen() {
      try {
        const response = await patchAprovedCanteen(this.selectedCanteenId);
        this.Mensage = "Cantina aprovada com sucesso!";
        this.mensageSnackbar = true;
        this.getCanteens();
      } catch (error) {
        this.errorMensage = error.response?.data?.error || "Erro desconhecido";
        this.errorSnackbar = true;
      }
    },
    async rejectCanteen() {
      try {
        const response = await patchDesapproveCanteen(this.selectedCanteenId);
        console.log(`Reprovar cantina ${this.selectedCanteenId}`);
        this.Mensage = "Cantina reprovada com sucesso!";
        this.mensageSnackbar = true;
        this.getCanteens();
      } catch (error) {
        this.errorMensage = error.response?.data?.error || "Erro desconhecido";
        this.errorSnackbar = true;
      }
    },
    async getCanteens() {
      try {
        const response = await getCanteensPending();
        this.canteens = response.data["Cantinas pendentes"];
      } catch (error) {
        console.error("Erro ao buscar cantinas pendentes:", error);
      } finally {
        this.isLoading = false;
      }
    },
  },
  mounted() {
    this.getCanteens();
  },
};
</script>
