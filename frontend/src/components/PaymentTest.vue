<template>
    <div>
      <h1>Fazer Pedido</h1>
      <form @submit.prevent="submitOrder">
        <div v-for="(product, index) in products" :key="product.id">
          <h3>{{ product.name }}</h3>
          <p>Preço: {{ product.price }}</p>
          <label>
            Quantidade:
            <input type="number" v-model.number="product.quantity" min="1" />
          </label>
        </div>
        <button type="submit">Finalizar Pedido</button>
      </form>
  
      <div v-if="preferenceId">
        <h2>Redirecionando para pagamento...</h2>
        <a :href="paymentUrl">Clique aqui se não for redirecionado</a>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        products: [
          { id: 1, name: 'Produto 1', price: 10.00, quantity: 0 },
          { id: 2, name: 'Produto 2', price: 15.00, quantity: 0 },
          // Adicione mais produtos conforme necessário
        ],
        preferenceId: null,
        paymentUrl: null,
      };
    },
    computed: {
      cantinaId() {
        // Defina a lógica para obter o cantinaId
        return this.$route.params.cantinaId; // Ajuste conforme sua lógica
      },
    },
    methods: {
        async submitOrder() {
  try {
    console.log('Cantina ID:', this.cantinaId); // Verifique se este valor é válido
    const orderResponse = await axios.post(`/api/cantinas/${this.cantinaId}/orders`, {
      products: this.products.filter(product => product.quantity > 0),
    });

    if (orderResponse.data.preference) {
      this.preferenceId = orderResponse.data.preference.preference_id;
      this.paymentUrl = `https://www.mercadopago.com.br/checkout/v1/redirect?pref_id=${this.preferenceId}`;
      setTimeout(() => {
        window.location.href = this.paymentUrl;
      }, 2000);
    }
  } catch (error) {
    console.error('Erro ao fazer o pedido:', error.response?.data || error.message);
    alert('Ocorreu um erro ao fazer o pedido. Por favor, tente novamente.');
  }
}
    }
  };
  </script>
  
  <style scoped>
  /* Estilos para o componente */
  </style>
  