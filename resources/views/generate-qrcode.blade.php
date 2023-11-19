@extends('layouts.app')
@section('content')
    <div>{!! $qrCode !!}</div>
    <form action="{{ route('stream') }}" method="POST">
        <input type="text" name="qr">
    </form>
@endsection
