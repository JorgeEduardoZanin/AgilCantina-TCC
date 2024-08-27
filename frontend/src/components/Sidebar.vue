<template>
  <div class="mainSidebar d-flex flex-column justify-content-between">
    <nav class="sidebar d-flex flex-column p-3">
      <router-link to="/" class="d-flex justify-content-center"><img src="../assets/logos/agil-cantina-letras-pretas.png" class="img-fluid" style="width: 200px"></router-link>
      <ul class="nav nav-pills flex-column mb-auto mt-5">
        <li class="nav-item">
          <router-link class="p-3 rounded nav-pills nav-link mb-3"><i class="bi bi-shop-window px-2 me-2"></i>Cantinas</router-link>
        </li>
        <li class="nav-item">
          <router-link class="p-3 rounded nav-pills nav-link mb-3"><i class="bi bi-cart3 px-2 me-2"></i>Carrinho</router-link>
        </li>
        <li class="nav-item">
          <router-link class="p-3 rounded nav-pills nav-link mb-3"><i class="bi bi-heart px-2 me-2"></i>Favoritos</router-link>
        </li>
        <li class="nav-item">
          <router-link class="p-3 rounded nav-pills nav-link mb-3"><i class="bi bi-question-circle px-2 me-2"></i>Ajuda</router-link>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
  name: "Sidebar",
}

</script>

<style scoped>
.mainSidebar {
  background-color: #F2F2F2;
  width: 300px;
  height: 100vh;
}
.sidebar .nav-link {
  color: #333;
  font-size: 24px;
  font-weight: 500;
}
.sidebar .nav-link:hover {
  background-color: #ffa600;
  color: #fff;
}

.last{
  color: #333;
  font-size: 24px;
  font-weight: 500;
}
.last:hover {
  background-color: #FFA700;
  color: #fff;
}


</style>