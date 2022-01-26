<template>
  <div>
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
            <h5 class="modal-title" id="exampleModalLabel">Adicionar Gift Card</h5>
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
                <label for="name">PIN</label>
                <input
                  type="text"
                  class="form-control"
                  id="code"
                  v-model="data.code"
                  placeholder="XXXX XXXX XXXX XXXX"
                />
              </div>

              <select class="form-control" v-for="value in values" :key="value.id">
                <option>{{value.value}}</option>
              </select>
              <div class="mt-4 submitCLient">
                <span v-if="errors" class="text-muted error-client">Erro!</span>
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
                  class="btn btn-primary btn-submit"
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
      array: [],
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
  },
  mounted() {
    this.getCards();
    this.getSum();
  },
};
</script>