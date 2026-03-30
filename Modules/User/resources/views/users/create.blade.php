@extends('panel::app')

@section('title', 'Novo Usuário')
@section('page_title', 'Novo Usuário')

@section('content')
    <div class="tw:w-full">
        @include('user::users._form')
    </div>
@endsection
