@extends('panel::app')

@section('title', 'Editar Empresa')
@section('page_title', 'Editar Empresa')

@section('content')
    <div class="tw:w-full">
        @include('company::companies._form')
    </div>
@endsection
