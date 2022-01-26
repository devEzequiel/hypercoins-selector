<div>
    <h3 class="mb-3">Olá, {{$name}}! <br> Segue os cards de R${{$cards[0]['value']}} resgatados por você</h3>
    @foreach($cards as $card)
        <p>{{$card['code']}}</p>
    @endforeach
</div>