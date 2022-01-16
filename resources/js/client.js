require("./bootstrap");
import api from "./api";

window.Vue = require("vue").default;

let clients = new Vue({
  el: "#clients",
  data() {
    return {
      message: "Hello Vue!",
      clients: [],
      show: false,
    };
  },
  async mounted() {
    let response = await api.get("get/clients");

    this.clients = await response.data.data;
  },
  methods: {
    toggleClient() {
      this.show ? false : true;
      return;
    },
  },
});
