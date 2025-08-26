<?php $__env->startSection('breadcrumbs'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('admin.residents.index')); ?>">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">ID Card Management</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-title', 'ID Card Management'); ?>
<?php $__env->startSection('page-subtitle', 'Manage resident ID card issuance and renewals'); ?>

<?php $__env->startSection('content'); ?>
<!-- Metrics Row -->
<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card document-metric-card shadow h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-primary">
                            <i class="fe fe-credit-card text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-muted mb-0">Pending Issuance</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0"><?php echo e($pendingIssuance->total()); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card document-metric-card shadow h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-warning">
                            <i class="fe fe-refresh-cw text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-muted mb-0">Pending Renewal</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0"><?php echo e($pendingRenewal->total()); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card document-metric-card shadow h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-danger">
                            <i class="fe fe-alert-triangle text-white mb-0"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-muted mb-0">Expiring Soon</p>
                    </div>
                    <div class="col-auto">
                        <span class="h3 mb-0"><?php echo e($expiringSoon->total()); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tab Navigation -->
<?php
    $tab = request('tab', 'issuance');
                                    ?>
<ul class="nav nav-tabs mb-4" id="idTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link <?php echo e($tab === 'issuance' ? 'active' : ''); ?>" href="?tab=issuance">Pending Issuance</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e($tab === 'renewal' ? 'active' : ''); ?>" href="?tab=renewal">Pending Renewal</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e($tab === 'expiring' ? 'active' : ''); ?>" href="?tab=expiring">Expiring Soon</a>
                                    </li>
                            </ul>
<div class="tab-content" id="idTabContent">
    <div class="tab-pane fade <?php echo e($tab === 'issuance' ? 'show active' : ''); ?>" id="issuance" role="tabpanel">
        <!-- Pending Issuance Card -->
        <div class="card shadow mb-4">
            <?php echo $__env->make('admin.residents.partials.pending-ids-issuance', compact('pendingIssuance'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
    <div class="tab-pane fade <?php echo e($tab === 'renewal' ? 'show active' : ''); ?>" id="renewal" role="tabpanel">
        <!-- Pending Renewal Card -->
        <div class="card shadow mb-4">
            <?php echo $__env->make('admin.residents.partials.pending-ids-renewal', compact('pendingRenewal'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
    <div class="tab-pane fade <?php echo e($tab === 'expiring' ? 'show active' : ''); ?>" id="expiring" role="tabpanel">
        <!-- Expiring Soon Card -->
        <div class="card shadow mb-4">
            <?php echo $__env->make('admin.residents.partials.pending-ids-expiring', compact('expiringSoon'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    console.log('Auto-scroll script loaded!');
    function clearAllFilters() {
        document.getElementById('filterForm').reset();
        window.location.href = "<?php echo e(route('admin.residents.id.pending')); ?>";
    }

    $(document).ready(function() {
        // Store scroll position before sorting
        function storeScrollPosition() {
            sessionStorage.setItem('tableScrollPosition', window.pageYOffset);
        }
        
        // Restore scroll position after page load
        function restoreScrollPosition() {
            const scrollPosition = sessionStorage.getItem('tableScrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition));
                sessionStorage.removeItem('tableScrollPosition');
            }
        }
        
        // Add click handlers to all sortable headers
        $('.table thead a').on('click', function(e) {
            storeScrollPosition();
            // Allow the link to navigate normally
        });
        
        // Restore scroll position on page load (if coming from a sort)
        restoreScrollPosition();
        
        // Alternative: Scroll to table when sorting to maintain context
        if (window.location.search.includes('sort=')) {
            setTimeout(function() {
                const tableElement = document.querySelector('.table-responsive');
                if (tableElement) {
                    tableElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        }

        // --- Auto-scroll to correct table after filtering/searching ---
        const urlParams = new URLSearchParams(window.location.search);
        // Pending Issuance
        if ((urlParams.get('search') || urlParams.get('type') || urlParams.get('gender') || urlParams.get('age_group') || urlParams.get('has_photo')) && (!urlParams.get('tab') || urlParams.get('tab') === 'issuance')) {
            setTimeout(function() {
                const issuanceTab = document.getElementById('issuance');
                if (issuanceTab) {
                    console.log('Issuance tab display:', getComputedStyle(issuanceTab).display, 'Visibility:', getComputedStyle(issuanceTab).visibility, 'Classes:', issuanceTab.className);
                }
                const issuanceTable = document.getElementById('pendingIssuanceTable');
                if (issuanceTable) {
                    console.log('Scrolling to Pending Issuance Table');
                    issuanceTable.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    const noResults = document.getElementById('pendingIssuanceNoResults');
                    if (noResults) {
                        console.log('Scrolling to Pending Issuance No Results');
                        noResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        console.log('Pending Issuance Table and No Results not found');
                    }
                }
            }, 400);
        }
        // Pending Renewal
        if (urlParams.get('tab') === 'renewal' && (urlParams.get('renewal_search') || urlParams.get('renewal_type') || urlParams.get('renewal_gender') || urlParams.get('renewal_age_group'))) {
            setTimeout(function() {
                const renewalTab = document.getElementById('renewal');
                if (renewalTab) {
                    console.log('Renewal tab display:', getComputedStyle(renewalTab).display, 'Visibility:', getComputedStyle(renewalTab).visibility, 'Classes:', renewalTab.className);
                }
                const renewalTable = document.getElementById('pendingRenewalTable');
                if (renewalTable) {
                    console.log('Scrolling to Pending Renewal Table');
                    renewalTable.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    const noResults = document.getElementById('pendingRenewalNoResults');
                    if (noResults) {
                        console.log('Scrolling to Pending Renewal No Results');
                        noResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        console.log('Pending Renewal Table and No Results not found');
                    }
                }
            }, 400);
        }
        // Expiring Soon
        if (urlParams.get('tab') === 'expiring' && (urlParams.get('expiring_search') || urlParams.get('expiring_type') || urlParams.get('expiring_gender') || urlParams.get('expiring_age_group'))) {
            setTimeout(function() {
                const expiringTab = document.getElementById('expiring');
                if (expiringTab) {
                    console.log('Expiring tab display:', getComputedStyle(expiringTab).display, 'Visibility:', getComputedStyle(expiringTab).visibility, 'Classes:', expiringTab.className);
                }
                const expiringTable = document.getElementById('expiringSoonTable');
                if (expiringTable) {
                    console.log('Scrolling to Expiring Soon Table');
                    expiringTable.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    const noResults = document.getElementById('expiringSoonNoResults');
                    if (noResults) {
                        console.log('Scrolling to Expiring Soon No Results');
                        noResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        console.log('Expiring Soon Table and No Results not found');
                    }
                }
            }, 400);
        }

        // Initialize DataTables with custom options
        var pendingIssuanceTable = $('#pendingIssuanceTable').DataTable({
            "paging": false, // Disable DataTables pagination as we're using Laravel's
            "searching": false, // Disable default search as we already have our own
            "ordering": false, // Disable DataTables sorting since we have custom sorting
            "info": false, // Hide "Showing X to Y of Z entries" text
            "responsive": true,
            "dom": '<"row"<"col-sm-12"tr>>', // Only show the table without DataTables' controls
            "language": {
                "emptyTable": "No pending IDs found"
            }
        });
        
        $('#pendingRenewalTable, #expiringSoonTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": false, // Disable DataTables sorting since we have custom sorting
            "info": false,
            "responsive": true,
            "dom": '<"row"<"col-sm-12"tr>>', 
            "language": {
                "emptyTable": "No records found"
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
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
/* Filter Button Hover Effects - Match Archived Residents Page */
.filter-btn-hover {
    transition: transform 0.2s ease-in-out;
}

.filter-btn-hover:hover {
    transform: scale(1.1);
    background-color: transparent !important;
    border-color: #6c757d !important;
    color: #6c757d !important;
}

.filter-btn-hover:focus {
    background-color: transparent !important;
    border-color: #6c757d !important;
    color: #6c757d !important;
    box-shadow: none !important;
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/lumanglipa/resources/views/admin/residents/pending-ids.blade.php ENDPATH**/ ?>