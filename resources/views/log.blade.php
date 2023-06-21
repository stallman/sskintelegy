@extends('layout.master')

@section('content')

    <div class="log log-wrapper">
        <div class="log__content">
            <div class="log__header">
                <a href="#" class="log__header_link">{{__('menu.transaction_log')}}</a>
            </div>
            @foreach($logs as $ob)
            <div class="log__row">
                <h1 class="log__row_date">{{$ob->created_at}}</h1>
                <h1 class="log__row_transaction  log-row-content">{{__('menu.deposit')}}</h1>
                <h1 class="log__row_amount">{{$ob->amount}}</h1>
                <h1 class="log__row_time"></h1>
            </div>
            @endforeach
        </div>
    </div>

@endsection
