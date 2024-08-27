<template>
      <v-container>
    <v-list-item>
      <v-list-item-content>
        <v-edit-dialog
          v-model="editing"
          :return-value.sync="name"
          @save="saveName"
          @cancel="cancelEdit"
        >
          <v-text-field
            v-model="name"
            label="Editar nome"
            single-line
            hide-details
          ></v-text-field>
          <template v-slot:input>
            <v-list-item-title>{{ name }}</v-list-item-title>
          </template>
        </v-edit-dialog>
      </v-list-item-content>
    </v-list-item>
  </v-container>

</template>

<script>
export default {
  data() {
    return {
      editing: false,
      name: 'Clique para editar',
    };
  },
  methods: {
    saveName() {
      // Código para salvar o nome editado
    },
    cancelEdit() {
      // Código para cancelar a edição
    },
  },
};
</script>

<style scoped>
input{
  background-color: red;
}
</style>