@extends('layouts.app')
@section('content')
    <div id='qrcode'>{!! $qrCode !!}</div>
<div> {{$uniqueId}}</div>

<div>chat</div>

    <script type="module">

        Echo.channel(`chat.{{$uniqueId}}`)
            .listen('MessageSent', (e) => {
            //in js remove the #qrcode and start adding to the chat ever e.message apears
            }).error((error) => {
                console.error('Could not subscribe to the channel:', error);
            });
    </script>
@endsection
