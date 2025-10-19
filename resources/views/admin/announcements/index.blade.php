@extends('layouts.admin.master')

@section('title', 'Announcements Management')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h1 class="h3 mb-0 text-gray-800">Announcements Management</h1>
                    <p class="text-muted mb-0">Create and manage barangay announcements with slot management</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        New Announcement
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $metrics['total'] }}</h3>
                            <p>Total Announcements</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $metrics['active'] }}</h3>
                            <p>Active Announcements</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $metrics['expired'] }}</h3>
                            <p>Expired Announcements</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $metrics['total_registrations'] }}</h3>
                            <p>Total Registrations</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcements Table -->
            <div class="card shadow-lg border-0 mb-4 admin-card-shadow">
                <div class="card-header">
                    <strong class="card-title">
                        <i class="fas fa-bullhorn mr-2"></i>All Announcements 
                        <span class="badge badge-primary">
                            {{ is_object($announcements) && method_exists($announcements, 'total') ? $announcements->total() : (is_array($announcements) ? count($announcements) : 0) }}
                        </span>
                    </strong>
                </div>
                <div class="card-body">
                    @if((is_object($announcements) && $announcements->count() > 0) || (is_array($announcements) && count($announcements) > 0))
                        <div class="table-responsive">
                            <table id="announcementsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Slots</th>
                                        <th>Dates</th>
                                        <th>Created</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($announcements as $announcement)
                                        <tr>
                                            <td>
                                                <div>
                                                    <div class="font-weight-bold">{{ \Illuminate\Support\Str::limit($announcement->title, 50) }}</div>
                                                    <div class="small text-muted">{{ \Illuminate\Support\Str::limit($announcement->content, 60) }}</div>
                                                </div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $typeLabels = [
                                                        'general' => 'General',
                                                        'limited_slots' => 'Registration Required',
                                                        'event' => 'Event',
                                                        'service' => 'Service',
                                                        'program' => 'Program'
                                                    ];
                                                    $typeLabel = $typeLabels[$announcement->type] ?? ucfirst(str_replace('_', ' ', $announcement->type));
                                                @endphp
                                                <span class="badge badge-pill badge-{{ $announcement->type === 'limited_slots' ? 'warning' : 'info' }}">
                                                    {{ $typeLabel }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-{{ $announcement->is_active ? 'success' : 'secondary' }}">
                                                    {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                @if($announcement->status)
                                                    <br>
                                                    <small class="text-muted">{{ ucfirst($announcement->status) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($announcement->type === 'limited_slots')
                                                    <div class="font-weight-bold">{{ $announcement->current_slots }}/{{ $announcement->max_slots }}</div>
                                                    <div class="progress progress-sm mt-1">
                                                        <div class="progress-bar bg-{{ $announcement->progress_color }}" 
                                                             data-progress="{{ $announcement->progress_percentage }}">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ $announcement->progress_percentage }}% filled</small>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($announcement->start_date)
                                                    <div class="small">
                                                        <i class="fas fa-calendar-start text-success"></i> 
                                                        {{ $announcement->start_date->format('M j, Y') }}
                                                    </div>
                                                @endif
                                                @if($announcement->end_date)
                                                    <div class="small">
                                                        <i class="fas fa-calendar-times text-danger"></i> 
                                                        {{ $announcement->end_date->format('M j, Y') }}
                                                    </div>
                                                @endif
                                                @if(!$announcement->start_date && !$announcement->end_date)
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $announcement->created_at->format('M j, Y') }}<br>
                                                <small class="text-muted">{{ $announcement->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td class="text-center table-actions-col">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#" onclick="viewAnnouncementDetails('{{ $announcement->id }}')">
                                                            <i class="fas fa-eye mr-2"></i>View Details
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('admin.announcements.edit', $announcement) }}">
                                                            <i class="fas fa-edit mr-2"></i>Edit
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('admin.announcements.registrations', $announcement) }}">
                                                            <i class="fas fa-users mr-2"></i>Registrations ({{ $announcement->current_slots }})
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('admin.announcements.toggle', $announcement) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-{{ $announcement->is_active ? 'eye-slash' : 'eye' }} mr-2"></i>
                                                                {{ $announcement->is_active ? 'Deactivate' : 'Activate' }}
                                                            </button>
                                                        </form>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger" href="#" 
                                                           onclick="confirmDelete('{{ $announcement->id }}', '{{ $announcement->title }}')">
                                                            <i class="fas fa-trash mr-2"></i>Delete
                                                        </a>
                                                    </div>
                                                </div>
                                                
                                                <!-- Delete Form -->
                                                <form id="delete-form-{{ $announcement->id }}" 
                                                      action="{{ route('admin.announcements.destroy', $announcement) }}" 
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-bullhorn text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h6 class="text-muted">No announcements found</h6>
                            <p class="text-sm text-muted">
                                @if(request()->hasAny(['search', 'type', 'status']))
                                    Try adjusting your search criteria or 
                                    <a href="{{ route('admin.announcements.index') }}">clear filters</a>.
                                @else
                                    Start by creating your first announcement.
                                @endif
                            </p>
                            @if(!request()->hasAny(['search', 'type', 'status']))
                                <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Create First Announcement
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Announcement Details Modal -->
<div class="modal fade" id="viewAnnouncementModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Announcement Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <div class="section-title mb-2" style="font-weight: 600; color: #2A7BC4; font-size: 1rem;">Announcement Information</div>
                        <div class="info-row mb-2">
                            <span class="info-label" style="font-weight: 600; color: #666;">Title:</span>
                            <span class="info-value ml-2" id="modal-title"></span>
                        </div>
                        <div class="info-row mb-2">
                            <span class="info-label" style="font-weight: 600; color: #666;">Type:</span>
                            <span class="info-value ml-2" id="modal-type"></span>
                        </div>
                        <div class="info-row mb-2">
                            <span class="info-label" style="font-weight: 600; color: #666;">Status:</span>
                            <span class="info-value ml-2" id="modal-status"></span>
                        </div>
                        <div class="info-row mb-2">
                            <span class="info-label" style="font-weight: 600; color: #666;">Created:</span>
                            <span class="info-value ml-2" id="modal-created"></span>
                        </div>
                        <div class="info-row mb-3" id="modal-dates-row">
                            <span class="info-label" style="font-weight: 600; color: #666;">Duration:</span>
                            <span class="info-value ml-2" id="modal-dates"></span>
                        </div>
                        <div class="section-title mb-2" style="font-weight: 600; color: #2A7BC4; font-size: 1rem;">Content</div>
                        <div id="modal-content" class="content-box p-3" style="background: #f8f9fa; border-radius: 8px; line-height: 1.6;"></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="section-title mb-2" style="font-weight: 600; color: #2A7BC4; font-size: 1rem;">Registration Info</div>
                        <div id="modal-slots-info" class="mb-3">
                            <!-- Slots info will be populated here -->
                        </div>
                        
                        <div class="section-title mb-2" style="font-weight: 600; color: #2A7BC4; font-size: 1rem;">Image</div>
                        <div id="modal-image-container" class="text-center">
                            <span class="text-muted">No image uploaded.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="#" id="modal-edit-btn" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Announcement
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, title) {
    if (confirm(`Are you sure you want to delete the announcement "${title}"? This action cannot be undone.`)) {
        document.getElementById(`delete-form-${id}`).submit();
    }
}

function viewAnnouncementDetails(announcementId) {
    // Reset modal content
    $('#modal-title').text('Loading...');
    $('#modal-type').text('Loading...');
    $('#modal-status').text('Loading...');
    $('#modal-created').text('Loading...');
    $('#modal-dates').text('Loading...');
    $('#modal-content').text('Loading...');
    $('#modal-slots-info').html('<span class="text-muted">Loading...</span>');
    $('#modal-image-container').html('<span class="text-muted">Loading...</span>');
    
    // Open modal first
    $('#viewAnnouncementModal').modal('show');
    
    // Fetch data via AJAX
    $.ajax({
        url: '/admin/announcements/' + announcementId,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            // Populate modal with data
            $('#modal-title').text(response.title);
            $('#modal-type').html('<span class="badge badge-' + (response.type === 'limited_slots' ? 'warning' : 'info') + '">' + response.type_display + '</span>');
            $('#modal-status').html(getAnnouncementStatusBadge(response.status, response.is_active));
            $('#modal-created').text(formatDate(response.created_at));
            $('#modal-content').html(response.content.replace(/\n/g, '<br>'));
            
            // Handle dates
            if (response.start_date || response.end_date) {
                let datesText = '';
                if (response.start_date) {
                    datesText += 'From ' + formatDate(response.start_date);
                }
                if (response.end_date) {
                    datesText += (response.start_date ? ' to ' : 'Until ') + formatDate(response.end_date);
                }
                $('#modal-dates').text(datesText);
                $('#modal-dates-row').show();
            } else {
                $('#modal-dates-row').hide();
            }
            
            // Handle slots info
            if (response.type === 'limited_slots') {
                const slotsHtml = `
                    <div class="info-row mb-2">
                        <span class="info-label" style="font-weight: 600; color: #666;">Max Slots:</span>
                        <span class="info-value ml-2">${response.max_slots}</span>
                    </div>
                    <div class="info-row mb-2">
                        <span class="info-label" style="font-weight: 600; color: #666;">Registered:</span>
                        <span class="info-value ml-2">${response.current_slots}</span>
                    </div>
                    <div class="info-row mb-2">
                        <span class="info-label" style="font-weight: 600; color: #666;">Available:</span>
                        <span class="info-value ml-2">${response.max_slots - response.current_slots}</span>
                    </div>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-${response.progress_color}" style="width: ${response.progress_percentage}%"></div>
                    </div>
                    <small class="text-muted">${response.progress_percentage}% filled</small>
                `;
                $('#modal-slots-info').html(slotsHtml);
            } else {
                $('#modal-slots-info').html('<span class="text-muted">No registration required</span>');
            }
            
            // Handle image
            if (response.image) {
                const imageUrl = '/storage/' + response.image;
                $('#modal-image-container').html(
                    '<img src="' + imageUrl + '" alt="Announcement Image" class="img-fluid" style="max-width: 100%; border-radius: 8px; cursor: pointer;" onclick="enlargeImage(\'' + imageUrl + '\')">'
                );
            } else {
                $('#modal-image-container').html('<div class="text-center p-3" style="background: #f8f9fa; border-radius: 8px;"><i class="fas fa-image text-muted mb-2" style="font-size: 2rem;"></i><br><span class="text-muted">No image</span></div>');
            }
            
            // Update edit button link
            $('#modal-edit-btn').attr('href', '/admin/announcements/' + response.id + '/edit');
        },
        error: function(xhr, status, error) {
            console.error('Error fetching announcement details:', error);
            $('#modal-title').text('Error loading data');
            $('#modal-type').text('Error');
            $('#modal-status').text('Error');
            $('#modal-created').text('Error');
            $('#modal-content').text('Error loading content');
            $('#modal-slots-info').html('<span class="text-danger">Error loading slots info</span>');
            $('#modal-image-container').html('<span class="text-danger">Error loading image</span>');
        }
    });
}

function getAnnouncementStatusBadge(status, isActive) {
    let badgeClass = 'secondary';
    let statusText = status;
    
    if (isActive) {
        switch(status) {
            case 'active':
                badgeClass = 'success';
                statusText = 'Active';
                break;
            case 'full':
                badgeClass = 'warning';
                statusText = 'Full';
                break;
            case 'expired':
                badgeClass = 'secondary';
                statusText = 'Expired';
                break;
            default:
                badgeClass = 'success';
                statusText = 'Active';
        }
    } else {
        badgeClass = 'secondary';
        statusText = 'Inactive';
    }
    
    return '<span class="badge badge-' + badgeClass + '">' + statusText + '</span>';
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function enlargeImage(imageUrl) {
    // Create a simple image enlargement modal
    const enlargeModal = `
        <div class="modal fade" id="enlargeImageModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Announcement Image</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="${imageUrl}" class="img-fluid" style="max-width: 100%;">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing enlarge modal if any
    $('#enlargeImageModal').remove();
    
    // Add new modal to body and show it
    $('body').append(enlargeModal);
    $('#enlargeImageModal').modal('show');
    
    // Clean up when modal is hidden
    $('#enlargeImageModal').on('hidden.bs.modal', function () {
        $(this).remove();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Set progress bar widths
    document.querySelectorAll('.progress-bar[data-progress]').forEach(function(bar) {
        var progress = bar.getAttribute('data-progress');
        bar.style.width = progress + '%';
    });
    
    if (window.DataTableHelpers) {
        if (document.getElementById('announcementsTable')) {
            DataTableHelpers.initDataTable('#announcementsTable', {
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                order: [[ 5, "desc" ]], // Sort by created date
                columnDefs: [
                    { "orderable": false, "targets": -1 } // Disable sorting on actions column
                ]
            });
        }
    }
});
</script>

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
.progress-sm {
    height: 0.4rem;
}
.admin-card-shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
.info-row {
    display: flex;
    align-items: flex-start;
}
.info-label {
    min-width: 100px;
    flex-shrink: 0;
}
.info-value {
    flex: 1;
    word-break: break-word;
}
.section-title {
    border-bottom: 2px solid #2A7BC4;
    padding-bottom: 4px;
    margin-bottom: 12px;
}
</style>
@endsection