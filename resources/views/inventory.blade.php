@php use App\Models\Trade; @endphp
@extends('layout.master')

@section('content')

    <form class="inventory" action="" method="post">
        @csrf
        <nav class="inventory__nav">
            <div class="inventory__nav_filters">
                <img src="assets/icons/filter.svg" alt="filter">
                <h1>{{__('menu.filter')}}</h1>
            </div>
            <div class="inventory__nav_inventory">
                <a href="#">{{__('menu.inventory')}}</a>
            </div>
        </nav>
        <div class="inventory__content">
            @foreach($products as $ob)
                @php
                    $classes = in_array($ob->pivot->status, [Trade::STATUS_STORAGE, Trade::STATUS_ERROR], true) ? '' : 'inactive';
                @endphp
                @if (empty(($ob->imageurl)))
                    <div class="catalog__list_item catalog__item {{$classes}}"
                         title="Available: at {{$ob->pivot->possible_widthdraw_at}}">
                        @else
                            <div class="catalog__list_item catalog__item {{$classes}}"
                                 title="Available: at {{$ob->pivot->possible_widthdraw_at}}"
                                 style="background-image: url('{{$ob->imageurl}}')">
                                @endif
                                <div class="catalog__item_checkbox">
                                    @if (Carbon\Carbon::now() >= $ob->pivot->possible_widthdraw_at)
                                        <input type="checkbox" name="ids[{{$ob->pivot->id}}]"
                                               id="item{{$ob->pivot->id}}">
                                    @endif

                                    <label for="item{{$ob->pivot->id}}">
                                        <img src="assets/icons/checkmark-item.svg" alt="">
                                    </label>
                                </div>
                                <div class="empty"></div>
                                <div class="catalog__item_content">
                                    <div>
                                        <h1 class="catalog__item_title">{{$ob->name}}</h1>
                                        <h1 class="catalog__item_namestatus">Status </h1>
                                    </div>
                                    <div>
                                        <h1 class="catalog__item_status">{{$ob->pivot->status}}</h1>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                    </div>
                    <div class="inventory__control">
                        <div class="inventory__control_add">
                            <img src="assets/icons/plus-icon.svg" alt="">
                        </div>
                        <span class="inventory__control_info">please check items to make withdraw it</span>
                        <button class="inventory__btn btn-secondary" type="submit">Withdraw</button>
                    </div>
    </form>

@endsection
