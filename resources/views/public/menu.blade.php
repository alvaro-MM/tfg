@extends('layouts.public')

@section('title', 'Menú - ' . $table->name)

@section('header-title', 'Menú - ' . $table->name)

@section('content')
    @livewire('public-menu', ['token' => $table->qr_token], key('menu-' . $table->qr_token))
@endsection
