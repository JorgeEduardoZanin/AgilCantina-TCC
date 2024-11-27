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
      v-else
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
                    v-model="editedItem.nome"
                    label="Nome"
                    :rules="NomeRules"
                    variant="underlined"
                  ></v-text-field>
                  <v-text-field
                    v-model="editedItem.descricao"
                    label="Descrição"
                    :rules="DescricaoRules"
                    variant="underlined"
                  ></v-text-field>
                  <v-text-field
                    v-model="editedItem.preco"
                    label="Preço (R$)"
                    :rules="PrecoRules"
                    variant="underlined"
                  ></v-text-field>
                  <v-text-field
                    v-model="editedItem.preco_custo"
                    label="Preço de custo (R$)"
                    :rules="PrecoDeCustoRules"
                    variant="underlined"
                  ></v-text-field>
                  <v-text-field
                    v-model="editedItem.quantidade"
                    label="Quantidade (unidades)"
                    :rules="QuantidadeRules"
                    variant="underlined"
                  ></v-text-field>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="dark" variant="text" @click="close"
                  >Cancelar</v-btn
                >
                <v-btn variant="elevated" @click="save" color="amber-darken-2">Salvar</v-btn>
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
      <template v-slot:item.Imagem="{ item }">
        <v-img
          :src="item.Imagem"
          max-height="100"
          max-width="100"
          contain
          class="mt-3"
        ></v-img>
      </template>

      <template v-slot:item.actions="{ item }">
        <v-icon class="me-2" size="small" @click="editItem(item)"
          >mdi-pencil</v-icon
        >
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
  getImageProduct,
  getProducts,
  postImageProduct,
} from "@/services/HttpService";

export default {
  data: () => ({
    isLoading: true,
    dialog: false,
    dialogDelete: false,
    profileImage: "",
    imagePreview: "",

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

  NomeRules: [
    (value) => !!value || "O nome é obrigatório.",
    (value) => /^[^0-9]*$/.test(value) || "O nome não pode conter números.",
    (value) => value.length <= 40 || "O nome não pode exceder 40 caracteres.",
  ],
  PrecoRules: [
    (value) => !!value || "O preço é obrigatório.",
    (value) => /^[0-9.]*$/.test(value) || "O preço deve conter apenas números e pontos.",
    (value) => value.split(".").length <= 2 || "O preço pode ter apenas um ponto decimal.",
    (value) => value.length <= 5 || "O preço não pode ter mais de 5 dígitos.",
  ],
  PrecoDeCustoRules: [
    (value) => !!value || "O preço de custo é obrigatório.",
    (value) => /^[0-9.]*$/.test(value) || "O preço de custo deve conter apenas números e pontos.",
    (value) => value.split(".").length <= 2 || "O preço de custo pode ter apenas um ponto decimal.",
    (value) => value.length <= 5 || "O preço de custo não pode ter mais de 5 dígitos.",
  ],
  QuantidadeRules: [
    (value) => !!value || "A quantidade é obrigatória.",
    (value) => /^[0-9]*$/.test(value) || "A quantidade deve conter apenas números.",
    (value) => value.length <= 5 || "A quantidade não pode ter mais de 5 dígitos.",
  ],
  DescricaoRules: [
    (value) => !!value || "A descrição é obrigatória.",
    (value) => value.length <= 80 || "A descrição não pode exceder 80 caracteres.",
    ],
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

        this.products = await Promise.all(
          response.data.map(async (product) => {
            try {
              const imageResponse = await getImageProduct(product.id);
              const imageUrl = imageResponse.data?.image_url || ""; 

              return {
                Id: product.id,
                Imagem: imageUrl,
                Nome: product.name,
                Descricao: product.description,
                Preco: product.price.toFixed(2),
                Preco_custo: product.cost_price.toFixed(2),
                Quantidade: product.quantity.toString(),
              };
            } catch (error) {
              console.error(
                `Erro ao buscar imagem para o produto ${product.id}:`,
                error
              );
              return {
                Id: product.id,
                Nome: product.name,
                Descricao: product.description,
                Preco: product.price.toFixed(2),
                Preco_custo: product.cost_price.toFixed(2),
                Quantidade: product.quantity.toString(),
              };
            }
          })
        );
      } catch (error) {
        console.error("Erro ao obter produtos:", error);
      } finally {
        this.isLoading = false;
      }
    },

    editItem(item) {
      this.editedIndex = this.products.indexOf(item);
      this.editedItem = {
        nome: item.Nome,
        preco: item.Preco,
        descricao: item.Descricao,
        quantidade: item.Quantidade,
        preco_custo: item.Preco_custo,
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
        this.getProducts();
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
      try {
        let productId;

        if (this.editedIndex > -1) {
          await this.postEditProduct();
          productId = this.editedItem.id;
        } else {
          productId = await this.postNewProduct();
        }

        if (this.profileImage) {
          await this.postImageProduct(productId);
        }

        this.close();
      } catch (error) {
        console.error("Erro ao salvar o produto:", error);
      }
    },

    async postNewProduct() {
      try {
        const product = {
          name: this.editedItem.nome,
          price: parseFloat(this.editedItem.preco),
          description: this.editedItem.descricao,
          quantity: parseInt(this.editedItem.quantidade),
          availability: true,
          cost_price: this.editedItem.preco_custo,
        };

        const response = await createProduct(product);
        return response.data.product.id;
      } catch (error) {
        console.error("Erro ao criar o produto:", error);
      }
    },

    async postEditProduct() {
      try {
        const product = {
          name: this.editedItem.nome,
          price: parseFloat(this.editedItem.preco),
          cost_price: this.editedItem.preco_custo,
          description: this.editedItem.descricao,
          quantity: parseInt(this.editedItem.quantidade),
          availability: true,
        };
        await editProduct(this.editedItem.id, product);
        this.getProducts();
      } catch (error) {
        console.error("Erro ao editar o produto:", error);
      }
    },
    async postImageProduct(productId) {
      if (!this.profileImage) {
        console.error("Nenhuma imagem foi selecionada.");
        return;
      }

      try {
        const formData = new FormData();
        formData.append("image", this.profileImage);

        await postImageProduct(productId, formData);
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
    async getImageProduct() {
      try {
        const response = await getImageProduct(productId);
      } catch {}
    },
  },
};
</script>

<style scoped>
* {
  font-family: Inter;
}
</style>
