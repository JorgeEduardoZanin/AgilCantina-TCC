<template>
  <div class="mainRegister">
    <RegisterForm />
  </div>
</template>

<script>
import RegisterForm from "@/components/RegisterForm.vue";

export default {
  name: "Register",
  components: { RegisterForm },
};
</script>

<style scoped>
.mainRegister{
    background-color: #fffdf8;
  }
</style>
