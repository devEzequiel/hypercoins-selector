require("./bootstrap");
import api from './api';

window.Vue = require("vue").default;

var app = new Vue({
  el: "#clients",
  data: {
    message: "Hello Vue!",
    clients: []
  },
  async mounted() {
    let response = await api.get("get/clients");

    this.clients = await response.data.data;
    console.log(this.clients);  
  },

});
