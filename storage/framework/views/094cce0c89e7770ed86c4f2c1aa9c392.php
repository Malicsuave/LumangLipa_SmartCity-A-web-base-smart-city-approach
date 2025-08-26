
<form action="<?php echo e(route('admin.residents.id.pending')); ?>" method="GET" id="issuanceFilterForm">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by name, Barangay ID, phone number..." value="<?php echo e(request('search')); ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary border-0" type="submit" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                        <i class="fe fe-search fe-16"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary border-0 filter-btn-hover" data-toggle="collapse" data-target="#issuanceFilterSection" aria-expanded="false" title="Filter Options" style="border-left: 1px solid #dee2e6;">
                        <i class="fe fe-filter fe-16"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?php if(request()->hasAny(['search', 'type', 'gender', 'age_group', 'has_photo'])): ?>
                <a href="<?php echo e(route('admin.residents.id.pending')); ?>?tab=issuance" class="btn btn-outline-secondary">
                    <i class="fe fe-x fe-16 mr-1"></i>Clear All Filters
                </a>
            <?php endif; ?>
        </div>
    </div>
    <!-- Collapsible Filter Section -->
    <div class="collapse <?php echo e(request()->hasAny(['type', 'gender', 'age_group', 'has_photo']) ? 'show' : ''); ?>" id="issuanceFilterSection">
        <div class="card border-left-primary mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-primary">
                    <i class="fe fe-filter fe-16 mr-2"></i>Filter Options
                    <small class="text-muted ml-2">Filter residents by various criteria</small>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Resident Type</label>
                        <select name="type" class="form-control form-control-sm">
                            <option value="">All Types</option>
                            <option value="Non-migrant" <?php echo e(request('type') == 'Non-migrant' ? 'selected' : ''); ?>>Non-migrant</option>
                            <option value="Migrant" <?php echo e(request('type') == 'Migrant' ? 'selected' : ''); ?>>Migrant</option>
                            <option value="Transient" <?php echo e(request('type') == 'Transient' ? 'selected' : ''); ?>>Transient</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control form-control-sm">
                            <option value="">All Genders</option>
                            <option value="Male" <?php echo e(request('gender') == 'Male' ? 'selected' : ''); ?>>Male</option>
                            <option value="Female" <?php echo e(request('gender') == 'Female' ? 'selected' : ''); ?>>Female</option>
                            <option value="Non-binary" <?php echo e(request('gender') == 'Non-binary' ? 'selected' : ''); ?>>Non-binary</option>
                            <option value="Transgender" <?php echo e(request('gender') == 'Transgender' ? 'selected' : ''); ?>>Transgender</option>
                            <option value="Other" <?php echo e(request('gender') == 'Other' ? 'selected' : ''); ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Age Group</label>
                        <select name="age_group" class="form-control form-control-sm">
                            <option value="">All Ages</option>
                            <option value="0-17" <?php echo e(request('age_group') == '0-17' ? 'selected' : ''); ?>>0-17 (Minor)</option>
                            <option value="18-59" <?php echo e(request('age_group') == '18-59' ? 'selected' : ''); ?>>18-59 (Adult)</option>
                            <option value="60+" <?php echo e(request('age_group') == '60+' ? 'selected' : ''); ?>>60+ (Senior)</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Photo Status</label>
                        <select name="has_photo" class="form-control form-control-sm">
                            <option value="">All</option>
                            <option value="yes" <?php echo e(request('has_photo') == 'yes' ? 'selected' : ''); ?>>With Photo</option>
                            <option value="no" <?php echo e(request('has_photo') == 'no' ? 'selected' : ''); ?>>Without Photo</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-filter fe-16 mr-1"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Active Filters Display -->
    <?php if(request()->hasAny(['search', 'type', 'gender', 'age_group', 'has_photo'])): ?>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Active filters:</small>
                    <span class="badge badge-info ml-2"><?php echo e($pendingIssuance->total()); ?> results found</span>
                </div>
                <small class="text-muted">Click on any filter badge to remove it</small>
            </div>
            <div class="mt-2">
                <?php if(request('search')): ?>
                    <a href="<?php echo e(request()->fullUrlWithQuery(['search' => null, 'tab' => 'issuance'])); ?>" class="badge badge-dark mr-1 text-decoration-none">
                        Search: <?php echo e(request('search')); ?> <i class="fe fe-x"></i>
                    </a>
                <?php endif; ?>
                <?php if(request('type')): ?>
                    <a href="<?php echo e(request()->fullUrlWithQuery(['type' => null, 'tab' => 'issuance'])); ?>" class="badge badge-primary mr-1 text-decoration-none">
                        Type: <?php echo e(request('type')); ?> <i class="fe fe-x"></i>
                    </a>
                <?php endif; ?>
                <?php if(request('gender')): ?>
                    <a href="<?php echo e(request()->fullUrlWithQuery(['gender' => null, 'tab' => 'issuance'])); ?>" class="badge badge-success mr-1 text-decoration-none">
                        Gender: <?php echo e(request('gender')); ?> <i class="fe fe-x"></i>
                    </a>
                <?php endif; ?>
                <?php if(request('age_group')): ?>
                    <a href="<?php echo e(request()->fullUrlWithQuery(['age_group' => null, 'tab' => 'issuance'])); ?>" class="badge badge-info mr-1 text-decoration-none">
                        Age Group: <?php echo e(request('age_group')); ?> <i class="fe fe-x"></i>
                    </a>
                <?php endif; ?>
                <?php if(request('has_photo')): ?>
                    <a href="<?php echo e(request()->fullUrlWithQuery(['has_photo' => null, 'tab' => 'issuance'])); ?>" class="badge badge-warning mr-1 text-decoration-none">
                        Photo: <?php echo e(request('has_photo') == 'yes' ? 'With Photo' : 'Without Photo'); ?> <i class="fe fe-x"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</form> <?php /**PATH /var/www/html/lumanglipa/resources/views/admin/residents/pending-ids-filter-main-content.blade.php ENDPATH**/ ?>