@extends('panel::app')

@section('title', 'Tokens')
@section('page_title', 'Tokens')

@section('content')
    @include('token::tokens.index')
@endsection
