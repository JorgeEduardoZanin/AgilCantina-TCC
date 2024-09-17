<template>
  <v-app >
    <v-navigation-drawer collapse  width="300" class="mainSidebar">
      <img
        class="img-fluid p-3"
        src="../assets/logos/agil-cantina-letras-pretas.png"
      />
      <v-list>
        <v-list v-model="active" lines="three">
          <v-list-item
            v-for="item in menuItems"
            :key="item.title"
            :to="item.route"
            :value="item.route"
            class="nav-item">
            <v-list-item-content class="items">
              <v-list-item-title class="title">
                <v-icon>{{ item.icon }}</v-icon>
                {{ item.title }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-list>
    </v-navigation-drawer>
  </v-app>
</template>

<script>
export default {
  name: "Sidebar",
  data() {
    return {
      active: null,
      menuItems: [
        { title: "Cantinas", route: "/cantinas", icon: "mdi-store" },
        { title: "Carrinho", route: "/cart", icon: "mdi-cart" },
        { title: "Favoritos", route: "/favorites", icon: "mdi-heart" },
        { title: "Ajuda", route: "/help", icon: "mdi-help-circle" },
      ],
    };
  },
};
</script>

<style scoped>
.mainSidebar {
  background-color: #f2f2f2;
  width: 300px;
}

.items{
  font-size: 50px;
  font-weight: 500;
  color: #333;
}

.nav-item:hover{
  background: linear-gradient(to top, #ffa600, #ffd900);
  color: #f2f2f2;
}
.title{
  color: #333;
  font-size: 20px;
}
</style>
