<template>
    <div id="clients">
    <h1>Clientes</h1>
    <div class="mt-6">
    {{-- <table class="table">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Password</th>
            </tr>
        </thead>
        <tbody  v-for="client in clients" :key="client.client.id">
            <td v-html="message"></td> 
            <td v-html="client.client.email"></td>
            <td v-html="client.client.password"></td>
        </tbody>
    </table> --}}
    
    <div>
        <h4 class="client-name" v-on:click="show = true">Nome do Cliente</h4>
        <ul v-if="clientData">
            <li><span>Email:</span> emaildoclient@email.com</li>
            <li><span>Senha:</span> passwordExample</li>
            <li><span>Email:</span> emaildoclient@email.com</li>
            <li><span>Saldo</span> XXXXX</li>
        </ul>
        <h4>Nome do Cliente</h4>
        <ul v-if="clientData">
            <li> <span>Email:</span> emaildoclient@email.com</li>
            <li><span>Senha:</span> passwordExample</li>
            <li><span>Email:</span> emaildoclient@email.com</li>
            <li><span>Saldo</span> </li>
        </ul>
    </div>
</div>
</template>

<script>
    import api from "./../../api";
    
    window.Vue = require("vue").default;
    export default {
        el: "#clients",
        data() {
            return {
            message: "Hello Vue!",
            clients: [],
            show: false,
            };
        },
        async beforeCreate() {
            let response = await api.get("get/clients");

            this.clients = await response.data.data;
        },
        methods: {
            toggleClient() {
            this.show ? false : true;
            return;
            },
        },
        };
</script>
