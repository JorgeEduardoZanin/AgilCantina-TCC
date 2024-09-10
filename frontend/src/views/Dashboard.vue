<template>
  <div class="mainDashboard">
    <v-container>
      <v-row>
        <v-col cols="12">
          <v-card class="pa-1">
            <v-card-title>
              <v-row>
                <v-col>
                  <h5 class="subtitle">Dashboard </h5>
                  <h1 class="title">Nome Empresa</h1>
                </v-col>
                <v-col cols="1">
                    <div class="foto">
                        <span>foto</span>
                    </div>
                </v-col>
              </v-row>
            </v-card-title>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
    <DashboardHome />
  </div>
</template>

<script>
import DashboardHome from "@/components/DashboardHome.vue";

export default {
  components: { DashboardHome },
};
</script>

<style>
.mainDashboard {
  background-color: #f2f2f2;
  font-family: Roboto;
}
.title {
  font-size: 36px;
}
.foto{
    background-color: #ffa600;
}
</style>
