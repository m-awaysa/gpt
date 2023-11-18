@extends('layouts.app')
@section('content')


<div class="d-flex flex-column m-auto align-content-center justify-content-center">
    <form action="{{ route('gpt') }}" method="POST" class="m-auto w-100">
        @csrf
        <div class="d-flex row col-12 w-100" style="height:auto; min-height:500px">
            <textarea class="col-6" type="text" name="question" style="min-height: 100%; width:50%"
                placeholder="Ask a normal question">{{ old('question') }}</textarea>
            <p class="col-6 bg-success" style="min-height: 100%">
                {{ $answer ?? '' }}
            </p>
        </div>
        <div>
            <button class="submit">Ask chat gpt a question</button>
        </div>
    </form>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <form action="{{ route('ask-gpt') }}" method="POST" class="m-auto w-100">
        @csrf
        <div class="d-flex row col-12 w-100" style="height:auto; min-height:500px">
            <textarea class="col-6" name="context" placeholder="Enter the context here..."
                style="min-height: 100%; width:50%">{{ old('context') }}</textarea>
            <textarea class="col-6" name="question_for_context" placeholder="Ask a question about the context..."
                style="min-height: 100%; width:50%">{{ old('question_for_context') }}</textarea>
        </div>
        <div class="col-12">
            <p class="bg-success" style="min-height: 300px">

                {{ $answer_on_context ?? '' }}
            </p>
        </div>
        <div>
            <button type="submit" class="submit">Ask ChatGPT</button>
        </div>
    </form>

</div>
@endsection