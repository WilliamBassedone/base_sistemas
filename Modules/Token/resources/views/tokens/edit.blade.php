@extends('panel::app')

@section('title', 'Editar Token')
@section('page_title', 'Editar Token')

@section('content')
    <div class="tw:w-full">
        @include('token::tokens._form')
    </div>
@endsection
