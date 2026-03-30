@extends('panel::app')

@section('title', 'Editar Usuário')
@section('page_title', 'Editar Usuário')

@section('content')
    <div class="tw:w-full">
        @include('user::users._form')
    </div>
@endsection
