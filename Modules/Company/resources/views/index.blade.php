@extends('panel::app')

@section('title', 'Empresas')
@section('page_title', 'Empresas')

@section('content')
    <div class="tw:space-y-6">
        <x-ui::data-table-tw title="Listagem de Empresas" description="Dados fictícios para iniciar o layout." :columns="$table['columns']"
            :rows="$table['rows']" :pagination="$pagination" create-route="companies.create" create-label="Nova Empresa"
            edit-route="companies.edit" />
    </div>
@endsection
