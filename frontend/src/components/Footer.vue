<template>
    <v-footer class="bg-amber-darken-2 text-center d-flex flex-column py-4 mt-5">
      <div class="text-dark mb-4">
        <strong>AgilCantina</strong> é um sistema de gestão desenvolvido para otimizar o processo de atendimento e organização em cantinas universitárias, 
        melhorando a experiência dos usuários e administradores. Oferecemos funcionalidades como:
      </div>
  
      <div class="d-flex justify-center flex-wrap text-dark mb-4">
        <span class="mx-2">• Gestão de Pedidos: Pedidos rápidos e sem filas.</span>
        <span class="mx-2">• Controle de Estoque: Monitore e reabasteça produtos facilmente.</span>
        <span class="mx-2">• Monitoramento de Vendas: Relatórios detalhados para decisões estratégicas.</span>
        <span class="mx-2">• Pagamento Integrado: Transações rápidas e seguras.</span>
      </div>
  
      <div>
        <v-btn
          v-for="icon in icons"
          :key="icon"
          :icon="icon"
          class="mx-2 text-white"
          variant="text"
        ></v-btn>
      </div>
      <v-divider class="my-1"></v-divider>
  
      <div class="text-dark">
        {{ new Date().getFullYear() }} — <strong>AgilCantina</strong>. Todos os direitos reservados.
      </div>
    </v-footer>
  </template>
  
  <script>
  export default {
    data: () => ({
      icons: [
        'mdi-facebook',
        'mdi-twitter',
        'mdi-linkedin',
        'mdi-instagram',
      ],
    }),
  };
  </script>
  