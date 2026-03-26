@extends('layouts.app')

@section('content')
    <h1>Página Home</h1>
    <p>Esta é a página inicial.</p>
    
    <p>Renderizado pelo servidor às: <strong>{{ now()->format('H:i:s') }}</strong></p>
    
    <div style="margin-top: 20px; padding: 10px; background-color: #f0f0f0;">
        <h3>Teste do Turbo Drive</h3>
        <p>Clique no link "Sobre" no menu acima. Observe que o ícone do navegador não "gira" (refresh completo), mas o horário muda.</p>
    </div>
@endsection