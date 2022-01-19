<template>
  <div class="container">
    <div class="client-head">
      <h1 class="mb-3">{{ client.name }}</h1>
      <button
          type="button"
          class="btn btn-success create-client"
          @click="modalClient = true"
      >
        Editar Cliente
      </button>


    </div>

    <div>
      <ul>
        <li><span>Email: </span> {{ client.email }}</li>
        <li><span>Email: </span> {{ client.email }}</li>
        <li><span>Cards Disponíveis: </span> R$ 35,00:</li>
      </ul>
      <h4>Cards Disponiveis</h4>
      <ul>
        <li>
        <li><span> R$ 35,00: </span></li>
        <li>
        <li><span> R$ 35,00: </span></li>
        <li>
        <li><span> R$ 35,00: </span></li>
        <li>
        <li><span> R$ 35,00: </span></li>
        <li>
        <li><span> R$ 35,00: </span></li>
        <li>
        <li><span> R$ 35,00: </span></li>
        <li>
        <li><span> R$ 35,00: </span></li>
      </ul>
    </div>
    <!-- Modal -->
    <div
        class="modal"
        id="exampleModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
        v-if="modalClient"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button
                type="button"
                class="close"
                @click="modalClient = false"
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
                    v-model="data.name"
                    placeholder="Insira o nome do cliente"
                />
                <pre>{{ client.name }}</pre>

              </div>
              <div>
                <label for="email">Email</label>
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    v-model="data.email"
                    placeholder="Insira o email do cliente"
                />
                <pre>{{ client.email }}</pre>
              </div>
              <div class="form-group">
                <label for="password">Senha do Cliente</label>
                <input
                    type="password"
                    class="form-control"
                    id="password"
                    v-model="data.password"
                    placeholder="Insira a senha de acesso"
                />
                <pre>{{ client.password }}</pre>
              </div>
              <div class="mt-4 submitCLient">
                <span v-if="errors" class="text-muted error-client">Erro!</span>
                <button
                    type="button"
                    class="btn btn-secondary mr-1 closeModal"
                    data-dismiss="modal"
                    @click="modalClient = false"
                >
                  Close
                </button>
                <button
                    @click="updateClient()"
                    type="button"
                    class="btn btn-primary btn-submit"
                >
                  Atualizar
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

export default {
  data() {
    return {
      id: '',
      client: [],
      data: [],
      errors: false,
      modalClient: false
    }
  },
  methods: {
    updateClient() {
      const data = {
        name: this.data.name,
        email: this.data.email,
        password: this.data.password,
      };
// console.log(this.id)
      api.put(`/clients/${this.id}`, data).then((r) => {
        api
            .get("get/client/" + this.id)
            .then((r) => {
              this.client = r.data;
              this.errors = false;
            })

      }).catch((err) => {
        this.errors = true;
      });
    },
  },
  mounted() {
    const url = window.location.href;
    this.id = url.charAt(url.length - 1);
    api.get("get/client/" + this.id).then((r) => {
      this.client = r.data;
    });
  },
};
</script>
