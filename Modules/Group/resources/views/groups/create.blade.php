@extends('panel::app')

@section('title', 'Novo Grupo')
@section('page_title', 'Novo Grupo')

@section('content')
    <div class="tw:w-full">
        @include('group::groups._form')
    </div>
@endsection
