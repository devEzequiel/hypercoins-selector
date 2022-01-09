@extends('index')

@section('content')

<div id="clients">
    <h1>Clientes</h1>
    <div class="mt-6">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Password</th>
            </tr>
        </thead>
        <tbody  v-for="client in clients">
            <td v-html="client"></td>
            {{-- <td v-html="client.client.email"></td>
            <td v-html="client.client.password"></td> --}}
        </tbody>
    </table>
</div>

<script src="{{asset('js/clients.js')}}" defer></script>
@endsection