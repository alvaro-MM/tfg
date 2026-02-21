@extends('layout.tfg')

@section('title', 'Nueva review')

@section('content')

    <livewire:review-form :dish_id="$dish_id" />

@endsection

