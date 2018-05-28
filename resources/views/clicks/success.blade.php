@extends('layouts.app')

@section('content')
    <h1 class="text-success text-center mb-3">Success!</h1>
    <div class="row d-flex justify-content-center">
        <div class="col-8">
            @include('clicks.click', ['click' => $click])
        </div>
    </div>
@endsection