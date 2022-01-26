<template>
  <div class="container">
    <div class="client-head">
      <h1 class="mb-3">Gift Cards</h1>

      <button
        type="button"
        class="btn btn-outline-dark btn-font create-client"
        v-on:click="modalCreate = true"
        data-toggle="modal"
      >
        Adicionar
      </button>
    </div>

    <div class="table-cards mt-5">
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Código</th>
            <th scope="col">Valor</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="card in cards" :key="card.id">
            <th scope="row">{{card.id}}</th>
            <td>{{card.code}}</td>
            <td>{{card.value}}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Card -->
    <div
      class="modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
      v-if="modalCreate"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Adicionar Gift Card
            </h5>
            <button
              type="button"
              class="close"
              @click="modalCreate = false"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-gift">
                <div class="form-group col-sm-9">
                  <label for="name">PIN</label>
                  <input
                    type="text"
                    class="form-control"
                    id="code"
                    placeholder="XXXX-XXXX-XXXX-XXXX"
                    style="text-transform: uppercase"
                    autocomplete="off"
                    v-model="code"
                  />
                </div>
                <div class="form-group col-sm-2">
                  <label for="inputState">Preço</label>
                  <select class="form-control" v-model="value">
                    <option value="" selected>Selecionar...</option>
                    <option
                      v-for="value in values"
                      :key="value.id"
                      v-bind:value="value.id"
                    >
                      {{ value.value }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="mt-4 submitCLient">
                <span v-if="error" class="text-muted error-client font-message"
                  >Ocorreu um Erro!</span
                >
                <span
                  v-if="success"
                  class="text-muted success-client font-message"
                  >Card adicionado</span
                >
                <button
                  type="button"
                  class="btn btn-secondary mr-1 closeModal"
                  data-dismiss="modal"
                  @click="modalCreate = false"
                >
                  Close
                </button>
                <button
                  @click="createCard()"
                  type="button"
                  class="btn btn-success btn-submit"
                >
                  Adicionar
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
      cards: [],
      sum: [],
      values: [
        { id: 1, value: 35 },
        { id: 2, value: 50 },
        { id: 3, value: 55.9 },
        { id: 4, value: 56 },
        { id: 5, value: 70 },
        { id: 6, value: 150 },
      ],
      modalCreate: false,
      code: "",
      value: "",
      error: false,
      success: false,
    };
  },
  methods: {
    async getCards() {
      const response = await api.get("/cards/all");

      this.cards = response.data;
    },
    async getSum() {
      const response = await api.get("/cards/sum");

      this.sum = response.data;
    },
    async createCard() {
      this.error = false;
      this.success = false;

      const data = {
        code: this.code.toUpperCase(),
        value: this.value,
      };

      try {
        await api.post("/cards", data);
        this.getCards();
        this.code = "";
        this.value = "";
        this.success = true;
      } catch (err) {
        this.error = true;
      }
    },
  },
  mounted() {
    this.getCards();
    this.getSum();
  },
};
</script>