@extends('layouts.app')
@section('content')
    <button onclick="refreshPage()" class="btn btn-primary my-3">Refresh Page</button>

    <div id='qrcode'>{!! $qrCode !!}</div>
    <div>{{ $uniqueId }}</div>

    <div id="chatContainer" class="d-flex flex-column justify-content-center align-content-center ">Chat</div>
    <button id="get_full_res">i didnt get the full response?</button>
    <div id="resultsContainer"></div>
    <script>
        function refreshPage() {
            window.location.reload();
        }
    </script>
    <script type="module">
        
        function fetchData(uniqueId) {

            var url = new URL("{{ route('stream.get.message') }}");
            url.searchParams.append('uniqueId', uniqueId);

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Required for Laravel to recognize the request as AJAX
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content') // CSRF token

                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('data :P')
                    console.log(data);

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


        console.log(Echo);
        Echo.channel(`chat.{{ $uniqueId }}`)
            .listen('MessageSent', (e) => {

                console.log(e);

                // Remove the QR code element
                const qrCodeDiv = document.getElementById('qrcode');
                if (qrCodeDiv) {
                    qrCodeDiv.remove();
                }

                // Append the message to the chat container
                const chatContainer = document.getElementById('chatContainer');
                chatContainer.innerText = '';
                const messageElement = document.createElement('p');
                messageElement.className = 'text-center border p-3 border my-2 mb-5';
                messageElement.innerText = 'your last message: ' + e
                    .message; // Adjust if your message structure is different
                chatContainer.appendChild(messageElement);



                const data = new FormData();
                data.append('question', e.message);
                data.append('uniqueId', e.uniqueId); // Replace 'key1' and 'value1' with your actual key and value

                fetch("{{ route('stream.create.message') }}", {
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
                        fetchData(e.uniqueId)
                        var intervalId = setInterval(() => fetchData(e.uniqueId), 4000);
                        setTimeout(() => {
                            clearInterval(intervalId);
                            console.log("Stopped fetching data after 30 seconds.");

                            var url = new URL("{{ route('stream.end.answer') }}");
                            url.searchParams.append('uniqueId', e.uniqueId);
                            fetch(url, {
                                    method: 'GET',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest', // Required for Laravel to recognize the request as AJAX
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content') // CSRF token

                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log('success :P')
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                });

                        }, 30000);
                        // Handle success
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        // Handle errors
                    });




            }).error((error) => {
                console.error('Could not subscribe to the channel:', error);
            });
    </script>
@endsection
