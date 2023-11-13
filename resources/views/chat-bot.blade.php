<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    <div class="container mt-4">
        <div class="row" id="drink-container">
            <!-- Drink boxes will be added here dynamically -->
        </div>
    </div>

    <form id="myForm">
        @csrf
        <label for="question">Your question:</label>
        <input type="text" name="question" id="text_query" required>
        <button id='submitButton' type="submit">Submit</button>
    </form>
    <div id="resultsContainer"></div>

    @if (isset($response))
        @foreach ($response as $res)
            <div class="bg-success border my-2">

                <p>message:</p>
                <p>{{ $res['content'][0]['text']['value'] }}</p>
            </div>
        @endforeach

    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const drinks = [{
                    "id": "drink1",
                    "temperature": "hot",
                    "type": "tea",
                    "sugar": "yes",
                    "additives": [
                        "mint"
                    ],
                    "size": [
                        "large"
                    ]
                },
                {
                    "id": "drink2",
                    "temperature": "hot",
                    "type": "tea",
                    "sugar": "no",
                    "additives": [
                        "milk"
                    ],
                    "size": [
                        "large",
                        "small"
                    ]
                },
                {
                    "id": "drink3",
                    "temperature": "iced",
                    "type": "tea",
                    "sugar": "yes",
                    "additives": [
                        "milk"
                    ],
                    "size": [
                        "large",
                        "small"
                    ]
                },
                {
                    "id": "drink4",
                    "temperature": "iced",
                    "type": "tea",
                    "sugar": "no",
                    "additives": [
                        "milk", "coffe"
                    ],
                    "size": [
                        "large",
                        "small"
                    ]
                },
                {
                    "id": "drink5",
                    "temperature": "hot",
                    "type": "coffe",
                    "sugar": "no",
                    "additives": [
                        "milk"
                    ],
                    "size": [
                        "large",
                        "small"
                    ]
                },
                {
                    "id": "drink6",
                    "temperature": "hot",
                    "type": "coffe",
                    "sugar": "yes",
                    "additives": [
                        "milk"
                    ],
                    "size": [
                        "large",
                        "small"
                    ]
                },
                {
                    "id": "drink7",
                    "temperature": "iced",
                    "type": "coffe",
                    "sugar": "yes",
                    "additives": [

                    ],
                    "size": [
                        "large",
                        "small"
                    ]
                },
                {
                    "id": "drink8",
                    "temperature": "cold",
                    "type": "juice",
                    "sugar": "yes",
                    "additives": [
                        "apple", "orange"
                    ],
                    "size": [
                        "large",
                        "small",
                        "extra large"
                    ]
                },
                {
                    "id": "drink9",
                    "temperature": "cold",
                    "type": "juice",
                    "sugar": "no",
                    "additives": [
                        "apple"
                    ],
                    "size": [
                        "large",
                        "small",
                        "extra small"
                    ]
                },
                {
                    "id": "drink10",
                    "temperature": "hot",
                    "type": "juice",
                    "sugar": "yes",
                    "additives": [
                        "orange"
                    ],
                    "size": [
                        "large",
                        "small"
                    ]
                },
                {
                    "id": "drink11",
                    "temperature": "hot",
                    "type": "juice",
                    "sugar": "no",
                    "additives": [
                        "orange", "extra sugare"
                    ],
                    "size": [
                        "large",
                        "small"
                    ]
                }
            ];

            const container = document.getElementById('drink-container');

            drinks.forEach(drink => {
                const card = document.createElement('div');
                card.className = 'col-md-4 mb-4';

                const cardBody = document.createElement('div');
                cardBody.className = 'card h-100';
                cardBody.innerHTML = `
            <div class="card-body">
                <h5 class="card-title">${drink.id.toUpperCase()}</h5>
                <h6 class="card-title">${drink.type.toUpperCase()} - ${drink.temperature.toUpperCase()}</h6>
                <p class="card-text">Sugar: ${drink.sugar}</p>
                <p class="card-text">Additives: ${drink.additives.join(', ') || 'None'}</p>
                <p class="card-text">Sizes: ${drink.size.join(', ')}</p>
            </div>
        `;

                card.appendChild(cardBody);
                container.appendChild(card);
            });
        });
    </script>
    <script>
        function fetchData() {
            fetch("{{ route('get.message') }}", {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Required for Laravel to recognize the request as AJAX
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content') // CSRF token
                    }
                })
                .then(response => response.json())
                .then(data => {
                    
                    document.getElementById('submitButton').disabled = false;

                    const container = document.getElementById('resultsContainer');
                    container.innerHTML = ''; // Clear previous results

                    data.data.forEach(res => {
                        // Assuming each item in the array has a content array with at least one text object
                        let messageContent = res.content[0].text.value;
                        let messageDiv = document.createElement('div');
                        messageDiv.className = 'bg-success border my-2';

                        let messagePara = document.createElement('p');
                        messagePara.textContent = `message: ${messageContent}`;

                        messageDiv.appendChild(messagePara);
                        container.appendChild(messageDiv);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }


        document.getElementById('myForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the default form submit action
            document.getElementById('submitButton').disabled = true;

            const data = new FormData(this); // Create a FormData object from the form
            fetch("{{ route('create.message') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Required for Laravel to recognize the request as AJAX
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content') // CSRF token
                    },
                    body: data
                })
                .then(response => response)
                .then(data => {
                    console.log('Success:');
                    fetchData
                    setInterval(fetchData, 3000);
                    // Handle success
                })
                .catch((error) => {
                    console.error('Error:', error);
                    // Handle errors
                });
        });

        // Call fetchData every 2000 milliseconds (2 seconds)
    </script>
</body>

</html>
