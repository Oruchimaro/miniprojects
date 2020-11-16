<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ app()->getLocale() }}</title>

    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }



        .btn {
            position: absolute;
            bottom: 101px;
            right: 40vw;
            padding: 20px 40px;
            text-decoration: none;
            background: #3675DA;
            color: white;
            border-radius: 5px;
            font-size: 2em;
            font-weight: bold;
        }
    </style>

</head>

<body>

    <h1 class="title">
        @if( app()->getLocale() == 'en' )
        {{ $post->title_en }}
        @elseif ( app()->getLocale() == 'fa' )
        {{ $post->title_fa }}
        @elseif ( app()->getLocale() == 'fr' )
        {{ $post->title_fr }}
        @endif
    </h1>
    <a class="btn " href="{{ url('/') }}"> {{ __('messages.home') }} </a>

</body>

</html>

