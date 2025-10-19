@extends('layouts.admin.master')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Audit Logs</h1>
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-2">
                <input type="text" name="user" class="form-control" placeholder="User ID" value="{{ request('user') }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="log_name" class="form-control" placeholder="Log Name" value="{{ request('log_name') }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="description" class="form-control" placeholder="Action" value="{{ request('description') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-block">Filter</button>
            </div>
        </div>
    </form>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Date/Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Model</th>
                            <th>Record ID</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    @if($log->causer)
                                        {{ $log->causer->name }} (ID: {{ $log->causer_id }})
                                    @else
                                        System
                                    @endif
                                </td>
                                <td>{{ $log->description }}</td>
                                <td>{{ class_basename($log->subject_type) }}</td>
                                <td>{{ $log->subject_id }}</td>
                                <td>
                                    @if($log->properties && ($log->properties['attributes'] ?? null))
                                        <details>
                                            <summary>View</summary>
                                            <pre class="mb-0">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </details>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No logs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 