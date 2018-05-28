@extends('layouts.app')

@section('content')
    <div class="input-group" id="tracking-link">
        <input type="text" class="form-control" value="{{ $link }}" placeholder="Enter tracking link" aria-label="Enter tracking link" aria-describedby="basic-addon2">
        <div class="input-group-append">
            <button class="btn btn-primary" type="button">Go!</button>
        </div>
    </div>
@endsection