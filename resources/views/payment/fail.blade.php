@extends('layout.master')

@section('content')

        <div class="status">
            <h1 class="status__title">Payment failed</h1>
            <h2 class="status__subtitle">Please try again or contact our Support Team</h2>
            <img class="status__img" src="{{ asset('assets/icons/failed.svg') }}" alt="failed">
            <button class="status__btn open-popup" @auth data-popup="popup4" @else data-popup="popup-login" @endauth>Try again</button>
        </div>

@endsection
