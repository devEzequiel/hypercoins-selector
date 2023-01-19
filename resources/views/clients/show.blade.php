@extends('master')

@section('content')
<div id="clients" class="mt-3">
    <show-client></show-client>
    {{-- <h1>{{$client}}</h1> --}}
</div>

{{-- <script src="{{asset('js/client.js')}}" defer></script> --}}

@endsection