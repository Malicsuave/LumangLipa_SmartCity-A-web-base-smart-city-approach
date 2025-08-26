@extends('layouts.admin')

@section('title', 'Manage Officials')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Barangay Officials</h3>
                    <a href="{{ route('admin.officials.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Official
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Committee</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($officials as $official)
                                    <tr>
                                        <td>
                                            @if($official->profile_pic)
                                                <img src="{{ asset('storage/officials/' . $official->profile_pic) }}" 
                                                     alt="{{ $official->name }}" 
                                                     class="rounded-circle" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white" 
                                                     style="width: 50px; height: 50px; font-weight: bold;">
                                                    {{ $official->initials }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $official->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $official->position === 'Captain' ? 'primary' : 'secondary' }}">
                                                {{ $official->position }}
                                            </span>
                                        </td>
                                        <td>{{ $official->committee ?: '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.officials.edit', $official) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.officials.destroy', $official) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this official?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No officials found. Add some officials to get started.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
