@extends('panel::app')

@section('title', 'Visualizar Usuário')
@section('page_title', 'Visualizar Usuário')

@section('content')
    <div class="tw:w-full">
        @include('user::users._form', ['mode' => 'show'])
    </div>
@endsection
