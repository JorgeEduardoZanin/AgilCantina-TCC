<template>
  <v-container fluid>
    <!-- Introdução -->
    <v-row justify="center">
      <v-col cols="12" md="8">
        <v-card class="elevation-2">
          <v-card-title class="text-h6">Ajuda - AgilCantina</v-card-title>
          <v-card-text>
            O AgilCantina é um sistema de gestão projetado para otimizar o
            atendimento e organização em cantinas universitárias. Ele visa
            melhorar a experiência dos usuários e facilitar o trabalho dos
            administradores da cantina.
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Funcionalidades -->
    <v-row class="mt-5" justify="center">
      <v-col cols="12" md="8">
        <v-card class="elevation-2">
          <v-card-title class="text-h6"
            >Funcionalidades Principais</v-card-title
          >
          <v-card-text>
            <v-list dense>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title
                    ><v-icon color="amber-darken-2" class="px-4"
                      >mdi-cart</v-icon
                    ><strong>Gestão de Pedidos:</strong></v-list-item-title
                  >
                  <v-list-item-subtitle
                    >Faça pedidos rapidamente, evitando longas
                    filas.</v-list-item-subtitle
                  >
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title
                    ><v-icon color="amber-darken-2" class="px-4"
                      >mdi-cube</v-icon
                    ><strong>Controle de Estoque:</strong></v-list-item-title
                  >
                  <v-list-item-subtitle
                    >Acompanhe o estoque de produtos, evitando
                    desperdícios.</v-list-item-subtitle
                  >
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title
                    ><v-icon color="amber-darken-2" class="px-4"
                      >mdi-chart-line</v-icon
                    ><strong
                      >Monitoramento de Vendas:</strong
                    ></v-list-item-title
                  >
                  <v-list-item-subtitle
                    >Acompanhe as vendas e lucros da cantina com relatórios
                    detalhados.</v-list-item-subtitle
                  >
                </v-list-item-content>
              </v-list-item>

              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title
                    ><v-icon color="amber-darken-2" class="px-4"
                      >mdi-credit-card</v-icon
                    ><strong>Pagamento Integrado:</strong></v-list-item-title
                  >
                  <v-list-item-subtitle
                    >Realize pagamentos rápidos e seguros diretamente no
                    sistema.</v-list-item-subtitle
                  >
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Perguntas Frequentes -->
    <v-row class="mt-5" justify="center">
      <v-col cols="12" md="8">
        <v-card class="elevation-2">
          <v-card-title class="text-h6"
            >Perguntas Frequentes (FAQ)</v-card-title
          >
          <v-card-text>
            <v-expansion-panels>
              <v-expansion-panel
                title="Como faço um pedido?"
                text="Para fazer um pedido, basta acessar o menu de produtos, escolher os itens desejados e confirmar o pedido. O pagamento pode ser feito de forma integrada."
              >
                <template v-slot:header>
                  <v-icon color="amber-darken-2" class="px-4"
                    >mdi-help-circle-outline</v-icon
                  >
                </template>
              </v-expansion-panel>

              <v-expansion-panel
                title="Como monitorar o estoque?"
                text="A cantina pode acessar a seção de cardapio para visualizar e controlar a quantidade de produtos disponíveis"
              >
                <template v-slot:header>
                  <v-icon color="amber-darken-2" class="px-4"
                    >mdi-help-circle-outline</v-icon
                  >
                </template>
              </v-expansion-panel>

              <v-expansion-panel
                title="Como visualizar os relatórios de vendas?"
                text="Os relatórios de vendas estão na dashboard, onde é possível visualizar detalhes sobre o desempenho financeiro da cantina."
              >
                <template v-slot:header>
                  <v-icon color="amber-darken-2" class="px-4"
                    >mdi-help-circle-outline</v-icon
                  >
                </template>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Suporte -->
    <v-row class="mt-5" justify="center">
      <v-col cols="12" md="8">
        <v-card class="elevation-2">
          <v-card-title class="text-h6">Precisa de Ajuda?</v-card-title>
          <v-card-text>
            Se você tiver mais dúvidas, entre em contato com nosso suporte.
            <v-btn
              color="amber-darken-2"
              class="mt-4"
              block
              >Enviar E-mail para Suporte</v-btn
            >
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "HelpScreen",
};
</script>

<style scoped>
.mt-5 {
  margin-top: 2rem;
}
</style>
