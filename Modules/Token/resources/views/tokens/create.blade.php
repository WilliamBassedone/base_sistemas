@extends('panel::app')

@section('title', 'Novo Token')
@section('page_title', 'Novo Token')

@section('content')
    <div class="tw:w-full">
        @include('token::tokens._form')
    </div>
@endsection
