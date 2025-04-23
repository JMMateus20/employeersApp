<h2>🎉 Hoy celebramos un cumpleaños 🎉</h2>

@foreach ($cumpleanieros as $c)
    <p>🎂 <strong>{{ $c->nombre }}</strong> está cumpliendo años hoy ({{ \Carbon\Carbon::parse($c->fecha_nac)->age }} años) 🥳</p>
@endforeach

<p>¡No olvides felicitarlo(a)!</p>