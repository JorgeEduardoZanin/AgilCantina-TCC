<template>
    <v-dialog :value="isOpen" max-width="500px" @input="updateIsOpen">
      <v-card>
        <v-card-title>
          <span class="text-h5">Configurações</span>
          <v-spacer></v-spacer>
          <v-btn icon @click="closeModal">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <!-- Adicione aqui os campos para edição de informações -->
          <v-text-field label="Nome" v-model="nome" />
          <v-text-field label="Sobrenome" v-model="sobrenome" />
          <!-- Adicione outros campos de configuração conforme necessário -->
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="saveSettings">Salvar</v-btn>
          <v-btn color="secondary" @click="closeModal">Cancelar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </template>
  
  <script>
  export default {
    name: "SettingsModal",
    props: {
      isOpen: {
        type: Boolean,
        default: false,
      },
    },
    data() {
      return {
        nome: "",       // Carregue as informações iniciais conforme necessário
        sobrenome: "",  // Carregue as informações iniciais conforme necessário
      };
    },
    methods: {
      closeModal() {
        this.$emit("update:isOpen", false);
      },
      saveSettings() {
        // Realize as operações de salvamento, como atualizar o Vuex ou fazer uma API call
        this.$emit("save", { nome: this.nome, sobrenome: this.sobrenome });
        this.closeModal();
      },
      updateIsOpen(value) {
        if (!value) this.closeModal();
      }
    },
    watch: {
      isOpen(newVal) {
        if (newVal) {
          // Carregue os dados atuais de nome e sobrenome quando o modal abrir
          this.nome = this.$store.getters.getName;
          this.sobrenome = this.$store.getters.getSurname;
        }
      },
    },
  };
  </script>
  