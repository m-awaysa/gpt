@extends('layouts.app')
@section('content')
<form action="{{ route('image-query') }}" method="POST">
    @csrf
    <label for="text_query">Your question about the image:</label>
    <input type="text" name="text_query" id="text_query" required>

    <label for="image_url">Image URL:</label>
    <input type="text" name="image_url" id="image_url" required>

    <button type="submit">Submit Query</button>
</form>

@if (isset($response))
<div>
    <p>OpenAI Response:</p>
    <p>{{ $response['choices'][0]['message']['content'] ?? 'No response found.' }}</p>
</div>

@endsection