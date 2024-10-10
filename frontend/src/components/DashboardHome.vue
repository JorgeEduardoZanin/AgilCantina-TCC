<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="4">
        <v-btn large block  @click="editProfile">
          <v-icon left class="px-5">mdi-domain</v-icon>
          Editar Perfil
        </v-btn>
      </v-col>
      <v-col cols="12" md="4">
        <v-btn large block  @click="editMenu">
          <v-icon left class="px-5">mdi-silverware-fork-knife</v-icon>
          Editar Cardápio
        </v-btn>
      </v-col>
      <v-col cols="12" md="4">
        <v-btn large block  @click="viewOrders">
          <v-icon left class="px-5">mdi-order-bool-descending-variant</v-icon>
          Pedidos
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  methods: {
    editProfile() {
        this.$router.push({path:'/dashboard/profile'})
    },
    editMenu() {
        this.$router.push({path:'/dashboard/menu'})
    },
    viewOrders() {
        this.$router.push({path:'/dashboard/pedidos'})
    }
  }
};
</script>

<style scoped>
.v-btn {
  height: 100px;
  font-size: 20px;
}
.v-icon {
  font-size: 48px; /* Ajusta o tamanho dos ícones */
}
</style>
