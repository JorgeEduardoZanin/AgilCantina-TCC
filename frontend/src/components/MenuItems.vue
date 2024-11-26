<template>
  <div>
    <div
      v-if="isLoading"
      class="d-flex justify-center align-center"
      style="height: 100vh"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>

    <v-data-table
      :headers="headers"
      :items="products"
      :sort-by="[{ key: 'Nome', order: 'asc' }]"
    >
      <template v-slot:top>
        <v-toolbar color="amber-accent-4">
          <v-toolbar-title class="d-flex align-center">
            <v-icon class="mr-2">mdi-silverware-fork-knife</v-icon>
            Cardápio
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ props }">
              <v-btn v-bind="props" variant="elevated" class="m-2"
                ><v-icon class="px-3">mdi-plus</v-icon>Novo Produto</v-btn
              >
            </template>
            <v-card>
              <v-card-title>
                <h4 class="px-4 pt-4">{{ formTitle }}</h4>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-text-field
                    v-model="editedItem.nome"
                    label="Nome"
                    variant="underlined"
                  ></v-text-field>
                  <v-text-field
                    v-model="editedItem.descricao"
                    label="Descrição"
                    variant="underlined"
                  ></v-text-field>
                  <v-text-field
                    v-model="editedItem.preco"
                    label="Preço (R$)"
                    variant="underlined"
                  ></v-text-field>
                  <v-text-field
                    v-model="editedItem.preco_custo"
                    label="Preço de custo (R$)"
                    variant="underlined"
                  ></v-text-field>
                  <v-text-field
                    v-model="editedItem.quantidade"
                    label="Quantidade (unidades)"
                    variant="underlined"
                  ></v-text-field>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" variant="text" @click="close"
                  >Cancelar</v-btn
                >
                <v-btn variant="text" @click="save">Salvar</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
              <v-card-title class="text-h5">
                Tem certeza que deseja excluir este item?
              </v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-darken-1" variant="text" @click="closeDelete"
                  >Cancelar</v-btn
                >
                <v-btn
                  color="blue-darken-1"
                  variant="text"
                  @click="deleteItemConfirm"
                  >Sim</v-btn
                >
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon class="me-2" size="small" @click="editItem(item)"
          >mdi-pencil</v-icon>
        <v-icon size="small" @click="deleteItem(item)">mdi-delete</v-icon>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import {
  createProduct,
  deleteProduct,
  editProduct,
  getProducts,
} from "@/services/HttpService";

export default {
  data: () => ({
    isLoading: true,
    dialog: false,
    dialogDelete: false,
    headers: [
      { title: "Imagem", align: "start", sortable: false, key: "Imagem" },
      { title: "Nome", key: "Nome" },
      { title: "Descrição", key: "Descricao" },
      { title: "Preço (R$)", key: "Preco" },
      { title: "Preço de Custo(R$)", key: "Preco_custo" },
      { title: "Quantidade (unidade)", key: "Quantidade" },
      { title: "Ações", key: "actions", sortable: false },
    ],
    products: [],
    editedIndex: -1,
    editedItem: {
      nome: "",
      descricao: "",
      preco: "",
      preco_custo: "",
      quantidade: "",
      img: "",
      id: "",
    },
    selectedItem: null,
    defaultItem: {
      nome: "",
      descricao: "",
      preco: "",
      preco_custo: "",
      quantidade: "",
      img: "",
      id: "",
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
    this.getProducts();
  },

  methods: {
    async getProducts() {
      try {
        const id = localStorage.getItem("canteen_id");
        const response = await getProducts(id);

        this.products = response.data.map((product) => ({
          Id: product.id,
          Imagem: product.img,
          Nome: product.name,
          Descricao: product.description,
          Preco: product.price.toFixed(2),
          Preco_custo: product.cost_price.toFixed(2),
          Quantidade: product.quantity.toString(),
        }));
      } catch (error) {
        console.error("Erro ao obter produtos:", error);
      }finally{
        this.isLoading = false;
      }
    },

    editItem(item) {
      this.editedIndex = this.products.indexOf(item);
      this.editedItem = {
        nome: item.Nome,
        descricao: item.Descricao,
        preco: item.Preco,
        preco_custo: item.Preco_custo,
        quantidade: item.Quantidade,
        img: item.Imagem,
        id: item.Id,
      };
      this.dialog = true;
    },

    deleteItem(item) {
      this.selectedItem = item;
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      try {
        if (this.selectedItem) {
          await deleteProduct(this.selectedItem.Id);
          const index = this.products.indexOf(this.selectedItem);
          if (index > -1) {
            this.products.splice(index, 1);
          }
        }
        this.closeDelete();
      } catch (error) {
        console.error("Erro ao deletar o produto:", error);
      }
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.selectedItem = null;
      });
    },

    async save() {
      if (this.editedIndex > -1) {
        await this.postEditProduct();
      } else {
        await this.postNewProduct();
      }
      this.close();
    },

    async postNewProduct() {
      try {
        const product = {
          name: this.editedItem.nome,
          price: parseFloat(this.editedItem.preco),
          description: this.editedItem.descricao,
          quantity: parseInt(this.editedItem.quantidade),
          availability: true,
          img: this.editedItem.img || "",
          cost_price: this.editedItem.preco_custo,
        };

        await createProduct(product);
        this.getProducts();
      } catch (error) {
        console.error("Erro ao criar o produto:", error);
      }
    },

    async postEditProduct() {
      try {
        const product = {
          name: this.editedItem.nome,
          price: parseFloat(this.editedItem.preco),
          cost_price : this.editedItem.preco_custo,
          description: this.editedItem.descricao,
          quantity: parseInt(this.editedItem.quantidade),
          availability: true,
          img: this.editedItem.img || "",
        };
        await editProduct(this.editedItem.id, product);
        this.getProducts();
      } catch (error) {
        console.error("Erro ao editar o produto:", error);
      }
    },
  },
};
</script>

<style scoped>
*{
  font-family: Inter;
}
</style>