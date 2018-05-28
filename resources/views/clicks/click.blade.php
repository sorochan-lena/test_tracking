<div class="table-container table-responsive">
    <table class="table">
        <tbody>
        <tr>
            <th width="30%">ID</th> <td>{{ $click->id }}</td>
        </tr>
        <tr>
            <th>User Agent</th> <td>{{ $click->ua }}</td>
        </tr>
        <tr>
            <th>IP</th> <td>{{ $click->ip }}</td>
        </tr>
        <tr>
            <th>Referer</th> <td>{{ $click->ref }}</td>
        </tr>
        <tr>
            <th>Param #1</th> <td>{{ $click->param1 }}</td>
        </tr>
        <tr>
            <th>Param #2</th> <td>{{ $click->param2 }}</td>
        </tr>
        <tr>
            <th>Errors</th> <td>{{ $click->error }}</td>
        </tr>
        <tr>
            <th>Bad domain</th> <td>{{ $click->badDomain }}</td>
        </tr>
        </tbody>
    </table>
</div>