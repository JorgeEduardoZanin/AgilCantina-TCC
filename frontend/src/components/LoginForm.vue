<template>
  <div>
    <h1>Login</h1>
    <v-sheet class="mx-auto">
      <v-form @submit.prevent>
        <v-text-field
          v-model="email"
          :rules="Emailrules"
          label="E-mail"
        ></v-text-field>

        <v-text-field
          v-model="password"
          :type="showPassword ? 'text' : 'password'"
          :rules="PasswordRules"
          label="Password"
          :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
          @click:append="showPassword = !showPassword"
        ></v-text-field>

        <v-btn class="mt-2" type="submit" block>Entrar</v-btn>
      </v-form>
    </v-sheet>
    <pre>{{ this.data }}</pre>
  </div>
</template>

<script>
export default {
  name: "LoginForm",

  data: () => ({
    email: "",
    password: "",
    showPassword: false,
    Emailrules: [
      (value) => {
        if (value) return true;
        return "O e-mail é obrigatório";
      },
      (value) => {
        if (/.+@.+\..+/.test(value)) return true;
        return "O e-mail é inválido";
      },
    ],
    PasswordRules: [
      (value) => {
        if (value) return true;
        return "A senha é obrigatória";
      },
      (value) => {
        if (value.length >= 8) return true;
        return "A senha deve ter pelo menos 8 caracteres";
      },
    ],
  }),
};
</script>

<style></style>
