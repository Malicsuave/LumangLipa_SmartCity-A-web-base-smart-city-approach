@extends('layouts.admin.master')

@section('title', 'Bulk Photo Upload & ID Issuance')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h3 page-title">Bulk Photo Upload & ID Issuance</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.residents.id.pending') }}" class="btn btn-primary mr-2">
                        <i class="fe fe-arrow-left mr-2"></i> Back to ID Management
                    </a>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fe fe-check-circle fe-16 mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fe fe-alert-circle fe-16 mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Upload Multiple Photos</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Upload photos for multiple residents at once. Photos should be named with the resident's Barangay ID or a consistent naming pattern.</p>
                    
                    <form action="{{ route('admin.residents.id.bulk-upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="photos">Select Multiple Photos</label>
                            <input type="file" class="form-control-file" id="photos" name="photos[]" multiple accept="image/*" required>
                            <small class="form-text text-muted">
                                Accepted formats: JPG, PNG, GIF. Max size: 5MB per image.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="naming_pattern">Photo Naming Pattern</label>
                            <select class="form-control" id="naming_pattern" name="naming_pattern">
                                <option value="barangay_id">Barangay ID (e.g., BRG-2023-00001.jpg)</option>
                                <option value="full_name">Full Name (e.g., JuanDelaCruz.jpg)</option>
                                <option value="last_first">Last Name, First Name (e.g., DelaCruz_Juan.jpg)</option>
                            </select>
                            <small class="form-text text-muted">
                                Select how your photos are named so the system can match them to residents.
                            </small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-upload mr-2"></i>Upload Photos
                        </button>
                    </form>
                </div>
            </div>

            <!-- New Bulk Signature Upload Section -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Upload Multiple Signatures</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Upload signatures for multiple residents at once. Signature files should be named with the resident's Barangay ID or a consistent naming pattern.</p>
                    
                    <form action="{{ route('admin.residents.id.bulk-signature-upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="signatures">Select Multiple Signatures</label>
                            <input type="file" class="form-control-file" id="signatures" name="signatures[]" multiple accept="image/*" required>
                            <small class="form-text text-muted">
                                Accepted formats: JPG, PNG. Max size: 2MB per image.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="signature_naming_pattern">Signature Naming Pattern</label>
                            <select class="form-control" id="signature_naming_pattern" name="signature_naming_pattern">
                                <option value="barangay_id">Barangay ID (e.g., BRG-2023-00001.jpg)</option>
                                <option value="full_name">Full Name (e.g., JuanDelaCruz.jpg)</option>
                                <option value="last_first">Last Name, First Name (e.g., DelaCruz_Juan.jpg)</option>
                            </select>
                            <small class="form-text text-muted">
                                Select how your signature files are named so the system can match them to residents.
                            </small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-upload mr-2"></i>Upload Signatures
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Bulk ID Issuance</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Issue IDs for multiple residents who already have their photos uploaded.</p>

                    <form action="{{ route('admin.residents.id.bulk-issue') }}" method="POST" id="bulkIssueForm">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="bulkIssueTable">
                                <thead>
                                    <tr>
                                        <th width="50"><input type="checkbox" id="selectAll"></th>
                                        <th>Barangay ID</th>
                                        <th>Name</th>
                                        <th>Photo</th>
                                        <th>Signature</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($readyForIssuance->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No residents ready for ID issuance.</td>
                                        </tr>
                                    @endif
                                    @foreach($readyForIssuance as $resident)
                                        <tr>
                                            <td>
                                                @if($resident->photo && $resident->signature && $resident->id_status !== 'issued')
                                                    <input type="checkbox" name="resident_ids[]" value="{{ $resident->id }}" class="resident-checkbox">
                                                @endif
                                            </td>
                                            <td><strong>{{ $resident->barangay_id }}</strong></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm mr-2">
                                                        @if($resident->photo)
                                                            <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle">
                                                        @else
                                                            <div class="avatar-letter rounded-circle bg-warning">{{ substr($resident->first_name, 0, 1) }}</div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <strong>{{ $resident->last_name }}, {{ $resident->first_name }}</strong>
                                                        @if($resident->middle_name)
                                                            {{ substr($resident->middle_name, 0, 1) }}.
                                                        @endif
                                                        {{ $resident->suffix }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($resident->photo)
                                                    <span class="badge badge-success">✓</span>
                                                @else
                                                    <span class="badge badge-danger">✗</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($resident->signature)
                                                    <span class="badge badge-success">✓</span>
                                                @else
                                                    <span class="badge badge-danger">✗</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($resident->id_status === 'issued')
                                                    <span class="badge badge-success">Issued</span>
                                                @elseif($resident->photo && $resident->signature)
                                                    <span class="badge badge-primary">Ready</span>
                                                @else
                                                    <span class="badge badge-warning">Incomplete</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary" id="bulkIssueBtn">
                                <i class="fe fe-credit-card mr-2"></i>Issue IDs for Selected Residents
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        var bulkIssueTable = $('#bulkIssueTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[1, 'asc']],
            columnDefs: [
                { orderable: false, targets: [0] }
            ],
            drawCallback: function() {
                // Re-attach event listeners after DataTable redraws
                attachCheckboxHandlers();
            }
        });
        
        function attachCheckboxHandlers() {
            // Select all checkboxes
            $('#selectAll').off('click').on('click', function() {
                $('.resident-checkbox').prop('checked', $(this).prop('checked'));
                updateBatchButton();
            });
            
            // Update batch button state when individual checkboxes change
            $('.resident-checkbox').off('click').on('click', function() {
                updateBatchButton();
                
                // Update select all checkbox
                var totalCheckboxes = $('.resident-checkbox').length;
                var checkedCheckboxes = $('.resident-checkbox:checked').length;
                $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0);
            });
        }
        
        // Initial attachment of handlers
        attachCheckboxHandlers();
        
        // Update the bulk issue button state
        function updateBatchButton() {
            var checkedCount = $('.resident-checkbox:checked').length;
            // Remove the line that disables the button
            
            if (checkedCount > 0) {
                $('#bulkIssueBtn').html('<i class="fe fe-credit-card mr-2"></i> Issue IDs for ' + checkedCount + ' Selected Resident' + (checkedCount > 1 ? 's' : ''));
            } else {
                $('#bulkIssueBtn').html('<i class="fe fe-credit-card mr-2"></i> Issue IDs for Selected Residents');
            }
        }

        // Fancy image preview on upload
        $('#photos').on('change', function() {
            var files = $(this)[0].files;
            var previewContainer = $('#photoPreviewContainer');
            
            if (!previewContainer.length) {
                previewContainer = $('<div id="photoPreviewContainer" class="d-flex flex-wrap mt-3"></div>');
                $(this).after(previewContainer);
            } else {
                previewContainer.empty();
            }
            
            if (files.length > 0) {
                previewContainer.before('<p class="mt-3 mb-2"><strong>Photo Previews:</strong></p>');
                
                for (var i = 0; i < Math.min(files.length, 10); i++) {
                    var reader = new FileReader();
                    reader.onload = (function(file, index) {
                        return function(e) {
                            var previewItem = $('<div class="mr-2 mb-2" style="width: 100px;"></div>');
                            previewItem.append('<img src="' + e.target.result + '" class="img-thumbnail" style="height: 100px; object-fit: cover;" alt="Preview">');
                            previewItem.append('<small class="d-block text-truncate">' + file.name + '</small>');
                            previewContainer.append(previewItem);
                        };
                    })(files[i], i);
                    
                    reader.readAsDataURL(files[i]);
                }
                
                if (files.length > 10) {
                    previewContainer.append('<div class="ml-2 align-self-center"><em>...and ' + (files.length - 10) + ' more files</em></div>');
                }
            }
        });
    });
</script>
@endsection

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
</style>