@extends('layouts.app')
@section('content')

<div class="d-flex flex-column m-auto align-content-center justify-content-center" style="height: 100vh;width: 100%">
    <div>

        <form action="{{ route('speech-to-text') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="audio_file" required>
            @error('audio_file')
            <div class="text-danger">{{ $message }}</div>
            @enderror
            <button type="submit">Transcribe</button>
        </form>

        <br>
        -------------------------------------------------------------------------
        <br>
        <h1>Transcription Result</h1>
        @if (isset($transcription))
        <p>{{ $transcription }}</p>
        @else
        <p>No transcription available.</p>
        @endif
    </div>
</div>

@endsection