@extends('panel::app')

@section('title', 'Componentes')
@section('page_title', 'Componentes')

@pushOnce('styles')
    @vite('Modules/Development/resources/assets/css/table-styles.css')
@endPushOnce

@section('content')
    <div class="dev-page">
        <section class="dev-panel">
            <div class="dev-panel__hero">
                <p class="dev-eyebrow">Development Module</p>
                <h2 class="dev-title">{{ $table['title'] }}</h2>
                <p class="dev-subtitle">{{ $table['description'] }}</p>
            </div>

            <div class="dev-toolbar">
                <div class="dev-toolbar__meta">
                    <span class="dev-toolbar__label">Montagem dinamica</span>
                    <span class="dev-toolbar__hint">
                        O backend envia colunas e linhas; a view apenas interpreta e renderiza a tabela.
                    </span>
                </div>

                <div class="dev-actions">
                    <a href="{{ route('components.builder') }}" class="dev-btn" data-turbo-frame="main"
                        data-turbo-action="advance">
                        <i class="fa-solid fa-sliders"></i>
                        Configurar tabela
                    </a>

                    <a href="{{ route('components.create') }}" class="dev-btn dev-btn--primary" data-turbo-frame="main"
                        data-turbo-action="advance">
                        <i class="fa-solid fa-plus"></i>
                        Cadastrar
                    </a>
                </div>
            </div>

            <div class="dev-grid">
                <x-ui::data-table :columns="$table['columns']" :rows="$table['rows']" :pagination="$pagination" edit-route="components.builder" />


            </div>
        </section>
    </div>
@endsection
