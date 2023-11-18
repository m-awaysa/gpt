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
                    "id": 1,
                    "name": "cooler master mm711",
                    "slug": "cooler-master-mm711",
                    "image": "mouse3-cooler-master-mm711"
                },
                {
                    "id": 2,
                    "name": "LAPTOP ASUS TUF 15.6 i7-12650H SSD1TB RAM16",
                    "slug": "LAPTOP-ASUS-TUF-15-6-i7-12650H-SSD1TB-RAM16",
                    "image": "laptop1-i7-3070"
                },
                {
                    "id": 3,
                    "name": "LAPTOP LENOVO IP FLEX 5 14T i5-1235U SSD512 RAM8",
                    "slug": "LAPTOP-LENOVO-IP-FLEX-5-14Ti5-1235USSD512RAM8",
                    "image": "laptop2-lenovo"
                },
                {
                    "id": 4,
                    "name": "GPU ASUS TUF GTX1650S-O4G-GAMING",
                    "slug": "GPU-ASUS-TUF-GTX1650S-O4G-GAMING",
                    "image": "GPU-AS-1650S-O4-300"
                },
                {
                    "id": 5,
                    "name": "GPU GIGABYTE RTX4070-GAMING-OC-12GD REV1.0",
                    "slug": "GPU-GIGABYTE-RTX4070-GAMING-OC-12GD",
                    "image": "GPU-GB-4070-OC-5"
                },
                {
                    "id": 6,
                    "name": "KEYBOARD T-DAGGER BATTLESHIP T-TGK301",
                    "slug": "KEYBOARD-T-DAGGER-BATTLESHIP-T-TGK301",
                    "image": "KB-T-TGK301-MCA-30"
                },
                {
                    "id": 7,
                    "name": "KEYBOARD COOLERMASTER MK750 RGB MECHANICAL",
                    "slug": "KEYBOARD-COOLERMASTER-MK750-RGB-MECHANICAL",
                    "image": "KB-MK750-M-RED-5"
                },
                {
                    "id": 8,
                    "name": "MOUSE LENOVO M500 GAMING RGB",
                    "slug": "MOUSE-LENOVO-M500-GAMING-RGB",
                    "image": "MS-M500-RGB-300"
                },
                {
                    "id": 9,
                    "name": "MICROPHONE SILVERLINE MM-202 MULTIMEDIA",
                    "slug": "MICROPHONE-SILVERLINE-MM-202-MULTIMEDIA",
                    "image": "MIC-SL-MM-202-30"
                }
            ];

            const container = document.getElementById('drink-container');

            drinks.forEach(drink => {
                const card = document.createElement('div');
                card.className = 'col-md-4 mb-4';

                const cardBody = document.createElement('div');
                cardBody.className = 'card h-100';
                let urlTemplate = "{{ route('product', ':id') }}";
                let url = urlTemplate.replace(':id', drink.id);

                cardBody.innerHTML = `
            <div class="card-body" style="max-height:400px;">
                <a href="${url}">
                <img src="{{ asset('img/${drink.image}.png') }}" class="card-img" />
                <h6 class="card-title">${drink.name}.</h6>
            </div>
        `;

                card.appendChild(cardBody);
                container.appendChild(card);
            });
        });
</script>
<script>
    function fetchData() {
            fetch("{{ route('product.get.message') }}", {
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
                        let messageContent = res.content[0].text.value;
                        let messageDiv = document.createElement('div');
                        if (res.role == 'user') {
                            messageDiv.className = 'bg-light border my-2';
                        } else {
                            messageDiv.className = 'bg-secondary border my-2';
                        }

                        // Use innerHTML to render the HTML content
                        messageDiv.innerHTML = `message: ${messageContent}`;

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
            fetch("{{ route('product.create.message') }}", {
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
                    intervalId = setInterval(fetchData, 4000);
                    setTimeout(() => {
                        clearInterval(intervalId);
                        console.log("Stopped fetching data after 10 seconds.");
                    }, 30000);
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