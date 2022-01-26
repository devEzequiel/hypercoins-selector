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
        <date-picker v-model="endDate" valueType="format"></date-picker>
      </div>
      <div class="filter-button mt-3">
        <button
          type="button"
          class="btn btn-outline-dark"
          @click="filterReport()"
        >
          Filtrar
        </button>
      </div>
    </div>

    <download-csv :data="downloadCsv()" name="relatorio-hypercoins.csv"
      ><button type="button" class="btn btn-success mt-5">
        <i class="fas fa-file-csv"></i> Baixar CSV
      </button>
    </download-csv>
    <table class="table table-report mt-2">
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
import JsonCSV from "vue-json-csv";
import moment from "moment";
import "vue2-datepicker/index.css";
import api from "./../api";

Vue.filter("formatDate", function (value) {
  if (value) {
    return moment(String(value)).format("DD/MM/YYYY hh:mm");
  }
});
Vue.component("downloadCsv", JsonCSV);
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
    async filterReport() {
      if (!this.startDate || !this.endDate) {
        return;
      }

      const response = await api.get(
        `get/reports/${this.startDate}/${this.endDate}`
      );

      this.reports = response.data.data;
    },

    downloadCsv() {
      const array = this.reports.map(function (rep) {
        return {
          id: rep.id,
          nome: rep.client.name,
          valor: rep.amount.toLocaleString("pt-br", {
            style: "currency",
            currency: "BRL",
          }),
          data: moment(String(rep.created_at)).format("DD/MM/YYYY hh:mm"),
        };
      });

      return array;
    },
  },
  mounted() {
    this.getReport();
  },
};
</script>