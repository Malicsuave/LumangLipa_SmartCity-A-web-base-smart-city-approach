@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">Services</li>
@endsection

@section('page-title', 'Resident Services')
@section('page-subtitle', 'Conduct services for ' . $resident->full_name)

@section('content')
<div class="row">
    <!-- Resident Information Card -->
    <div class="col-md-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fe fe-user fe-16 mr-2"></i>Resident Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar avatar-lg mb-3">
                        @if($resident->photo)
                            <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle">
                        @else
                            <div class="avatar-letter rounded-circle bg-primary">{{ substr($resident->first_name, 0, 1) }}</div>
                        @endif
                    </div>
                    <h5 class="mb-1">{{ $resident->full_name }}</h5>
                    <p class="text-muted mb-0">{{ $resident->barangay_id }}</p>
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-right">
                            <span class="d-block text-muted">Age</span>
                            <strong>{{ $resident->age }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <span class="d-block text-muted">Gender</span>
                        <strong>{{ $resident->sex }}</strong>
                    </div>
                </div>
                
                <hr>
                
                <div class="small">
                    <div class="mb-2">
                        <strong>Contact:</strong> {{ $resident->contact_number ?: 'N/A' }}
                    </div>
                    <div class="mb-2">
                        <strong>Address:</strong> {{ $resident->address }}
                    </div>
                    <div class="mb-2">
                        <strong>Civil Status:</strong> {{ $resident->civil_status }}
                    </div>
                    <div class="mb-2">
                        <strong>Type:</strong> 
                        <span class="badge badge-info">{{ $resident->type_of_resident }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fe fe-clipboard fe-16 mr-2"></i>Available Services</h5>
                    <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fe fe-arrow-left fe-16 mr-1"></i>Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fe fe-clipboard text-muted" style="font-size: 48px;"></i>
                    <h5 class="mt-3 text-muted">Services Section</h5>
                    <p class="text-muted">Services content will be added here.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Service Request Modal -->
<div class="modal fade" id="serviceRequestModal" tabindex="-1" role="dialog" aria-labelledby="serviceRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceRequestModalLabel">Request Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="serviceRequestForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serviceType">Service Type</label>
                                <input type="text" class="form-control" id="serviceType" name="service_type" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serviceDate">Service Date</label>
                                <input type="date" class="form-control" id="serviceDate" name="service_date" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purpose">Purpose</label>
                                <select class="form-control" id="purpose" name="purpose">
                                    <option value="">Select Purpose</option>
                                    <option value="Employment">Employment</option>
                                    <option value="Business">Business</option>
                                    <option value="School Requirement">School Requirement</option>
                                    <option value="Government Requirement">Government Requirement</option>
                                    <option value="Medical">Medical</option>
                                    <option value="Legal">Legal</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="urgency">Urgency Level</label>
                                <select class="form-control" id="urgency" name="urgency">
                                    <option value="Normal">Normal</option>
                                    <option value="Urgent">Urgent</option>
                                    <option value="Emergency">Emergency</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Additional Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional information or special requirements..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="requestedBy">Requested By</label>
                        <input type="text" class="form-control" id="requestedBy" name="requested_by" value="{{ auth()->user()->name }}" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fe fe-check fe-16 mr-1"></i>Process Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <i class="fe fe-check-circle fe-16 mr-2"></i>Service Processed Successfully
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fe fe-check-circle text-success" style="font-size: 48px;"></i>
                </div>
                <h5 id="successServiceType"></h5>
                <p class="text-muted">The service request has been processed for <strong>{{ $resident->full_name }}</strong></p>
                <div class="alert alert-info">
                    <strong>Reference Number:</strong> <span id="referenceNumber"></span>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                <button type="button" class="btn btn-outline-primary" onclick="printReceipt()">
                    <i class="fe fe-printer fe-16 mr-1"></i>Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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