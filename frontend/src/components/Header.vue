<template>
  <div class="mainHeader d-flex justify-content-between align-items-center p-5">
    <InputLocation/>
    <div class="d-flex align-items-center user rounded-pill">
      <i class="bi bi-person-circle px-2"></i>
      <span class="px-2">John Doe</span>
    </div>

  </div>
</template>

<script>
import InputLocation from "@/components/InputLocation.vue";
export default {
  name: "Header",
  components: {
    InputLocation
  }
}
</script>

<style scoped>
.mainHeader{
  background-color:rebeccapurple;
  width: 100vh;
  height: 50px;
}
.user{
  background-color: #F2F2F2;
  font-size: 20px;
}
.user i{
  font-size: 25px;
}
</style>