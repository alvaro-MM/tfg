@extends('layout.tfg')

@section('title', 'Editar Review')

@section('content')

    <livewire:review-form :review="$review" />

@endsection
