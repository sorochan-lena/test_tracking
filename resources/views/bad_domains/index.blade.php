@extends('layouts.app')

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-6">
            <form action="{{ url('bad-domains') }}" method="POST">
                <div class="input-group input-group-sm mb-5 mt-3">
                    @csrf
                    <input type="text" name="bad_domain" value="{{ old('bad_domain') }}" class="form-control {{ $errors->has('bad_domain') ? 'is-invalid' : '' }}" placeholder="Add domain" aria-label="Add domain" aria-describedby="basic-addon2" required>
                    <div class="input-group-append">
                        <input type="submit" class="btn btn-primary">
                    </div>
                    @if ($errors->has('bad_domain'))
                        <div class="invalid-feedback">
                            {{ $errors->first('bad_domain') }}
                        </div>
                    @endif
                </div>
            </form>
            <div class="table-container table-responsive">
                <table class="table" id="bad-domains">
                    <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="90%">Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($domains->count())
                        @foreach ($domains as $domain)
                            <tr>
                                <td>{{ $domain->id }}</td>
                                <td>{{ $domain->name }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection