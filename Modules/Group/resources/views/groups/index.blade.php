@extends('panel::app')

@section('title', 'Grupos')
@section('page_title', 'Grupos')

@section('content')
    <div class="tw:space-y-6">
        {{-- @if (session('status'))
            <div
                class="tw:bg-emerald-50 tw:border tw:border-emerald-200 tw:text-emerald-800 tw:rounded-lg tw:px-4 tw:py-3 tw:text-sm">
                {{ session('status') }}
            </div>
        @endif --}}


        <x-ui::data-table-tw title="Listagem de Grupos" description="Dados fictícios para iniciar o layout." :columns="$table['columns']"
            :rows="$table['rows']" :pagination="$pagination" create-route="groups.create" create-label="Novo Grupo"
            edit-route="components.builder" />

    </div>
@endsection
