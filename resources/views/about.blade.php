@extends('layouts.app')

@section('content')
    <h1>Página Sobre</h1>
    <p>Você navegou para outra página!</p>
    
    <p>Renderizado pelo servidor às: <strong>{{ now()->format('H:i:s') }}</strong></p>
    
    <a href="/">Voltar para Home</a>
@endsection