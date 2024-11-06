<template>
  <v-form>
    <v-radio-group
      v-model="selectedDay"
      row
      inline
      label="Horário de funcionamento"
    >
      <v-radio label="Segunda-feira" value="Segunda"></v-radio>
      <v-radio label="Terça-feira" value="Terça"></v-radio>
      <v-radio label="Quarta-feira" value="Quarta"></v-radio>
      <v-radio label="Quinta-feira" value="Quinta"></v-radio>
      <v-radio label="Sexta-feira" value="Sexta"></v-radio>
      <v-radio label="Sábado" value="Sábado"></v-radio>
      <v-radio label="Domingo" value="Domingo"></v-radio>
    </v-radio-group>

    <div v-if="selectedDay">
      <v-row>
        <v-col cols="6">
          <v-text-field
            v-model="openingTimes[selectedDay]"
            label="Horário de abertura"
            type="time"
            required
          ></v-text-field>
        </v-col>

        <v-col cols="6">
          <v-text-field
            v-model="closingTimes[selectedDay]"
            label="Horário de encerramento"
            type="time"
            required
          ></v-text-field>
        </v-col>
      </v-row>
    </div>

    <div v-if="formattedOpeningHours.length">
      <p>Horários de Funcionamento:</p>
      <div>
        <p v-for="(item, index) in formattedOpeningHours" :key="index">
          {{ item.day }}: {{ item.time }}
        </p>
      </div>
    </div>
  </v-form>
</template>

<script>
export default {
  name: "OpeningHoursComponent",
  data() {
    return {
      selectedDay: "",
      openingTimes: {},
      closingTimes: {},
    };
  },
  computed: {
    formattedOpeningHours() {
      const formattedTimes = [];
      const days = Object.keys(this.openingTimes);

      days.forEach((day) => {
        if (this.openingTimes[day] && this.closingTimes[day]) {
          const formattedTime = `${this.openingTimes[day]} - ${this.closingTimes[day]}`;
          formattedTimes.push({ day, time: formattedTime });
        }
      });
      this.$emit("update-opening-hours", formattedTimes);
      return formattedTimes;
    },
  },
};
</script>

<style scoped></style>
