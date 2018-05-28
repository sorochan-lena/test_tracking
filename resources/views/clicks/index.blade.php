@extends('layouts.app')

@section('content')
    <div class="table-container table-responsive">
        <table class="table" id="clicks">
            <thead>
                <tr>
                    <th width="20%">ID</th>
                    <th width="20%">User Agent</th>
                    <th width="10%">IP</th>
                    <th width="10%">Referer</th>
                    <th width="10%">Param #1</th>
                    <th width="10%">Param #2</th>
                    <th width="5%">Errors</th>
                    <th width="10%">Bad domain</th>
                </tr>
            </thead>
            <tbody>
                @if ($clicks->count())
                    @foreach ($clicks as $click)
                        <tr>
                            <td>{{ $click->id }}</td>
                            <td>{{ $click->ua }}</td>
                            <td>{{ $click->ip }}</td>
                            <td>{{ $click->ref }}</td>
                            <td>{{ $click->param1 }}</td>
                            <td>{{ $click->param2 }}</td>
                            <td>{{ $click->error }}</td>
                            <td>{{ $click->badDomain ? $click->badDomain->name : '' }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection