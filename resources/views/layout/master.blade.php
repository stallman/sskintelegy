<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/lib/dropdown/dropdown.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/custom.css">
    <link rel="stylesheet" href="/lib/dropdown/dropdown.css">
    <link rel="stylesheet" href="/lib/range/range.css">
    <link rel="stylesheet" href="/lib/alerts/alerts.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <title>Skintelegy</title>
</head>

<body>
    <div class="wrapper">
        <header class="header">
            <div class="container">
                <div class="header__main">
                    <div class="header__logo">
                        <a href="/">
                            <h1 class="header__logo_title">skintelegy</h1>
                        </a>
                        <img src="../assets/logo.png" alt="logo" class="header__logo_img">
                    </div>
                    <div class="header__dropdowns">
                        <div class="dropdown select">
                            <button class="dropbtn open-dropdown" type="button">
                                @foreach (config('app.available_locales') as $key => $val)
                                    @if (app()->getLocale() == $val)
                                        <span class="selected-option">{{$val}}</span>
                                    @endif
                                @endforeach
                                <img src="../assets/icons/dropdown-arrow.svg" alt="arrow-bottom" class="open-dropdown__img">
                            </button>

                            <div class="langs top-lang-switcher hide-mob dropdown-content">
                                @foreach (config('app.available_locales') as $key => $val)
                                    @if (app()->getLocale() != $val)
                                        <a href="#" class="dropdown-item" data-lang="{{$val}}">{{$val}}</a>
                                    @endif
                                @endforeach
                                {{--@if (app()->getLocale() == 'ru')
                                <a href="#" class="dropdown-item" data-lang="en">
                                    En
                                </a>
                                @elseif (app()->getLocale() == 'en')
                                <a href="#" class="dropdown-item" data-lang="ru">
                                    Ru
                                </a>
                                @endif--}}
                            </div>

                        </div>
                        @if(\Illuminate\Support\Facades\Auth::check())
                        <div class="header__avatar">
                            <img src="http://www.gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->email))) }}?d=identicon" widht="48" height="48" />
                        </div>
                        <div class="dropdown header__balance">
                            <button class="dropbtn open-dropdown" type="button">
                                <h1>{{__('menu.account_balance')}}: <span id="yellow">S</span>{{number_format(Auth::user()->balance, 2, '.', ',')}}</h1>
                                <div>
                                    <span>+</span>
                                </div>
                            </button>
                            <div class="dropdown-content">
                                <a href="#" class="dropdown-item open-popup" data-popup="popup4">
                                    <img src="../assets/icons/dropdown/solar_wallet-money-outline.svg" alt="solar_wallet-money-outline">
                                    <span>{{__('menu.ski_balance')}}</span>
                                </a>
                                <a href="/inventory" class="dropdown-item" onclick="window.location.href='/inventory'">
                                    <img src="../assets/icons/dropdown/Vector.svg" alt="Vector">
                                    <span>{{__('menu.inventory')}}</span>
                                </a>
                                <a href="/log" class="dropdown-item" onclick="window.location.href='/log'">
                                    <img src="../assets/icons/dropdown/bi_file-earmark-bar-graph.svg" alt="bi_file-earmark-bar-graph">
                                    <span>{{__('menu.history')}}</span>
                                </a>
                                <a href="#" class="dropdown-item open-popup" data-popup="popup1">
                                    <img src="../assets/icons/dropdown/material-symbols_settings.svg" alt="material-symbols_settings">
                                    <span>{{__('menu.settings')}}</span>
                                </a>
                                <a href="/logout" class="dropdown-item" onclick="window.location.href='/logout'">
                                    <img src="../assets/icons/dropdown/ion_exit-outline.svg" alt="ion_exit-outline">
                                    <span>{{__('menu.logout')}}</span>
                                </a>
                            </div>
                        </div>
                        @else
                        <button class="login open-popup" data-popup="popup-login">{{__('menu.login')}}</button>
                        <button class="open-popup" style="background-color:white" data-popup="popup-register">{{__('menu.reg')}}</button>
                        @endif

                    </div>
                </div>
                <nav class="header__nav">
                    <div class="header__nav_content">
                        @foreach ($categories as $ob)
                            <a href="/catalog?c={{$ob->slug}}" class="header__nav_item">{{$ob->name}}</a>
                        @endforeach
                    </div>
                    <input type="text" class="header__nav_search" placeholder="{{__('menu.search')}}" id="search">

                </nav>
            </div>
        </header>

        <main class="main">
            <div class="container">
                    @if(session()->has('success'))
                        <div class="alerts">
                            <div class="alerts__container">
                                <div class="alert active">
                                    <img src="../assets/icons/alert-close.svg" alt="close" class="alert__close">
                                    <img src="../assets/icons/alert-warn.svg" alt="warn" class="alert__img">
                                    <h1 class="alert__info">Success</h1>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($errors->any())

                        @foreach ($errors->all() as $error)
                        <div class="alerts">
                            <div class="alerts__container">
                                <div class="alert active">
                                    <img src="../assets/icons/alert-close.svg" alt="close" class="alert__close">
                                    <img src="../assets/icons/alert-warn.svg" alt="warn" class="alert__img">
                                    <h1 class="alert__info">{{$error}}</h1>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
            @yield('content', 'not content')
            </div>
        </main>

        <footer class="footer">
            <div class="container">
                <div class="footer__main">
                    <div class="footer__main_item">
                        <h1 class="footer__main_title">skintelegy</h1>
                        <p class="footer__main_description">Skintelegy LTD *****st, 00000 London, UK<br> Registration
                            number:
                            000000</p>
                    </div>
                    <div class="footer__main_item">
                        <div class="footer__main_socialmedia">
                            <div></div>
                            <div></div>
                        </div>
                        <p class="footer__main_copyright">©The Valve logo, the Steam logo, the logos and arts of CS:GO,
                            along with all other registered trademarked logos, in-game items, and arts on SKINTELEGY.COM
                            are
                            the property of their respective owners. Skintelegy LTD is in no way affiliated with or
                            endorsed
                            by Valve Corporation.</p>
                    </div>
                </div>
                <nav class="footer__nav">
                    <div class="footer__nav_column">
                        <h1 class="footer__nav_title">{{__('menu.info')}}</h1>
                        <div class="footer__nav_content">
                            <a href="/about" class="footer__nav_link">{{__('menu.about')}}</a>
                            <a href="/faq" class="footer__nav_link">{{__('menu.faq')}}</a>
                            <a href="/terms" class="footer__nav_link">{{__('menu.policy')}}</a>
                            <a href="/terms" class="footer__nav_link">{{__('menu.terms')}}</a>
                            <a href="/contacts" class="footer__nav_link">{{__('menu.contact')}}</a>
                        </div>
                    </div>
                    <div class="footer__nav_column">
                        <h1 class="footer__nav_title">{{__('menu.navi')}}</h1>
                        <div class="footer__nav_content">
                            
                               
                                    
                                    <div>
                                        @foreach ($categories as $ob)
                                            @if ($loop->index<4)
                                                <a href="/catalog?c={{$ob->slug}}" class="footer__nav_link">{{$ob->name}}</a>
                                            @endif
                                         @endforeach
                                    </div> 
                                    <div>
                                        @foreach ($categories as $ob)
                                            @if ($loop->index>=4)
                                                <a href="/catalog?c={{$ob->slug}}" class="footer__nav_link">{{$ob->name}}</a>
                                            @endif
                                         @endforeach
                                    </div>
                        </div>
                    </div>
                </nav>
            </div>
        </footer>
    </div>

    <!-- LOGIN POPUP -->
    <div class="popup popup-settings" id="popup-login">
        <div class="popup__content popup-settings__container">
            <form method="post" action="/my/login" id="login">
                <div class="popup-settings__content" style="gap: 50px !important">
                    <h1 class="popup-settings__content_title">{{__('menu.login')}}</h1>
                    <div class="login-alert"></div>
                    <div class="popup-settings__inputs">

                        <div class="popup-settings__row">
                            <div class="popup-settings__column">
                                <h1 class="popup-settings__column_title">{{__('menu.email')}}</h1>
                                <input name="email" type="email" class="popup-settings__column_input" placeholder="">
                            </div>
                            <div class="popup-settings__column">
                                <h1 class="popup-settings__column_title">{{__('menu.password')}}</h1>
                                <input name="password" type="password" class="popup-settings__column_input" placeholder="*******">
                            </div>
                        </div>
                    </div>
                    <button class="popup-settings__btn" type="submit">{{__('menu.confirm')}}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- REGISTER POPUP -->
    <div class="popup popup-settings" id="popup-register">
        <div class="popup__content popup-settings__container">
            <div class="popup-settings__content" style="gap: 50px !important">
                <h1 class="popup-settings__content_title">{{__('menu.reg')}}</h1>
                <form method="post" action="/my/register" id="registration">
                    <div class="login-alert"></div>
                    <div class="popup-settings__inputs">
                        <div class="popup-settings__row">
                            <div class="popup-settings__column">
                                <h1 class="popup-settings__column_title">{{__('menu.username')}}</h1>
                                <input name="name" type="text" class="popup-settings__column_input" placeholder="" required>
                            </div>
                            <div class="popup-settings__column">
                                <h1 class="popup-settings__column_title">{{__('menu.email')}}</h1>
                                <input name="email" type="email" class="popup-settings__column_input" placeholder="" required>
                            </div>
                            <div class="popup-settings__column">
                                <h1 class="popup-settings__column_title">{{__('menu.password')}}</h1>
                                <input name="password" type="password" class="popup-settings__column_input" placeholder="" required>
                            </div>
                            <div class="popup-settings__column">
                                <h1 class="popup-settings__column_title">{{__('menu.password_repeat')}}</h1>
                                <input name="password_repeat" type="password" class="popup-settings__column_input" placeholder="" required>
                            </div>

                            {{--<div class="popup-settings__column">

                                <h1 class="popup-settings__column_title">{{__('menu.captcha')}}</h1>
                                <div style="text-align: center; border: 1px solid #FFFFFF; height:56px; border-radius: 25px; width: 100%">
                                    <a href="#" id="reload_captcha">
                                        <div style="margin-top: 10px;"><?= captcha_img() ?></div>
                                    </a>
                                </div>

                            </div>
                            <div class="popup-settings__column">
                                <h1 class="popup-settings__column_title">{{__('menu.captcha_enter')}}</h1>

                                <input name="captcha" type="text" class="popup-settings__column_input" placeholder="" required>
                            </div>--}}
                        </div>
                    </div>
            </div>
            <br /><br />
            <button class="popup-settings__btn" type="submit">{{__('menu.confirm')}}</button>
            </form>

        </div>
    </div>
    </div>


    <!-- BUY PRODUCT POPUP -->
    <div class="popup popup-order" id="popup3">
        <form class="popup__content popup-order__content" action="/innerpayment" method="post">
            <img src="/assets/icons/close2.svg" alt="" data-popup="popup3" class="close-popup">
            <div class="popup-order__container">
                <h1 class="popup-order__container_title">{{__('menu.order')}}:</h1>
                <div class="popup-order__card">
                    <div class="popup-order__card_name">
                        <div></div>
                        <h1 id="name"></h1>
                    </div>
                    <div class="popup-order__card_price">
                        <h1 name="price">9 999 <span>S</span></h1>
                        <img src="/assets/icons/delete.svg" alt="delete">
                    </div>
                </div>
                <h1 class="popup-order__price">{{__('menu.sum')}}: <d name="price">9 999</d> <span>S</span></h1>
                <input type="text" class="popup-order__promo" placeholder="{{__('menu.promo')}}" />
                <input type="hidden" class="" name="id" />
                @csrf
                <label class="order-policy">{{__('menu.allow_policy')}}
                    <input type="checkbox" checked="checked" name="radio">
                    <span class="order-policy__checkmark"></span>
                </label>
                <div class="popup-order__total">
                    <h1 class="popup-order__total_sum">{{__('menu.sum')}}: <d name="price">9 999</d> <span>S</span></h1>
                    <h1 class="popup-order__total_total">{{__('menu.total')}}: <d name="price">9 000<> <span>S</span></h1>
                </div>
                <button class="popup-order__btn btn-secondary" type="submit">{{__('menu.pay')}}</button>
            </div>
        </form>
    </div>


    {{-- AUTH ZONE --}}
    @if(\Illuminate\Support\Facades\Auth::check())

    <!-- EDIT PROFILE POPUP -->
    <div class="popup popup-settings" id="popup1">
        <div class="popup__content popup-settings__container">
            <form method="post" action="{{route('profile');}}" id="profile">
                <div class="popup-settings__content">
                    <h1 class="popup-settings__content_title">
                        @csrf
                        {{--<input name="name" class="popup-balance__spend_input" style="width: 90%; font-size:80px; text-align: center" value="{{Auth::user()->name}}"><img src="/assets/icons/pen.svg" alt="">--}}
                        {{__('menu.settings')}}
                    </h1>
                    <div class="popup-settings__inputs">
                        <div class="popup-settings__row">
                            <div class="popup-settings__column">
                                <h1 class="popup-settings__column_title">{{__('menu.username')}}</h1>
                                <input name="name" type="text" class="popup-settings__column_input" placeholder="" value="{{Auth::user()->name}}">
                            </div>
                            <div class="popup-settings__column">
                                <h1 class="popup-settings__column_title">{{__('menu.password')}}</h1>
                                <input name="password" type="password" class="popup-settings__column_input" placeholder="{{__('menu.password_new')}}" value="">
                            </div>
                        </div>
                        <div class="popup-settings__row">
                            <div class="popup-settings__column">
                                <h1 class="popup-settings__column_title">{{__('menu.tradelink')}}</h1>
                                <input name="steamapi" type="text" class="popup-settings__column_input" placeholder="{{__('menu.tradelink')}}" value="{{Auth::user()->steamapi}}">
                            </div>
                        </div>
                    </div>
                    <button class="popup-settings__btn" type="submit">{{__('menu.confirm')}}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- LOADING POPUP -->
    <div class="popup popup-loading " id="loading-popup">
        <p class="popup-balance__head_title">Please wait...</p>
    </div>

    <!-- REAL BALANCE POPUP -->
    <div class="popup popup-balance" id="popup4">
        <div class="popup__content popup-balance__content">
            <div class="popup-balance__head">
                <h1 class="popup-balance__head_title">{{__('menu.balance')}}</h1>
                <img src="assets/icons/close1.svg" alt="close" class="popup-balance__head_img close-popup" data-popup="popup4">
            </div>
            <div class="popup-balance__balance">
                <img src="assets/images/balance-logo.png" alt="balance img">
                <h1 class="popup-balance__balance_title">
                    <f>{{number_format(Auth::user()->balance, 2, '.', ',')}}</f>
                </h1>
            </div>
            <form class="popup-balance__container" method="POST" action="{{ route('payment.create', ['type' => 'quickpayments']) }}">

                <div class="popup-balance__container_main">
                    <div class="popup-balance__spend">
                        <div class="popup-balance__spend_head">
                            <div>
                                <h1>Euro</h1>
                                <h1>1 EUR.</h1>
                            </div>
                            <img src="assets/icons/euro.svg" alt="euro">
                        </div>
                        <div class="popup-balance__spend_content">
                            <h1>{{__('menu.spent')}}</h1>
                            <div>
                                <div>
                                    <input name="amount" id="youspent" placeholder="3,757.00" class="popup-balance__spend_input" type="number" value="500"/>
                                    <span>EUR.</span>
                                </div>
                                <div></div>
                                <p>{{__('menu.min_dep')}} * EUR</p>
                            </div>
                        </div>
                    </div>
                    <div class="popup-balance__get">
                        <div class="popup-balance__spend_head">
                            <div>
                                <h1>Skintellions</h1>
                                <h1>1 EUR.</h1>
                            </div>
                            <div>
                                <img src="assets/icons/get.svg" alt="euro">
                            </div>
                        </div>
                        <div class="popup-balance__spend_content">
                            <h1>{{__('menu.get')}}</h1>
                            <div>
                                <div style="justify-content: start;">
                                    <input id="youget" placeholder="3,757.00" class="popup-balance__spend_input" type="number" value="500"/>
                                    <img src="assets/icons/skin-mini.svg" alt="">
                                </div>
                                <div></div>
                                <p>{{__('menu.max_dep')}} ***** EUR</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="popup-balance__btn btn-primary">{{__('menu.buy')}} Skintellions</button>
                @csrf
            </form>
        </div>
    </div>

    @endif

    @if(Session::has('redirectUrl'))
    <script>
        let loadingPopup = document.getElementById('loading-popup');
        document.body.classList.add('popup-overflow');
        loadingPopup.classList.add('active');
        setTimeout(() => {
            window.location.href = '{{ Session::get('redirectUrl') }}';
        }, 2000);
    </script>
    @endif

    <script src="/lib/popup/popup.js"></script>
    <script src="/lib/dropdown/dropdown.js"></script>
    <script src="/lib/range/range.js"></script>
    <script src="/lib/accordion/accordion.js"></script>
    <script src="/lib/alerts/script.js"></script>
    <script src="/js/form.js?v=<?= time() ?>"></script>

</body>

</html>
