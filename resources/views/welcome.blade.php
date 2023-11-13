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
                        <a class="nav-link" aria-current="page" href="{{ route('chat.bot') }}">Ai Bot</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>


    <div class="d-flex flex-column m-auto align-content-center justify-content-center">
        <form action="{{ route('gpt') }}" method="POST" class="m-auto w-100">
            @csrf



            <div class="d-flex row col-12 w-100" style="height:auto; min-height:500px">

                <textarea class="col-6" type="text" name="question" style="min-height: 100%; width:50%"
                    placeholder="Ask a normal question">{{ old('question') }}</textarea>
                <p class="col-6 bg-success" style="min-height: 100%">

                    {{ $answer ?? '' }}
                </p>
            </div>
            <div>
                <button class="submit">Ask chat gpt a question</button>
            </div>
        </form>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <form action="{{ route('ask-gpt') }}" method="POST" class="m-auto w-100">
            @csrf
            <div class="d-flex row col-12 w-100" style="height:auto; min-height:500px">
                <textarea class="col-6" name="context" placeholder="Enter the context here..." style="min-height: 100%; width:50%">{{ old('context') }}</textarea>
                <textarea class="col-6" name="question_for_context" placeholder="Ask a question about the context..."
                    style="min-height: 100%; width:50%">{{ old('question_for_context') }}</textarea>
            </div>
            <div class="col-12">
                <p class="bg-success" style="min-height: 300px">

                    {{ $answer_on_context ?? '' }}
                </p>
            </div>
            <div>
                <button type="submit" class="submit">Ask ChatGPT</button>
            </div>
        </form>

    </div>
</body>

</html>
