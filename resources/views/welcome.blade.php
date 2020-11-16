<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
         .btn {
            position: absolute;
            bottom: 100px;
            right: 40%;
            padding: 20px 40px;
            text-decoration: none;
            width: 10%;
            background: #3675DA;
            color: white;
            border-radius: 5px;
            font-size: 2em;
            font-weight: bold;
        }
        </style>

        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="title m-b-md">
            {{ __('messages.welcome') }}
        </div>

        <div class="links">
            <a href="{{ url('/locale/en') }}">English</a>
            <a href="{{ url('/locale/fr') }}">French</a>
            <a href="{{ url('/locale/fa') }}">Farsi</a>
        </div>


        <a class="btn" href="{{ url('/post') }}"> {{ __('messages.cont') }} </a>
    </body>
</html>
