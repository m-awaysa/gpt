@extends('layouts.app')
@section('content')
<form action="{{ route('generate-image') }}" method="POST">
    @csrf
    <label for="prompt">Describe the image you want to generate:</label>
    <input type="text" name="prompt" id="prompt" required>
    <button type="submit">Generate Image</button>
</form>

@if (isset($images) && $images != null)
@foreach ( $images as $image)
<div>
    <img src="{{ $image['url'] }}" alt="Generated Image">
</div>
@endforeach
@endif
@endsection