@extends('layouts.app')
@section('content')
    <form action="{{ route('ask-gpt-embidding') }}" method="POST" class="m-auto w-100">
        @csrf
        <textarea name="question" placeholder="Ask your question here..." style="min-height: 100px; width:100%">{{ old('question') }}</textarea>
        <p class="col-12 bg-success" style="min-height: 100px; width:100%">
        <div>{{ $spotted ?? '' }}</div>
        <br>
        <br>
        @if (isset($array) && $array != null)
            @foreach ($array as $arr)
                <div>

                    {{ $arr }}
                </div>
            @endforeach
        @endif

        </p>
        <button type="submit" class="submit">Get Recipe</button>
    </form>
    @endsection
