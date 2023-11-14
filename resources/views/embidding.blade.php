<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Styles -->

</head>

<body class="" style="height: 100vh;width:80%;margin:auto">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('ask.gpt') }}">ASK GPT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('embidding') }}">Embidding</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('ask.image') }}">iamge genarater</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('ask.about.image') }}">Ask About
                            Iamge</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('text.speech') }}">text->speech</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('speech.text') }}">speech->text</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('chat.bot') }}">Ai Bot</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('product.bot') }}">Product Ai Bot</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <form action="{{ route('ask-gpt-embidding') }}" method="POST" class="m-auto w-100">
        @csrf
        <textarea name="question" placeholder="Ask your question here..." style="min-height: 100px; width:100%">{{ old('question') }}</textarea>
        <p class="col-12 bg-success" style="min-height: 100px; width:100%">
        <div>{{ $spotted ?? '' }}</div>
        <br>
        <br>
        @if (isset($array) && $array != null)
            @foreach ($array as $arr)
                <div>

                    {{ $arr }}
                </div>
            @endforeach
        @endif

        </p>
        <button type="submit" class="submit">Get Recipe</button>
    </form>
</body>

</html>
