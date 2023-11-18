@extends('layouts.app')
@section('content')
<div class="container mt-4 d-flec justify-content-center align-content-center align-middle"
    style="height: 100vh; width:100%">
    <div class="card" style="width: 18rem;">
        <img src="{{asset('img/'.$product->image)}}" class="card-img-top" alt="...">
        <div class="card-body">
            <p class="card-text">{{ $product->name }}</p>
        </div>
    </div>
</div>
@endsection