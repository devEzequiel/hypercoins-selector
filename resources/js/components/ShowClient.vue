<template>
  <div class="container">
    <div class="client-head">
      <h1 class="mb-3">{{ client.name }}</h1>

      <div class="icon-client">
        <button class="edit-client" type="button" @click="modalClient = true">
          <i class="fas fa-pen" aria-hidden="true"></i>
        </button>
        <button class="delete-client" type="button" @click="modalDelete = true">
          <i class="fa fa-trash" aria-hidden="true"></i>
        </button>
      </div>
    </div>

    <div>
      <ul>
        <li><span>Email: </span> {{ client.email }}</li>
        <li><span>Senha: </span> {{ client.password }}</li>
      </ul>
      <div>
        <h4 class="mt-4 mb-4">Saldo Disponivel</h4>
        <div v-if="balance.length == 0">Nenhum saldo disponível</div>
        <ul v-for="card in balance" :key="card.value">
          <li v-if="card.value" class="value-balance">
            <span>{{ card.total }}</span> cards de
            <span>R${{ card.value }}</span>
          </li>
        </ul>

        <button
          type="button"
          class="btn btn-success mt-4"
          @click="modalBalance = true"
        >
          Editar Saldo
        </button>
      </div>
    </div>

    <!-- Confirm delete-->

    <div
      class="modal bd-example-modal-sm"
      tabindex="-1"
      role="dialog"
      v-if="modalDelete"
    >
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <h5 class="modal-title" id="exampleModalLabel">
            Deseja excluir esse usuário?
          </h5>
          <div class="">
            <button
              type="button"
              class="btn btn-danger"
              @click="modalDelete = false"
            >
              Cancelar
            </button>
            <button
              type="button"
              class="btn btn-success"
              @click="deleteClient()"
            >
              Excluir
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Balance-->
    <div class="modal" tabindex="-1" v-if="modalBalance">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar Saldo</h5>
            <button
              type="button"
              class="close"
              @click="closeModalBalance()"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div v-for="value in values" :key="value.id">
                <div class="form-group">
                  <label>R$ {{ value.value }}</label>
                  <div class="input-saldo">
                    <input
                      type="text"
                      class="form-control"
                      v-model="array[value.id]"
                      placeholder="Insira a quantidade de cards"
                    />
                    <span>{{ balance[value.id].total }}</span>
                    <button
                      @click="addBalance(value.id)"
                      type="button"
                      class="btn btn-primary btn-submit btn-send-balance"
                    >
                      Atualizar
                    </button>
                  </div>
                  <!-- <span
                    v-if="errorsBalance[value.id]"
                    class="text-muted error-client error-balance"
                    >{{ errorsBalance[value.id] }}</span
                  > -->
                </div>
              </div>
              <div class="mt-4 submitCLient">
                <span v-if="errors" class="text-muted error-client">Erro!</span>
                <button
                  type="button"
                  class="btn btn-secondary mr-1 closeModal"
                  data-dismiss="modal"
                  @click="closeModalBalance()"
                >
                  Close
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
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
            <h5 class="modal-title" id="exampleModalLabel">Editar Cliente</h5>
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
      id: "",
      client: [],
      array: [],
      data: [],
      balance: [],
      values: [
        { id: 1, value: 35 },
        { id: 2, value: 50 },
        { id: 3, value: 55.9 },
        { id: 4, value: 56 },
        { id: 5, value: 70 },
        { id: 6, value: 150 },
      ],
      errors: false,
      errorsBalance: [],
      modalClient: false,
      modalBalance: false,
      modalDelete: false,
    };
  },
  methods: {
    async addBalance(value) {

      const data = {
        client_id: this.id,
        value: value,
        quantity: this.array[value],
      };
      try {
        await api.put("/clients", data);
        this.getBalance();
        this.array[value] = '';
      } catch (err) {
        this.errorsBalance[value] = err.response.data.message;
      }

      if (this.errorsBalance[value]) {
        this.errorsBalance[value] = "Erro!";
      }
    },
    closeModalBalance() {
      this.modalBalance = false;
      this.array = [];
    },
    updateClient() {
      const data = {
        name: this.data.name,
        email: this.data.email,
        password: this.data.password,
      };
      // console.log(this.id)
      api
        .put(`/clients/${this.id}`, data)
        .then((r) => {
          this.getClient();
        })
        .catch((err) => {
          this.errors = true;
        });
    },
    async getBalance() {
      try {
        const response = await api.get(`/balance/${this.id}`);
        this.balance = response.data;
      } catch (err) {
        this.errors = true;
      }
    },
    async getClient() {
      const response = await api.get("get/client/" + this.id);

      this.client = response.data;
    },
    deleteClient() {
      api.delete("client/" + this.id).then((r) => {
        window.location.href = `/clients`;
      });
    },
  },
  mounted() {
    const url = window.location.href;
    this.id = url.charAt(url.length - 1);
    this.getClient();
    this.getBalance();
  },
};
</script>
