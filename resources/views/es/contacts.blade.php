@extends('layout.master')

@section('content')
        <main class="main">
            <form class="contact-us">
                <div class="container">
                    <h1 class="contact-us__content_title">Contact us</h1>
                    <div class="contact-us__content">
                        <div class="contact-us__content_column">
                            <div class="contact-us__content_input">
                                <h1>User name</h1>
                                <input type="text" placeholder="User name">
                            </div>
                            <div class="contact-us__content_input">
                                <h1>email</h1>
                                <input type="email" placeholder="Email">
                            </div>
                            <div class="contact-us__content_input">
                                <h1>phone number</h1>
                                <input type="number" placeholder="Phone number">
                            </div>
                        </div>
                        <div class="contact-us__content_column">
                            <div class="contact-us__content_input">
                                <h1>topic</h1>
                                <input type="text" placeholder="Topic">
                            </div>
                            <div class="contact-us__content_textarea">
                                <h1>YOUR INQUIRY</h1>
                                <textarea placeholder="Your inquiry"></textarea>
                            </div>
                        </div>
                    </div>
                    <button class="contact-us__btn btn-primary" type="submit">Contact</button>
                </div>
            </form>
        </main>
@endsection
