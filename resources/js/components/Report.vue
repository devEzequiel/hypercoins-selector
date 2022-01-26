<template>
  <div class="container">
    <div class="client-head">
      <h1 class="mb-3">Relatórios</h1>
    </div>
    <div class="date-pick">
      <div class="mb-3 pickers">
        <label>Início</label>
        <date-picker v-model="startDate" valueType="format"></date-picker>
      </div>
      <div class="pickers">
        <label>Fim</label>
        <date-picker v-model="startDate" valueType="format"></date-picker>
      </div>
    </div>

    <table class="table table-report mt-5">
      <thead>
        <tr>
          <th scope="col">Cliente</th>
          <th scope="col">Valor</th>
          <th scope="col">Data</th>
        </tr>
      </thead>
      <tbody v-if="reports">
        <tr v-for="report in reports" :key="report.id">
          <td>{{ formatName(report.client.name) }}</td>
          <td>R${{ formatAmount(report.amount) }}</td>
          <td>{{ report.created_at | formatDate }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import DatePicker from "vue2-datepicker";
import moment from "moment";
import "vue2-datepicker/index.css";
import api from "./../api";

Vue.filter("formatDate", function (value) {
  if (value) {
    return moment(String(value)).format("DD/MM/YYYY hh:mm");
  }
});
export default {
  components: { DatePicker },
  data() {
    return {
      reports: [],
      startDate: null,
      endDate: null,
    };
  },
  methods: {
    formatAmount(amount) {
      var formated = amount.toLocaleString("pt-br", {
        style: "currency",
        currency: "BRL",    
      });
      return formated;
    },
    formatName(name) {
      let firstName = name.split(" ");
      return firstName[0];
    },
    async getReport() {
      const response = await api.get("get/reports");

      this.reports = response.data.data;
    },
  },
  mounted() {
    this.getReport();
  },
};
</script>