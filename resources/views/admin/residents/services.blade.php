@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">Services</li>
@endsection

@section('page-title', 'Resident Services')
@section('page-subtitle', 'Conduct services for ' . $resident->full_name)

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        @include('admin.residents.partials.resident-info', ['resident' => $resident])
    </div>
    <div class="col-md-8">
        @if(!request('service'))
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fe fe-clipboard fe-16 mr-2"></i>Choose a Service</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach([
                            'Barangay Clearance',
                            'Certificate of Residency',
                            'Certificate of Indigency',
                            'Business Permit',
                            'Medical Consultation',
                            'Complaint'
                        ] as $service)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 service-card" style="cursor:pointer;" onclick="window.location='?service={{ urlencode($service) }}'">
                                <div class="card-body text-center">
                                    <i class="fe fe-clipboard fe-32 mb-2"></i>
                                    <h6 class="mb-1">{{ $service }}</h6>
                                    <p class="text-muted small">Request or process {{ $service }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            @if(request('service') == 'Barangay Clearance')
                @include('public.request.barangayclearance-form', ['resident' => $resident, 'admin' => true])
            @elseif(request('service') == 'Certificate of Residency')
                @include('public.request.residency-form', ['resident' => $resident, 'admin' => true])
            @elseif(request('service') == 'Certificate of Indigency')
                @include('public.request.indigency-form', ['resident' => $resident, 'admin' => true])
            @elseif(request('service') == 'Business Permit')
                @include('public.request.businesspermit-form', ['resident' => $resident, 'admin' => true])
            @elseif(request('service') == 'Medical Consultation')
                @include('public.request.healthservice-form', ['resident' => $resident, 'admin' => true])
            @elseif(request('service') == 'Complaint')
                @include('public.request.complaint-form', ['resident' => $resident, 'admin' => true])
            @endif
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fe fe-list fe-16 mr-2"></i>Service Request Tracking</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Service</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th>Reference</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $requests = collect();
                                $requests = $requests->concat(
                                    \App\Models\DocumentRequest::where('barangay_id', $resident->barangay_id)->orderByDesc('requested_at')->get()
                                );
                                $requests = $requests->concat(
                                    \App\Models\HealthServiceRequest::where('barangay_id', $resident->barangay_id)->orderByDesc('requested_at')->get()
                                );
                                $requests = $requests->concat(
                                    \App\Models\Complaint::where('barangay_id', $resident->barangay_id)->orderByDesc('filed_at')->get()
                                );
                                $requests = $requests->sortByDesc(function($item) {
                                    return $item->requested_at ?? $item->filed_at ?? $item->created_at;
                                });
                            @endphp
                            @forelse($requests as $request)
                                @php
                                    $isLastTwo = $loop->remaining < 2;
                                @endphp
                                <tr>
                                    <td>{{ $request->requested_at ? $request->requested_at->format('Y-m-d') : ($request->filed_at ? $request->filed_at->format('Y-m-d') : ($request->created_at ? $request->created_at->format('Y-m-d') : '-')) }}</td>
                                    <td>{{ $request->document_type ?? $request->service_type ?? $request->formatted_complaint_type ?? 'N/A' }}</td>
                                    <td>{{ $request->purpose ?? $request->description ?? '-' }}</td>
                                    <td>{!! $request->status_badge ?? ucfirst($request->status ?? '-') !!}</td>
                                    <td>
                                        @if($request->reference_number)
                                            {{ $request->reference_number }}
                                        @else
                                            {{ 'SRV-' . (isset($request->requested_at) && $request->requested_at ? $request->requested_at->format('Y') : (isset($request->filed_at) && $request->filed_at ? $request->filed_at->format('Y') : (isset($request->created_at) && $request->created_at ? $request->created_at->format('Y') : date('Y')))) . '-' . str_pad($request->id, 3, '0', STR_PAD_LEFT) }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $dropdownItems = [];
                                            $dropdownItems[] = [
                                                'label' => 'View',
                                                'icon' => 'fe fe-eye fe-16 text-primary',
                                                'class' => '',
                                                'href' => '#',
                                            ];
                                            if(($request->status ?? null) === 'approved' || ($request->status ?? null) === 'completed') {
                                                $dropdownItems[] = [
                                                    'label' => 'Print',
                                                    'icon' => 'fe fe-printer fe-16 text-info',
                                                    'class' => '',
                                                    'href' => '#',
                                                ];
                                            }
                                        @endphp
                                        @include('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLastTwo])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No service requests found for this resident.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table-responsive,
.card-body,
.collapse,
#filterSection {
    overflow: visible !important;
}
.dropdown-menu {
    z-index: 9999 !important;
}
    .avatar {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto;
    }
    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-letter {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: white;
        font-size: 24px;
        font-weight: bold;
    }
    .list-group-item-action:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    .card {
        border-radius: 8px;
        transition: box-shadow 0.2s ease;
    }
    .card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .border-left-primary {
        border-left: 4px solid #007bff !important;
    }
    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }
    .border-left-danger {
        border-left: 4px solid #dc3545 !important;
    }
</style>
@endpush

@push('scripts')
<script>
const serviceTypes = {
    Document: [
        'Barangay Clearance',
        'Certificate of Residency',
        'Certificate of Indigency',
        'Business Permit',
        'Other Official Documents'
    ],
    Health: [
        'Medical Consultation',
        'Blood Pressure Check',
        'Vaccination Programs',
        'Prenatal Checkup',
        'Health Certificates',
        'Medicine Distribution'
    ],
    Complaint: [
        'Noise Complaints',
        'Property Disputes',
        'Public Safety Issues',
        'Infrastructure Problems',
        'Other Community Concerns'
    ]
};

document.getElementById('serviceCategory').addEventListener('change', function() {
    const category = this.value;
    const typeSelect = document.getElementById('serviceType');
    typeSelect.innerHTML = '<option value="">Select Type</option>';
    if (serviceTypes[category]) {
        serviceTypes[category].forEach(type => {
            const opt = document.createElement('option');
            opt.value = type;
            opt.textContent = type;
            typeSelect.appendChild(opt);
        });
    }
});

function requestService(serviceType) {
    document.getElementById('serviceType').value = serviceType;
    $('#serviceRequestModal').modal('show');
}

document.getElementById('serviceRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    const serviceData = {
        resident_id: {{ $resident->id }},
        service_type: formData.get('service_type'),
        service_date: formData.get('service_date'),
        purpose: formData.get('purpose'),
        urgency: formData.get('urgency'),
        notes: formData.get('notes'),
        requested_by: formData.get('requested_by')
    };
    
    // Generate reference number
    const referenceNumber = 'SRV-' + Date.now().toString().substr(-8);
    
    // Simulate service processing (in real implementation, this would be an AJAX call)
    setTimeout(() => {
        // Hide request modal
        $('#serviceRequestModal').modal('hide');
        
        // Show success modal
        document.getElementById('successServiceType').textContent = serviceData.service_type;
        document.getElementById('referenceNumber').textContent = referenceNumber;
        $('#successModal').modal('show');
        
        // Reset form
        document.getElementById('serviceRequestForm').reset();
        document.getElementById('serviceDate').value = '{{ date("Y-m-d") }}';
        document.getElementById('requestedBy').value = '{{ auth()->user()->name }}';
    }, 1000);
});

function printReceipt() {
    // In a real implementation, this would generate and print a receipt
    const serviceType = document.getElementById('successServiceType').textContent;
    const referenceNumber = document.getElementById('referenceNumber').textContent;
    
    const printContent = `
        <div style="text-align: center; font-family: Arial, sans-serif; padding: 20px;">
            <h3>Barangay Lumanglipa</h3>
            <h4>Service Receipt</h4>
            <hr>
            <p><strong>Resident:</strong> {{ $resident->full_name }}</p>
            <p><strong>Barangay ID:</strong> {{ $resident->barangay_id }}</p>
            <p><strong>Service:</strong> ${serviceType}</p>
            <p><strong>Reference:</strong> ${referenceNumber}</p>
            <p><strong>Date:</strong> ${new Date().toLocaleDateString()}</p>
            <p><strong>Processed by:</strong> {{ auth()->user()->name }}</p>
            <hr>
            <p style="font-size: 12px;">Thank you for using our services!</p>
        </div>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.open();
    printWindow.document.write(`
        <html>
            <head>
                <title>Service Receipt</title>
                <style>
                    body { margin: 0; padding: 20px; }
                    @media print { body { margin: 0; } }
                </style>
            </head>
            <body>
                ${printContent}
                <script>
                    window.onload = function() {
                        window.print();
                        window.close();
                    }
                </script>
            </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
@endpush