@extends('panel::app')

@section('title', 'Configurar Tabela')
@section('page_title', 'Configurar Tabela')

@pushOnce('styles')
    @vite('Modules/Development/resources/assets/css/table-styles.css')
@endPushOnce

@section('content')
    <div class="dev-page">
        <section class="dev-panel">
            <div class="dev-panel__hero">
                <p class="dev-eyebrow">Builder</p>
                <h2 class="dev-title">Estrutura enviada pelo backend</h2>
                <p class="dev-subtitle">
                    Esta tela representa o ponto onde voce pode evoluir a configuracao das colunas, tipos e regras da tabela.
                </p>
            </div>

            <div class="dev-grid">
                <div class="dev-card-grid">
                    @foreach ($schema as $field)
                        <article class="dev-card">
                            <h3>{{ $field['label'] }}</h3>
                            <p>Chave: <strong>{{ $field['key'] }}</strong></p>
                            <p>Tipo: <strong>{{ $field['type'] }}</strong></p>
                            <p>Sticky: <strong>{{ $field['sticky'] ? 'Sim' : 'Nao' }}</strong></p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection
