@extends('layout.master')

@section('content')

    @include(app()->getLocale().'.main.bg1')
    <div class="catalog__list">
        <div class="catalog__list_head">
            <h1 class="catalog__list_title">{{__('menu.topskins')}}</h1>
            <a href="/catalog" class="catalog__list_more">{{__('menu.viewall')}}</a>
        </div>
        <div class="catalog__list_content">

            @foreach($arProductsTop as $ob)
            <a href="{{route('card', $ob->id)}}">
                @if (empty(($ob->imageurl)))
                <div class="catalog__list_item catalog__item">
                @else
                <div class="catalog__list_item catalog__item" style="background-image: url('{{$ob->imageurl}}')">
                @endif

                <div class="empty"></div>
                <div class="catalog__item_content">
                    <div>
                        <h1 class="catalog__item_title">{{ \Illuminate\Support\Str::limit($ob->name, 20, $end='...') }}</h1>
                        <img src="assets/icons/cart.svg" alt="cart">
                    </div>
                    <h1 class="catalog__item_price">Price<span><h1>S</h1>&nbsp;{{number_format($ob->price, 2, '.', ',')}}</span></h1>
                </div>
            </div>
            </a>
            @endforeach
        </div>
    </div>
    <div class="quality">
        <div class="container">
            @include(app()->getLocale().'.main.bg2')
        </div>
    </div>
    <div class="catalog__list">
        <div class="catalog__list_head">
            <h1 class="catalog__list_title">{{__('menu.newskins')}}</h1>
            <a href="/catalog" class="catalog__list_more">{{__('menu.viewall')}}</a>
        </div>
        <div class="catalog__list_content">
            @foreach($arProductsNew as $ob)
                <a href="{{route('card', $ob->id)}}">
                @if (empty(($ob->imageurl)))
                <div class="catalog__list_item catalog__item">
                @else
                <div class="catalog__list_item catalog__item" style="background-image: url('{{$ob->imageurl}}')">
                @endif
                    <div class="empty"></div>
                    <div class="catalog__item_content">
                        <div>
                            <h1 class="catalog__item_title">{{ \Illuminate\Support\Str::limit($ob->name, 20, $end='...') }}</h1>
                            <img src="assets/icons/cart.svg" alt="cart">
                        </div>
                        <h1 class="catalog__item_price">Price<span><h1>S</h1>&nbsp;{{number_format($ob->price, 2, '.', ',');}}</span></h1>
                    </div>
                </div>
                </a>
            @endforeach
        </div>
    </div>
    @include(app()->getLocale().'.main.how')

    @include(app()->getLocale().'.main.learn')

@endsection