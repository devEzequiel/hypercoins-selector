<div>
    <h3 class="mb-5">Olá, Marcos! {{$name}} acabou de resgatar {{count($cards)}} cards de R${{$cards[0]['value']}}</h3>
    @foreach($cards as $card)
        <p>{{$card['code']}}</p>
    @endforeach
</div>