@extends('layouts.app')
@section('content')
    <div id='qrcode'>{!! $qrCode !!}</div>
    <div>{{ $uniqueId }}</div>

    <div id="chatContainer" class="d-flex flex-column justify-content-center align-content-center ">Chat</div>
    <div id="resultsContainer"></div>
    <script type="module">
        function fetchData(uniqueId) {
            var bodyData = new FormData();
            bodyData.append('uniqueId', uniqueId); // Replace 'key1' and 'value1' with your actual key and value

            fetch("{{ route('stream.get.message') }}", {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Required for Laravel to recognize the request as AJAX
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'), // CSRF token
                        body: bodyData
                    }
                })
                .then(response =>  response.json()) 
                .then(data => {
                    console.log('js 24 fetchData:  ' + data);

                    const container = document.getElementById('resultsContainer');
                    container.innerHTML = ''; // Clear previous results

                    data.forEach(res => {
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


        Echo.channel(`chat.{{ $uniqueId }}`)
            .listen('MessageSent', (e) => {


                // Remove the QR code element
                const qrCodeDiv = document.getElementById('qrcode');
                if (qrCodeDiv) {
                    qrCodeDiv.remove();
                }

                // Append the message to the chat container
                const chatContainer = document.getElementById('chatContainer');
                const messageElement = document.createElement('p');
                messageElement.className = 'text-center border p-3 border my-2 mb-5';
                messageElement.innerText = '';
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
                    .then(response => response.json())
                    .then(data => {
                        console.log('js88 :' + data);
                        fetchData(e.uniqueId)
                        let intervalId = setInterval(fetchData(e.uniqueId), 2000);
                        console.log('js91 intervalId :' + intervalId);
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






            }).error((error) => {
                console.error('Could not subscribe to the channel:', error);
            });
    </script>
@endsection
