@extends('layouts.public.master')

@section('title', 'Announcements')

@push('styles')
<style>
.announcement-card {
    transition: all 0.3s ease;
}

.announcement-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(42, 123, 196, 0.15) !important;
}

.announcement-card:hover img {
    transform: scale(1.05);
}

.category-header {
    transition: all 0.3s ease;
}

.category-header:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.progress {
    background-color: rgba(0,0,0,0.1);
}

.badge {
    backdrop-filter: blur(10px);
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.announcement-card {
    animation: slideInUp 0.6s ease-out;
}

.category-section {
    animation: slideInUp 0.4s ease-out;
}

.announcement-card:nth-child(2) { animation-delay: 0.1s; }
.announcement-card:nth-child(3) { animation-delay: 0.2s; }
.announcement-card:nth-child(4) { animation-delay: 0.3s; }

/* Standard Bootstrap Pagination Styles */
.pagination {
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 0.25rem;
    justify-content: center;
    margin: 1rem 0;
}

.pagination .page-item {
    list-style: none;
}

.pagination .page-item:first-child .page-link {
    margin-left: 0;
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
}

.pagination .page-item:last-child .page-link {
    border-top-right-radius: 0.25rem;
    border-bottom-right-radius: 0.25rem;
}

.pagination .page-item.active .page-link {
    z-index: 3;
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: white;
    border-color: #dee2e6;
}

.pagination .page-link:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: 0;
}

.pagination .page-link:hover {
    background-color: #e9ecef;
    border-color: #adb5bd;
    color: #0056b3;
    text-decoration: none;
}
</style>
@endpush

@section('content')
<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-4">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-3" style="color:#2A7BC4; font-size: 2.5rem;">Barangay Announcements</h1>
            <p class="text-muted" style="font-size:1.1rem;">Stay updated with the latest barangay news, events, and programs</p>
            <div style="width: 80px; height: 4px; background: linear-gradient(90deg, #2A7BC4, #FFD700); margin: 20px auto; border-radius: 2px;"></div>
        </div>

        @if($announcements->count() > 0)
            @php
                // Group announcements by type
                $groupedAnnouncements = $announcements->groupBy('type');
                
                // Ensure each group is sorted by created_at (oldest first)
                foreach($groupedAnnouncements as $type => $typeAnnouncements) {
                    $groupedAnnouncements[$type] = $typeAnnouncements->sortBy('created_at');
                }
                
                // Define type configurations
                $typeConfigs = [
                    'event' => [
                        'title' => 'Events & Activities',
                        'description' => 'Upcoming barangay events and community activities',
                            'icon' => 'fas fa-calendar-check',
                            'gradient' => 'linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%)',
                            'accent_color' => '#2A7BC4'
                    ],
                    'program' => [
                        'title' => 'Programs & Services',
                            'description' => 'Government programs and barangay services',
                            'icon' => 'fas fa-hands-helping',
                            'gradient' => 'linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%)',
                            'accent_color' => '#2A7BC4'
                    ],
                    'health_related' => [
                        'title' => 'Health Related',
                            'description' => 'Health services and appointments available',
                            'icon' => 'fas fa-heartbeat',
                            'gradient' => 'linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%)',
                            'accent_color' => '#2A7BC4'
                    ],
                    'general' => [
                        'title' => 'General Announcements',
                        'description' => 'Important notices and updates',
                        'icon' => 'fas fa-bullhorn',
                        'gradient' => 'linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%)',
                        'accent_color' => '#2A7BC4'
                    ],
                    'service' => [
                        'title' => 'Services Available',
                            'description' => 'Available barangay services and facilities',
                            'icon' => 'fas fa-cogs',
                            'gradient' => 'linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%)',
                            'accent_color' => '#2A7BC4'
                    ]
                ];
            @endphp

            @foreach($typeConfigs as $type => $config)
                @if($groupedAnnouncements->has($type))
                    @php
                        $typeAnnouncements = $groupedAnnouncements[$type];
                        $totalItems = $typeAnnouncements->count();
                    @endphp

                    <!-- Category Section -->
                    <div class="mb-5 category-section" data-type="{{ $type }}">
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0 category-header" style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); border-radius: 20px; padding: 20px;">
                                    <div class="d-flex align-items-center text-white">
                                        <div class="me-3">
                                            <i class="{{ $config['icon'] }}" style="font-size: 2.5rem; opacity: 0.9; color: white !important;"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-1 fw-bold" style="color: white !important;">{{ $config['title'] }}</h3>
                                            <p class="mb-0" style="opacity: 0.9; font-size: 1rem; color: white !important;">{{ $config['description'] }}</p>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="badge bg-white fw-bold px-3 py-2" style="font-size: 0.9rem; color: #2A7BC4 !important;">
                                                {{ $totalItems }} 
                                                {{ $totalItems > 1 ? 'items' : 'item' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- All Announcements for this category (hidden by JS initially) -->
                        <div class="row g-4 announcements-container" id="announcements-{{ $type }}">
                            @foreach($typeAnnouncements as $index => $announcement)
                                <!-- Debug: Index {{ $index }}, Created: {{ $announcement->created_at->format('M j, Y H:i') }} -->
                                <div class="col-lg-6 col-xl-4 announcement-item" data-type="{{ $type }}" data-index="{{ $index }}" style="display: none;">
                                    <div class="card h-100 announcement-card" style="border: 2px solid rgba(42, 123, 196, 0.12); border-radius: 20px; overflow: hidden; box-shadow: 0 8px 30px rgba(42, 123, 196, 0.08);">
                                        <!-- Card Header with Type Badge -->
                                        <div class="position-relative" style="height: 200px; overflow: hidden;">
                                            @if($announcement->image)
                                                <img src="{{ asset('storage/' . $announcement->image) }}" 
                                                     alt="{{ $announcement->title }}" 
                                                     class="card-img-top w-100 h-100" 
                                                     style="object-fit: cover; transition: transform 0.3s ease;">
                                            @else
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center" 
                                                     style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%);">
                                                    <i class="{{ $config['icon'] }} text-white" style="font-size: 2.5rem; opacity: 0.8;"></i>
                                                </div>
                                            @endif
                                            
                                            <!-- Type Badge -->
                                            <div class="position-absolute top-0 end-0 m-3">
                                                <span class="badge text-white px-3 py-2" style="background: #2A7BC4; border-radius: 25px; font-size: 0.75rem; font-weight: 600;">
                                                    @php
                                                        $typeLabels = [
                                                            'general' => 'General',
                                                            'health_related' => 'Health',
                                                            'event' => 'Event',
                                                            'service' => 'Service',
                                                            'program' => 'Program'
                                                        ];
                                                        echo $typeLabels[$announcement->type] ?? ucfirst($announcement->type);
                                                    @endphp
                                                </span>
                                            </div>

                                            <!-- Status Badge for limited slots -->
                                            @if($announcement->type === 'health_related' && $announcement->max_slots)
                                                <div class="position-absolute bottom-0 start-0 m-3">
                                                    @php
                                                        $slotsLeft = $announcement->max_slots - $announcement->current_slots;
                                                        $percentage = ($announcement->current_slots / $announcement->max_slots) * 100;
                                                    @endphp
                                                    <span class="badge {{ $slotsLeft > 0 ? 'bg-success' : 'bg-danger' }} px-3 py-2" style="border-radius: 25px; font-size: 0.75rem;">
                                                        @if($slotsLeft > 0)
                                                            {{ $slotsLeft }} slots left
                                                        @else
                                                            Full
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="card-body d-flex flex-column p-4">
                                            <h5 class="card-title fw-bold mb-3" style="color: #1e293b; line-height: 1.3;">
                                                {{ $announcement->title }}
                                            </h5>
                                            
                            <p class="card-text mb-3" style="color: #6c757d; font-size: 0.9rem; line-height: 1.5;">
                                {{ \Illuminate\Support\Str::limit(strip_tags($announcement->content), 100) }}
                            </p>
                            
                            <!-- Date and Status Info -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small style="color: #6c757d;">
                                        <i class="fas fa-calendar-alt me-1" style="color: #2A7BC4;"></i>
                                        @if($announcement->date)
                                            {{ $announcement->date->format('M d, Y') }}
                                        @else
                                            {{ $announcement->created_at->format('M d, Y') }}
                                        @endif
                                    </small>
                                    
                                    @if($announcement->type === 'health_related' && $announcement->max_slots)
                                        <small style="color: #6c757d;">
                                            <i class="fas fa-users me-1" style="color: #2A7BC4;"></i>
                                            {{ $announcement->current_slots }}/{{ $announcement->max_slots }}
                                        </small>
                                    @endif
                                </div>
                                
                                <!-- Progress Bar for limited slots -->
                                @if($announcement->type === 'health_related' && $announcement->max_slots)
                                    <div class="mt-2">
                                        <div class="progress" style="height: 4px; border-radius: 2px; background: #f8f9fa;">
                                            @php
                                                $progressWidth = min(100, ($announcement->current_slots / $announcement->max_slots) * 100);
                                            @endphp
                                            <div class="progress-bar" 
                                                 data-width="{{ $progressWidth }}"
                                                 style="background-color: #2A7BC4;">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                            </div>
                                            
                                            <div class="mt-auto">
                                                <a href="{{ route('announcements.show', $announcement) }}" 
                                                   class="btn w-100 fw-bold announcement-btn" 
                                                   style="background: #2A7BC4; color: white !important; border: none; border-radius: 12px; padding: 12px; transition: all 0.3s ease; text-decoration: none;"
                                                   onmouseover="this.style.background='#1e5f8c'; this.style.transform='translateY(-2px)'"
                                                   onmouseout="this.style.background='#2A7BC4'; this.style.transform='translateY(0)'">
                                                    <i class="fas fa-eye me-2" style="color: white !important;"></i>View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- JavaScript-based Pagination for this type -->
                        <div class="d-flex justify-content-center mt-4 pagination-container" id="pagination-{{ $type }}" style="display: none;">
                            <nav aria-label="Pagination for {{ $config['title'] }}">
                                <ul class="pagination">
                                    <!-- Pagination will be generated by JavaScript -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 8px 25px rgba(0,0,0,0.1);">
                        <i class="fas fa-bullhorn" style="font-size: 3rem; color: #6c757d;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-3" style="color: #1e293b;">No Announcements Available</h4>
                <p class="text-muted" style="font-size: 1.1rem;">
                    There are currently no active announcements. Please check back later for updates.
                </p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration
    const itemsPerPage = 3;
    
    // Get all category sections
    const categorySections = document.querySelectorAll('.category-section[data-type]');
    
    // Initialize pagination for each type
    categorySections.forEach(section => {
        const type = section.getAttribute('data-type');
        initializePagination(type);
    });
    
    function initializePagination(type) {
        const container = document.getElementById(`announcements-${type}`);
        const paginationContainer = document.getElementById(`pagination-${type}`);
        const items = container.querySelectorAll(`.announcement-item[data-type="${type}"]`);
        
        if (items.length === 0) return;
        
        // Debug: Check the order of items
        console.log(`${type} announcements order:`, Array.from(items).map(item => ({
            index: item.getAttribute('data-index'),
            title: item.querySelector('.card-title, h5')?.textContent?.trim()
        })));
        
        const totalPages = Math.ceil(items.length / itemsPerPage);
        let currentPage = 1;
        
        function showPage(page) {
            // Hide all items first
            items.forEach(item => {
                item.style.display = 'none';
            });
            
            // Calculate range for current page
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            
            // Show items for current page with animation
            for (let i = startIndex; i < endIndex && i < items.length; i++) {
                items[i].style.display = 'block';
                items[i].style.opacity = '0';
                items[i].style.transform = 'translateY(20px)';
                
                // Animate in
                setTimeout(() => {
                    items[i].style.transition = 'all 0.3s ease';
                    items[i].style.opacity = '1';
                    items[i].style.transform = 'translateY(0)';
                }, i * 50); // Stagger animation
            }
            
            currentPage = page;
            updatePaginationControls();
        }
        
        function updatePaginationControls() {
            const pagination = paginationContainer.querySelector('.pagination');
            pagination.innerHTML = '';
            
            // Previous button
            if (currentPage > 1) {
                const prevLi = createPageButton('prev', currentPage - 1, '<span aria-hidden="true">&laquo;</span>');
                prevLi.querySelector('.page-link').setAttribute('aria-label', 'Previous');
                pagination.appendChild(prevLi);
            } else {
                const prevLi = document.createElement('li');
                prevLi.className = 'page-item disabled';
                const prevA = document.createElement('a');
                prevA.className = 'page-link';
                prevA.innerHTML = '<span aria-hidden="true">&laquo;</span>';
                prevA.setAttribute('aria-label', 'Previous');
                prevA.style.cssText = `
                    color: #6c757d;
                    pointer-events: none;
                    background-color: white;
                    border: 1px solid #dee2e6;
                    padding: 0.5rem 0.75rem;
                    margin-left: -1px;
                    line-height: 1.25;
                    text-decoration: none;
                `;
                prevLi.appendChild(prevA);
                pagination.appendChild(prevLi);
            }
            
            // Always show page 1 for quick access, then show current page area
            if (totalPages <= 3) {
                // If 3 or fewer pages, show all
                for (let i = 1; i <= totalPages; i++) {
                    pagination.appendChild(createPageButton('number', i, i, i === currentPage));
                }
            } else {
                // Always show page 1
                pagination.appendChild(createPageButton('number', 1, 1, currentPage === 1));
                
                if (currentPage > 3) {
                    // Add ellipsis if current page is far from page 1
                    const ellipsisLi = document.createElement('li');
                    ellipsisLi.className = 'page-item disabled';
                    const ellipsisA = document.createElement('a');
                    ellipsisA.className = 'page-link';
                    ellipsisA.innerHTML = '...';
                    ellipsisA.style.cssText = `
                        color: #6c757d;
                        pointer-events: none;
                        background-color: white;
                        border: 1px solid #dee2e6;
                        padding: 0.5rem 0.75rem;
                        margin-left: -1px;
                        line-height: 1.25;
                        text-decoration: none;
                    `;
                    ellipsisLi.appendChild(ellipsisA);
                    pagination.appendChild(ellipsisLi);
                }
                
                // Show current page and adjacent pages (but not page 1 again)
                let startPage = Math.max(2, currentPage - 1);
                let endPage = Math.min(totalPages, currentPage + 1);
                
                // Ensure we show at least 2 more pages after page 1
                if (currentPage <= 3) {
                    startPage = 2;
                    endPage = Math.min(totalPages, 3);
                }
                
                for (let i = startPage; i <= endPage; i++) {
                    pagination.appendChild(createPageButton('number', i, i, i === currentPage));
                }
            }
            
            // Next button
            if (currentPage < totalPages) {
                const nextLi = createPageButton('next', currentPage + 1, '<span aria-hidden="true">&raquo;</span>');
                nextLi.querySelector('.page-link').setAttribute('aria-label', 'Next');
                pagination.appendChild(nextLi);
            } else {
                const nextLi = document.createElement('li');
                nextLi.className = 'page-item disabled';
                const nextA = document.createElement('a');
                nextA.className = 'page-link';
                nextA.innerHTML = '<span aria-hidden="true">&raquo;</span>';
                nextA.setAttribute('aria-label', 'Next');
                nextA.style.cssText = `
                    color: #6c757d;
                    pointer-events: none;
                    background-color: white;
                    border: 1px solid #dee2e6;
                    padding: 0.5rem 0.75rem;
                    margin-left: -1px;
                    line-height: 1.25;
                    text-decoration: none;
                `;
                nextLi.appendChild(nextA);
                pagination.appendChild(nextLi);
            }
        }
        
        function createPageButton(buttonType, page, content, isActive = false) {
            const li = document.createElement('li');
            li.className = `page-item ${isActive ? 'active' : ''}`;
            
            const a = document.createElement('a');
            a.className = 'page-link';
            a.innerHTML = content;
            a.href = '#';
            
            // Standard Bootstrap pagination styling
            if (isActive) {
                a.style.cssText = `
                    background-color: #007bff;
                    border-color: #007bff;
                    color: white;
                    padding: 0.5rem 0.75rem;
                    margin-left: -1px;
                    line-height: 1.25;
                    text-decoration: none;
                    border: 1px solid #dee2e6;
                `;
            } else {
                a.style.cssText = `
                    background-color: white;
                    border-color: #dee2e6;
                    color: #007bff;
                    padding: 0.5rem 0.75rem;
                    margin-left: -1px;
                    line-height: 1.25;
                    text-decoration: none;
                    border: 1px solid #dee2e6;
                `;
                
                a.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#e9ecef';
                    this.style.borderColor = '#adb5bd';
                });
                
                a.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'white';
                    this.style.borderColor = '#dee2e6';
                });
            }
            
            a.addEventListener('click', function(e) {
                e.preventDefault();
                showPage(page);
            });
            
            li.appendChild(a);
            return li;
        }
        
        // Show pagination only if there are multiple pages
        if (totalPages > 1) {
            paginationContainer.style.display = 'flex';
        }
        
        // Initialize first page
        showPage(1);
    }
    
    // Set progress bar widths using data attributes
    document.querySelectorAll('.progress-bar[data-width]').forEach(function(bar) {
        bar.style.width = bar.getAttribute('data-width') + '%';
    });
});
</script>
@endsection
