<template>
  <div>
    <div class="client-head">
      <h1 class="mb-3">Clientes</h1>
      <button
        type="button"
        class="btn btn-success create-client"
        v-on:click="showModal = true"
        data-target="#exampleModalLong"
        data-toggle="modal"
      >
        Novo Cliente
      </button>
    </div>

    <ul v-for="client in clients" :key="client.client.id">
      <div class="client-name">
        {{ client.client.name }}
      </div>
    </ul>

    <!-- Modal -->
    <div
      class="modal"
      id="exampleModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
      v-if="showModal"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button
              type="button"
              class="close"
              @click="showModal = false"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label for="name">Nome</label>
                <input
                  type="text"
                  class="form-control"
                  id="name"
                  v-model="name"
                  placeholder="Insira o nome do cliente"
                />
                
              </div>
              <div>
                <label for="email">Email</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  v-model="email"
                  placeholder="Insira o email do cliente"
                />
                
              </div>
              <div class="form-group">
                <label for="password">Senha do Cliente</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  v-model="password"
                  placeholder="Insira a senha de acesso"
                />
                
              </div>
              <div class="mt-4 submitCLient">
              <span v-if="errors" class="text-muted error-client">Preencha os campos corretamente</span>
                <button
                  type="button"
                  class="btn btn-secondary mr-1 closeModal"
                  data-dismiss="modal"
                  @click="showModal = false"
                >
                  Close
                </button>
                <button
                  @click="checkForm"
                  type="button"
                  class="btn btn-primary btn-submit"
                >
                  Cadastrar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "./../api";
import moment from "moment";

Vue.filter("formatDate", function (value) {
  if (value) {
    return moment(String(value)).format("DD/MM/YYYY hh:mm");
  }
});

export default {
  data() {
    return {
      clients: [],
      errors: false,
      show: true,
      showModal: false,
      name: "",
      email: "",
      password: "",
    };
  },
  methods: {
    checkForm() {
      if (!this.name || !this.email || !this.password) {
        return this.errors = true;
      }
      this.createClient();
    },
    createClient() {
      // e.preventDefault();
      // console.log(this.name)
      const data = {
        name: this.name,
        email: this.email,
        password: this.password,
      };

      console.log(data);

      const headers = {
        "Content-Type": "application/json",
        Accept: "application/json",
      };
      api.post("/clients", data, headers).then((r) => {
        api
          .get("get/clients")
          .then((r) => {
            this.clients = r.data.data;
            this.showModal = false;
          })
          .catch((err) => {
            console.log(err);
          });
      });
    },
    async getClient(id) {
      await api.get("get/clients" + id);
    }
  },
  beforeCreate() {
    api.get("get/clients").then((r) => {
      this.clients = r.data.data;
    });
  },
};
</script>
