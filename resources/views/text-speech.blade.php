@extends('layouts.app')
@section('content')
<form action="{{ route('text-to-speech') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="input_text">Enter text to convert to speech:</label>
    <textarea name="input_text" id="input_text" required></textarea>

    <label for="voice">Choose a voice:</label>
    <select name="voice" id="voice">
        <option value="alloy">Alloy</option>
        <option value="shimmer">Shimmer</option>
        <option value="nova">Nova</option>
        <option value="onyx">Onyx</option>
        <option value="fable">Fable</option>
        <option value="echo">Echo</option>
        // Add other voice options here
    </select>

    <button type="submit">Convert to Speech</button>
</form>
@endsection