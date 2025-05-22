@extends('layouts.admin.master')

@section('content')
<div class="row">
    <!-- Welcome Widget -->
    <div class="col-md-12 mb-4">
        <div class="card shadow welcome-widget">
            <div class="card-body">
                <h5 class="card-title">Welcome to your Admin Dashboard</h5>
                <p class="card-text">You are logged in as <strong>{{ Auth::user()->role->name }}</strong>.</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-md-3 mb-4">
        <div class="card shadow health-metric-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-primary">
                            <i class="fe fe-users text-white"></i>
                        </span>
                    </div>
                    <div class="col">
                        <p class="small text-muted mb-0">Residents</p>
                        <span class="h3">245</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card shadow health-metric-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-success">
                            <i class="fe fe-file-text text-white"></i>
                        </span>
                    </div>
                    <div class="col">
                        <p class="small text-muted mb-0">Documents</p>
                        <span class="h3">12</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card shadow health-metric-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-warning">
                            <i class="fe fe-alert-triangle text-white"></i>
                        </span>
                    </div>
                    <div class="col">
                        <p class="small text-muted mb-0">Complaints</p>
                        <span class="h3">3</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card shadow health-metric-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-danger">
                            <i class="fe fe-heart text-white"></i>
                        </span>
                    </div>
                    <div class="col">
                        <p class="small text-muted mb-0">Health Cases</p>
                        <span class="h3">8</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Role-Based Quick Access -->
    @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary']))
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <strong>Document Requests</strong>
            </div>
            <div class="card-body">
                <p>Manage document requests from residents</p>
                <a href="{{ route('admin.documents') }}" class="btn btn-primary">Go to Document Requests</a>
            </div>
        </div>
    </div>
    @endif

    @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Health Worker']))
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <strong>Health Services</strong>
            </div>
            <div class="card-body">
                <p>Manage health services and records</p>
                <a href="{{ route('admin.health') }}" class="btn btn-primary">Go to Health Services</a>
            </div>
        </div>
    </div>
    @endif

    @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Complaint Manager']))
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <strong>Complaints</strong>
            </div>
            <div class="card-body">
                <p>View and manage resident complaints</p>
                <a href="{{ route('admin.complaints') }}" class="btn btn-primary">Go to Complaints</a>
            </div>
        </div>
    </div>
    @endif

    @if(Auth::user()->role->name === 'Barangay Captain')
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <strong>Analytics</strong>
            </div>
            <div class="card-body">
                <p>View barangay data analytics</p>
                <a href="{{ route('admin.analytics') }}" class="btn btn-primary">Go to Analytics</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
