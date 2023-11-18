@extends('layouts.app')
@section('content')
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
                    console.log(data);
                    document.getElementById('submitButton').disabled = false;

                    const container = document.getElementById('resultsContainer');
                    container.innerHTML = ''; // Clear previous results

                    data.data.forEach(res => {

                        // Assuming each item in the array has a content array with at least one text object
                        let messageContent = res.content[0].text.value;
                        let messageDiv = document.createElement('div');
                        if (res.role == 'user') {
                            messageDiv.className = 'bg-light border my-2';
                        } else {
                            messageDiv.className = 'bg-secondary border my-2';
                        }

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
                    console.log(data);
                    fetchData
                    intervalId= setInterval(fetchData, 2000);
                    setTimeout(() => {
                        clearInterval(intervalId);
                        console.log("Stopped fetching data after 10 seconds.");
                    }, 10000);
                    // Handle success
                })
                .catch((error) => {
                    console.error('Error:', error);
                    // Handle errors
                });
        });

        // Call fetchData every 2000 milliseconds (2 seconds)
</script>

@endsection