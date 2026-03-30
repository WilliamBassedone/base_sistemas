@extends('panel::app')

@section('title', 'Editar Grupo')
@section('page_title', 'Editar Grupo')

@section('content')
    <div class="tw:w-full">
        @include('group::groups._form')
    </div>
@endsection
