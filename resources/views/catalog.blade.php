@extends('layout.master')

@section('content')
        <div class="main__nav">
            <div class="main__nav_bread">
                <a href="/">{{__('menu.home')}}</a> / {{__('menu.catalog')}}
            </div>
            <h1 class="main__nav_title">@if(!empty($search)) {{__('menu.search')}}: {{$search}} @else {{__('menu.catalog')}} CS:GO  @endif</h1>
        </div>
        <div class="catalog">
            <form class="catalog-filters">
                <div class="catalog-filters__price">
                    <h1 class="catalog-filters__price_title">{{__('menu.price')}}</h1>
                    <div class="range">
                        <div class="range-slider">
                            <span style="left: {{$min/$maxdb*100}}%; right: {{100-$max/$maxdb*100}}%" class="range-selected"></span>
                        </div>
                        <div class="range-input">
                            <input type="range" class="min" min="0" max="{{$maxdb}}" value="{{$min}}">
                            <input type="range" class="max" min="0" max="{{$maxdb}}" value="{{$max}}">
                        </div>
                        <div class="range-price">
                            <input type="number" id="min" name="min" value="{{$min}}">
                            <span>-</span>
                            <input type="number" id="max" name="max" value="{{round($max)}}">
                        </div>
                    </div>
                    <div>
                        <div class="accordion">
                            <div class="accordion-item">
                                <div class="accordion-header active">
                                    <span>Skin type</span>
                                    <img src="../assets/icons/dropdown-arrow.svg" alt="">
                                </div>
                                <div class="accordion-content active">
                                    @foreach ($categories as $ob)
                                                <label class="checkbox__container">{{$ob->name}}
                                                    <div class="checkbox__content">
                                                        <input type="checkbox" name="categories[]" value="{{$ob->slug}}" @if(in_array($ob->slug, $categoriesSlug, true)) checked @endif>
                                                        <span class="checkmark"></span>
                                                    </div>
                                                </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
    
                    </div>
                    <div class="catalog-filters__price_control">
                        <button class="btn-secondary" id="reset" type="submit" name="reset">{{__('menu.reset')}}</button>
                        <button class="btn-primary" type="submit" id="search_y">{{__('menu.search')}}</button>
                    </div>
                </div>
            </form>
            <div class="catalog-content">
                <div class="catalog-content__sorts">
                    <div class="catalog-content__sort" id="order_by_price" sort="{{$sort}}">
                        <h1 class="catalog-content__sort_name">{{__('menu.by_price')}}</h1>
                        <img src="../assets/icons/sort.svg" alt="sort">
                    </div>
                    <div class="catalog-content__sort" id="order_by_pop" sort="{{$sort}}">
                        <h1 class="catalog-content__sort_name">{{__('menu.by_pop')}}</h1>
                        <img src="../assets/icons/sort.svg" alt="sort">
                    </div>
                </div>
                <div class="catalog-content__items">
                    @foreach($arProducts as $ob)
                    <a href="{{route('card', $ob->id)}}">
                        @if (empty(($ob->imageurl)))
                        <div class="catalog__list_item catalog__item">
                        @else
                        <div class="catalog__list_item catalog__item" style="background-image: url('{{$ob->imageurl}}')">
                        @endif
                            <div class="empty"></div>
                            <div class="catalog__item_content" >
                                <div>
                                    <h1 class="catalog__item_title">{{ \Illuminate\Support\Str::limit($ob->name, 18, $end='...') }}</h1>
                                    <img src="../assets/icons/cart.svg" alt="cart">
                                </div>
                                <h1 class="catalog__item_price">Price<span><h1>S</h1>&nbsp;{{number_format($ob->price, 2, '.', ',')}}</span></h1>
                            </div>
                        </div>
                    </a>
                    @endforeach

                </div>
                <div class="catalog-content__pagination">


                @if ($arProducts->lastPage() > 1)
                    @php
                    $iStart = 1;
                    $iNow = $arProducts->currentPage();
                    $iEnd = $arProducts->lastPage();

                    if ($iEnd - $iNow >= 4 && $iNow - $iStart >= 4) {
                        $iStart2 = $iNow - 2 > $iStart ? $iNow - 2 : $iStart + 1;
                        $iEnd2 = $iNow +3 < $iEnd ? $iNow + 3 : $iEnd - 1;
                    } elseif ($iEnd - $iNow < 4 ) {
                        $iStart2 = $iNow - (5 - ($iEnd - $iNow)) > $iStart ? $iNow - (5 - ($iEnd - $iNow)) : $iStart + 1;
                        $iEnd2 = $iEnd - 1;
                    } elseif ($iNow - $iStart < 4) {
                        $iStart2 = $iStart + 1;
                        $iEnd2 = $iNow + (5 - ($iNow - $iStart)) < $iEnd ? $iNow + (5 - ($iNow - $iStart)) : $iEnd - 1;
                    }
                    @endphp

                    <button class="catalog-content__pagination_btn">
                        <a class="page-link" href="{{$arProducts->previousPageUrl()}}">
                        <img src="/assets/icons/pagination.svg" alt="pagination"></a>
                    </button>

                    <a class="page-link" href="{{$arProducts->url(1)}}"><div class="catalog-content__page">{{1}}</div></a>
                    @if ($iStart2 - $iStart > 1)
                        <div class="catalog-content__page">...</div>
                    @endif
                    @for ($i = $iStart2; $i <= $iEnd2; $i++)
                        <a class="page-link" href="{{$arProducts->url($i)}}"><div class="catalog-content__page">{{$i}}</div></a>
                    @endfor
                    @if ($iEnd - $iEnd2 > 1)
                        <div class="catalog-content__page">...</div>
                    @endif
                    <a class="page-link" href="{{$arProducts->url($arProducts->lastPage())}}"><div class="catalog-content__page">{{$arProducts->lastPage()}}</div></a>

                    <button class="catalog-content__pagination_btn">
                        <a class="page-link" href="{{$arProducts->nextPageUrl()}}">
                        <img src="../assets/icons/pagination.svg" alt="pagination" style="transform: rotate(180deg);"></a>
                    </button>
                @endif


                </div>
            </div>
        </div>
        @include(app()->getLocale().'.catalog.learn')

@endsection
