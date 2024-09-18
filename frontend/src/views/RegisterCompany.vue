<template>
    <div class="mainRegister">
      <RegisterCompanyForm/>
    </div>
  </template>
  
  <script>
  import RegisterCompanyForm from "@/components/RegisterCompanyForm.vue";

  export default {
    name: "Register",
    components: { RegisterCompanyForm },
  };
  </script>
  
  <style scoped>
  .mainRegister{
    background-color: #F2F2F2;
  }
  </style>
  