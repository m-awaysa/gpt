@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-center mt-5">
        <form id='form' onsubmit="sendMessage(event)">
            <input type="text" name="message">
            <button id="submitButton" type="submit">Send</button>
        </form>
    </div>

    <script type="module">
        Echo.channel(`chat.{{ $uniqueId }}` + '1')
            .listen('MessageSent', (e) => {
                const submitButton = document.getElementById('submitButton');
                submitButton.disabled = false;
            }).error((error) => {
                console.error('Could not subscribe to the channel:', error);
            });
    </script>

    <script>
        function sendMessage(e) {
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = true;
            e.preventDefault(); // Prevent the default form submission
            const form = document.getElementById('form');
            const formData = new FormData(form);
            const uniqueId = 'some-unique-string'; // Replace with your actual uniqueId
            let url = "{{ route('message', ['uniqueId' => ':uniqueId']) }}";
            url = url.replace(':uniqueId', '{{ $uniqueId }}');

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    // Optionally clear the message input after successful send
                    form.reset();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    </script>
@endsection
