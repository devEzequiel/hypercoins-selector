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
        <h4 class="mt-4">Saldo Disponivel</h4>
        <div v-if="balance.length == 0">Nenhum saldo disponível</div>
        <ul v-for="card in balance" :key="card.value">
          <li v-if="card.value">
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
    <div class="modal" tabindex="-1" v-if="modalDelete">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
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
              @click="modalBalance = false"
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
                      id="name"
                      v-model="data[value.key]"
                      placeholder="Insira a quantidade de cards"
                    />
                    <span>{{ balance[value.id].total }}</span>
                  </div>
                </div>
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
                  @click="addBalance()"
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
      modalClient: false,
      modalBalance: false,
      modalDelete: false,
    };
  },
  methods: {
    addBalance() {
      console.log(this.data.array);
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
    getBalance() {
      api
        .get(`/balance/${this.id}`)
        .then((r) => {
          this.balance = r.data;
        })
        .catch((err) => {
          this.errors = true;
        });
    },
    getClient() {
      api.get("get/client/" + this.id).then((r) => {
        this.client = r.data;
      });
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
