<template>
  <div class="container">
    <div class="client-head">
      <h1 class="mb-3">Resgatar PIN's</h1>
    </div>
    <div class="redeem">
      <form>
        <div class="form-gift">
          <div class="form-group col-md-6 col-sm-6">
            <label for="name">Quantidade</label>
            <input
              type="text"
              class="form-control"
              id="code"
              placeholder="0"
              style="text-transform: uppercase"
              autocomplete="off"
              v-model="quantity"
            />
          </div>
          <div class="form-group col-sm-5 col-md-5">
            <label for="inputState">Preço</label>
            <select class="form-control" v-model="value">
              <option value="" selected>Selecionar...</option>
              <option
                v-for="value in values"
                :key="value.id"
                v-bind:value="value.id"
              >
                R${{ value.value }}
              </option>
            </select>
          </div>
        </div>

        <div class="mt-4 submit-redeem">
          <button
            @click="takeCards()"
            type="button"
            class="btn btn-success btn-submit btn-redeem"
          >
            Adicionar
          </button>

          <span
            v-if="error"
            class="text-muted error-redeem error-client font-message"
            >{{ error }}</span
          >
          <span
            v-if="success"
            class="text-muted error-redeem success-client font-message"
            >{{ success }}</span
          >
        </div>
      </form>
    </div>

<!-- 
    <div class="modal" v-if="modalRequest">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" v-if="error">
              Deseja solicitar {{ quantity }} de R${{ value }} ao Administrador?
            </h5>
            <button
              type="button"
              @click="closeRequest()"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="confirm-request">
            <button
              type="button"
              class="btn btn-danger"
              @click="closeRequest()"
            >
              Não
            </button>
            <button
              @click="requestBalance()"
              type="button"
              class="btn btn-primary btn-submit"
            >
              Solicitar
            </button>
          </div>
        </div>
      </div>
    </div> -->

    <!-- Modal Balance-->
    <div class="modal" tabindex="-1" v-if="modalSuccess">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Códigos Resgatados</h5>
            <button
              type="button"
              class="close"
              @click="modalSuccess = false"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div>
              <div class="container">
                <div class="form-control redeem-codes">
                  <p v-for="card in cards" :key="card.id">{{ card.code }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        <button
              type="button"
              class="btn btn-danger"
              @click="downloadPdf()"
            >
              Não
            </button></div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "./../api";
export default {
  data() {
    return {
      values: [
        { id: 1, value: 35 },
        { id: 2, value: 50 },
        { id: 3, value: 55.9 },
        { id: 4, value: 56 },
        { id: 5, value: 70 },
        { id: 6, value: 150 },
      ],
      value: "",
      cards: [],
      quantity: "",
      error: "",
      success: false,
      modalRequest: false,
      modalSuccess: false,
    };
  },
  methods: {
    async takeCards() {
      this.success = false;
      this.error = "";

      const data = {
        quantity: this.quantity,
        value: this.value,
      };
      console.log(data);
      if (!this.value || !this.quantity) {
        return (this.error = "Preencha os campos corretamente");
      }
      try {
        const response = await api.post("/reports", data);

        console.log(response.data.data);
        this.cards = response.data.data;
        this.quantity = "";
        this.value = "";
        this.modalSuccess = true;
      } catch (err) {
        this.error = err.response.data.message;
        this.value = this.values[this.value - 1].value;
        this.modalRequest = true;
      }
    },

    async requestCards() {

    },

    closeRequest() {
        this.value = '';
        this.error = '';
        this.quantity = '';
        this.modalRequest = false;
    }
  },
  
};
</script>