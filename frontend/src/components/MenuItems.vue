<template>
  <v-data-table
    :headers="headers"
    :items="products"
    :sort-by="[{ key: 'nome', order: 'asc' }]"
  >
    <template v-slot:top>
      <v-toolbar>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ props }">
            <v-btn class="mb-2" dark v-bind="props"> Novo Produto </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="text-h5">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-text-field
                  v-model="editedItem.nome"
                  label="Nome"
                ></v-text-field>

                <v-text-field
                  v-model="editedItem.descricao"
                  label="Descriçao"
                ></v-text-field>

                <v-text-field
                  v-model="editedItem.preco"
                  label="Preço (R$)"
                ></v-text-field>

                <v-text-field
                  v-model="editedItem.quantidade"
                  label="Quantidade (unidades)"
                ></v-text-field>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="error" variant="text" @click="close">
                Cancelar
              </v-btn>
              <v-btn variant="text" @click="save"> Salvar </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5"
              >Are you sure you want to delete this item?</v-card-title
            >
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="closeDelete"
                >Cancel</v-btn
              >
              <v-btn
                color="blue-darken-1"
                variant="text"
                @click="deleteItemConfirm"
                >OK</v-btn
              >
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon class="me-2" size="small" @click="editItem(item)">
        mdi-pencil
      </v-icon>
      <v-icon size="small" @click="deleteItem(item)"> mdi-delete </v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn @click="initialize"> Reiniciar </v-btn>
    </template>
  </v-data-table>
</template>

<script>
export default {
  data: () => ({
    dialog: false,
    dialogDelete: false,
    headers: [
      {
        title: "Imagem",
        align: "start",
        sortable: false,
        key: "Imagem",
      },
      { title: "Nome", key: "Nome" },
      { title: "Descrição", key: "Descricao" },
      { title: "Preço (R$)", key: "Preco" },
      { title: "Quantidade (unidade)", key: "Quantidade" },
      { title: "Actions", key: "actions", sortable: false },
    ],
    products: [],
    editedIndex: -1,
    editedItem: {
      nome: "",
      descricao: "",
      preco: "",
      quantidade: "",
    },
    defaultItem: {
      nome: "",
      descricao: "",
      preco: "",
      quantidade: "",
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Novo Produto" : "Editar Produto";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    initialize() {
      this.products = [
        {
          Imagem: "imagem aqui",
          Nome: "Coxinha de frango",
          Descricao:
            "Coxinha de frango com 150g, massa de farinha de trigo e recheada com frango desfiado e catupiry, temperada com cebola, alho, sal, pimenta-do-reino e salsinha. Ela é empanada em farinha de rosca e frita até ficar crocante e dourada. Perfeita para um lanche simples e delicioso.",
          Preco: "6,00",
          Quantidade: "10",
        },
        {
          Imagem: "imagem aqui",
          Nome: "Empada de Palmito",
          Descricao:
            "Empada com massa amanteigada, recheada com palmito fresco e temperos especiais, proporcionando uma combinação cremosa e saborosa.",
          Preco: "5,50",
          Quantidade: "8",
        },
        {
          Imagem: "imagem aqui",
          Nome: "Kibe",
          Descricao:
            "Kibe crocante por fora e macio por dentro, recheado com carne moída bem temperada e ervas frescas, ideal para um lanche leve.",
          Preco: "4,50",
          Quantidade: "15",
        },
        {
          Imagem: "imagem aqui",
          Nome: "Pastel de Queijo",
          Descricao:
            "Pastel frito com massa fina e crocante, recheado com queijo derretido, perfeito para acompanhar uma bebida gelada.",
          Preco: "3,50",
          Quantidade: "20",
        },
      ];
    },

    editItem(item) {
      this.editedIndex = this.products.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.products.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      this.products.splice(this.editedIndex, 1);
      this.closeDelete();
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    save() {
      if (this.editedIndex > -1) {
        Object.assign(this.products[this.editedIndex], this.editedItem);
      } else {
        this.products.push(this.editedItem);
      }
      this.close();
    },
  },
};
</script>

<style></style>
 