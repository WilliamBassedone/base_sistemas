@extends('panel::app')

@section('title', 'Cadastrar Componente')
@section('page_title', 'Cadastrar Componente')

@pushOnce('styles')
    @vite('Modules/Development/resources/assets/css/table-styles.css')
@endPushOnce

@section('content')
    <div class="dev-page">
        <section class="dev-panel">
            <div class="dev-panel__hero">
                <p class="dev-eyebrow">Cadastro</p>
                <h2 class="dev-title">Nova tela de cadastro</h2>
                <p class="dev-subtitle">
                    Este ponto ja esta pronto para receber o formulario de cadastro do componente.
                </p>
            </div>

            <div class="dev-grid">
                <div class="dev-card-grid">
                    <article class="dev-card">
                        <h3>Objetivo da tela</h3>
                        <p>Receber os campos do componente, persistir os dados e retornar para a listagem.</p>
                    </article>

                    <article class="dev-card">
                        <h3>Proximos blocos</h3>
                        <ul>
                            <li>Identificacao do componente</li>
                            <li>Configuracoes de exibicao</li>
                            <li>Regras de comportamento</li>
                        </ul>
                    </article>
                </div>
            </div>
        </section>
    </div>
@endsection
