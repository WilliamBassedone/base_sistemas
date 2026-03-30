@extends('panel::app')

@section('title', 'Nova Empresa')
@section('page_title', 'Nova Empresa')

@section('content')
    <div class="tw:w-full">
        @include('company::companies._form')
    </div>
@endsection
