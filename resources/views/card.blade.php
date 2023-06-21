@extends('layout.master')

@section('content')

        <div class="main__nav" style="margin-top: 32px;">
            <div class="main__nav_bread">
                    <a href="/">{{__('menu.home')}}</a> / {{__('menu.catalog')}}
            </div>
            <h1 class="main__nav_title"></h1>
        </div>
        <div class="card">
            <div class="card__img" style="text-align:center">
                      <img src="{{$obProduct->imageurl}}" height="418"/>
            </div>
            <div class="card__content">
                <div class="card__content_description">
                    <h1 class="card__content_title">{{$obProduct->name}}</h1>
                    <h1 class="card__content_subtitle">Information</h1>
                </div>
                <div class="card__content_info">
                    <div>
                        <h1 class="card__content_type">Weapon: <!--AK47--></h1>
                        <h1 class="card__content_level">Wear level: {{$obProduct->param1}}</h1>
                    </div>
                </div>
                <div class="card__content_control">
                    <div class="card__content_price">
                        <img src="../assets/icons/coins.svg" alt="">
                        <span>{{number_format($obProduct->price, 2, '.', ',')}}</span>
                    </div>
                    <button class="card__content_btn btn-secondary open-popup" type="submit" data-popup="popup3" data-id="{{$obProduct->id}}" data-name="{{ \Illuminate\Support\Str::limit($obProduct->name, 18, $end='...') }}" data-price="{{number_format($obProduct->price, 2, '.', ',')}}">
                        <span>{{__('menu.cart')}}</span>
                        <img src="../assets/icons/tocart.svg" alt="">
                    </button>
                </div>
            </div>
        </div>
        @include(app()->getLocale().'.main.learn')

@endsection
