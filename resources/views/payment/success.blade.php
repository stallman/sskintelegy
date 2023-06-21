@extends('layout.master')

@section('content')
        <div class="status">
            <h1 class="status__title">Payment successful</h1>
            <h2 class="status__subtitle">Transaction details are available in the History section of your account</h2>
            <img class="status__img" src="{{ asset('assets/icons/successful.svg') }}" alt="successful">
            <a href="{{ url('/') }}" class="status__btn">Back to page</a>
        </div>
@endsection
