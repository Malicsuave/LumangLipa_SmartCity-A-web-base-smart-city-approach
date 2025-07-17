@extends('layouts.admin.master')

@section('content')
<div class="container-fluid mt-4">
    <h4>Edit Barangay Officials</h4>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('admin.officials.update-single') }}" method="POST" class="card card-body shadow-sm">
        @csrf
        <div class="form-group">
            <label for="captain_name">Barangay Captain</label>
            <input type="text" name="captain_name" id="captain_name" class="form-control @error('captain_name') is-invalid @enderror" value="{{ old('captain_name', $officials->captain_name) }}">
            @error('captain_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="mt-1 text-muted"><strong>Current:</strong> {{ $officials->captain_name }}</div>
        </div>
        @for($i = 1; $i <= 7; $i++)
        <div class="form-group">
            <label for="councilor{{ $i }}_name">Councilor {{ $i }}</label>
            <input type="text" name="councilor{{ $i }}_name" id="councilor{{ $i }}_name" class="form-control @error('councilor'.$i.'_name') is-invalid @enderror" value="{{ old('councilor'.$i.'_name', $officials->{'councilor'.$i.'_name'}) }}">
            @error('councilor'.$i.'_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="mt-1 text-muted"><strong>Current:</strong> {{ $officials->{'councilor'.$i.'_name'} }}</div>
            @php $committee = $officials->{'councilor'.$i.'_committee'} ?? null; @endphp
            <div class="mt-1 text-muted"><strong>Committee:</strong> {{ $committee ?: '—' }}</div>
        </div>
        @endfor
        <div class="form-group">
            <label for="sk_chairperson_name">SK Chairperson</label>
            <input type="text" name="sk_chairperson_name" id="sk_chairperson_name" class="form-control @error('sk_chairperson_name') is-invalid @enderror" value="{{ old('sk_chairperson_name', $officials->sk_chairperson_name) }}">
            @error('sk_chairperson_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="mt-1 text-muted"><strong>Current:</strong> {{ $officials->sk_chairperson_name }}</div>
            @php $sk_committee = $officials->sk_chairperson_committee ?? null; @endphp
            <div class="mt-1 text-muted"><strong>Committee:</strong> {{ $sk_committee ?: '—' }}</div>
        </div>
        <div class="form-group">
            <label for="secretary_name">Barangay Secretary</label>
            <input type="text" name="secretary_name" id="secretary_name" class="form-control @error('secretary_name') is-invalid @enderror" value="{{ old('secretary_name', $officials->secretary_name) }}">
            @error('secretary_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="mt-1 text-muted"><strong>Current:</strong> {{ $officials->secretary_name }}</div>
        </div>
        <div class="form-group">
            <label for="treasurer_name">Barangay Treasurer</label>
            <input type="text" name="treasurer_name" id="treasurer_name" class="form-control @error('treasurer_name') is-invalid @enderror" value="{{ old('treasurer_name', $officials->treasurer_name) }}">
            @error('treasurer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="mt-1 text-muted"><strong>Current:</strong> {{ $officials->treasurer_name }}</div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection 