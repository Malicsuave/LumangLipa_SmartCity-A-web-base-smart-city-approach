{{-- Lumanglipa Admin Sidebar Navigation --}}
<ul class="navbar-nav flex-fill w-100 mb-2">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="fe fe-home fe-16 mr-2"></i>
            <span>Dashboard</span>
        </a>
    </li>

    {{-- Residents Management --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.residents*') ? 'active' : '' }} collapsed" href="#residents" data-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.residents*') ? 'true' : 'false' }}" aria-controls="residents">
            <i class="fe fe-users fe-16 mr-2"></i>
            <span>Residents</span>
            <span class="fe fe-chevron-down fe-16 ml-auto"></span>
        </a>
        <div class="collapse {{ request()->routeIs('admin.residents*') ? 'show' : '' }}" id="residents">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.residents.index') }}" class="nav-link {{ request()->routeIs('admin.residents.index') ? 'active' : '' }}">
                        <i class="fe fe-list mr-2"></i> All Residents
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.residents.create') }}" class="nav-link {{ request()->routeIs('admin.residents.create') ? 'active' : '' }}">
                        <i class="fe fe-user-plus mr-2"></i> Add Resident
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.households.index') }}" class="nav-link {{ request()->routeIs('admin.households*') ? 'active' : '' }}">
                        <i class="fe fe-home mr-2"></i> Manage Households
                    </a>
                </li>
            </ul>
        </div>
    </li>

    {{-- Document Requests --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.document-requests*') ? 'active' : '' }} collapsed" href="#docRequests" data-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.document-requests*') ? 'true' : 'false' }}" aria-controls="docRequests">
            <i class="fe fe-file-text fe-16 mr-2"></i>
            <span>Document Requests</span>
            <span class="fe fe-chevron-down fe-16 ml-auto"></span>
        </a>
        <div class="collapse {{ request()->routeIs('admin.document-requests*') ? 'show' : '' }}" id="docRequests">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.document-requests') }}" class="nav-link {{ request()->routeIs('admin.document-requests') ? 'active' : '' }}">
                        <i class="fe fe-inbox mr-2"></i> Pending Requests
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.document-requests.approved') }}" class="nav-link {{ request()->routeIs('admin.document-requests.approved') ? 'active' : '' }}">
                        <i class="fe fe-check-circle mr-2"></i> Approved Requests
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.document-requests.settings') }}" class="nav-link {{ request()->routeIs('admin.document-requests.settings') ? 'active' : '' }}">
                        <i class="fe fe-settings mr-2"></i> Document Settings
                    </a>
                </li>
            </ul>
        </div>
    </li>

    {{-- Health Services --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.health*') ? 'active' : '' }} collapsed" href="#healthServices" data-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.health*') ? 'true' : 'false' }}" aria-controls="healthServices">
            <i class="fe fe-heart fe-16 mr-2"></i>
            <span>Health Services</span>
            <span class="fe fe-chevron-down fe-16 ml-auto"></span>
        </a>
        <div class="collapse {{ request()->routeIs('admin.health*') ? 'show' : '' }}" id="healthServices">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.health') }}" class="nav-link {{ request()->routeIs('admin.health') ? 'active' : '' }}">
                        <i class="fe fe-activity mr-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.health-services.index') }}" class="nav-link {{ request()->routeIs('admin.health-services.index') ? 'active' : '' }}">
                        <i class="fe fe-clipboard mr-2"></i> Service Requests
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.health-services.programs') }}" class="nav-link {{ request()->routeIs('admin.health-services.programs') ? 'active' : '' }}">
                        <i class="fe fe-calendar mr-2"></i> Programs & Events
                    </a>
                </li>
            </ul>
        </div>
    </li>

    {{-- Complaints Management --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.complaints*') ? 'active' : '' }} collapsed" href="#complaints" data-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.complaints*') ? 'true' : 'false' }}" aria-controls="complaints">
            <i class="fe fe-alert-circle fe-16 mr-2"></i>
            <span>Complaints</span>
            <span class="fe fe-chevron-down fe-16 ml-auto"></span>
        </a>
        <div class="collapse {{ request()->routeIs('admin.complaints*') ? 'show' : '' }}" id="complaints">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.complaints') }}" class="nav-link {{ request()->routeIs('admin.complaints') ? 'active' : '' }}">
                        <i class="fe fe-grid mr-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.complaint-management') }}" class="nav-link {{ request()->routeIs('admin.complaint-management') ? 'active' : '' }}">
                        <i class="fe fe-list mr-2"></i> All Complaints
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.complaint-management.hearings') }}" class="nav-link {{ request()->routeIs('admin.complaint-management.hearings') ? 'active' : '' }}">
                        <i class="fe fe-calendar mr-2"></i> Scheduled Hearings
                    </a>
                </li>
            </ul>
        </div>
    </li>

    {{-- Announcements --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}">
            <i class="fe fe-bell fe-16 mr-2"></i>
            <span>Announcements</span>
        </a>
    </li>

    {{-- Analytics --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.analytics*') ? 'active' : '' }}" href="{{ route('admin.analytics') }}">
            <i class="fe fe-bar-chart-2 fe-16 mr-2"></i>
            <span>Analytics</span>
        </a>
    </li>

    {{-- Admin divider --}}
    <li class="nav-item">
        <div class="nav-divider mt-2 mb-2"></div>
        <span class="text-muted nav-heading mt-2 mb-2">
            <span>Administration</span>
        </span>
    </li>

    {{-- Settings --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }} collapsed" href="#settings" data-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.settings*') ? 'true' : 'false' }}" aria-controls="settings">
            <i class="fe fe-settings fe-16 mr-2"></i>
            <span>Settings</span>
            <span class="fe fe-chevron-down fe-16 ml-auto"></span>
        </a>
        <div class="collapse {{ request()->routeIs('admin.settings*') ? 'show' : '' }}" id="settings">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.settings.general') }}" class="nav-link {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">
                        <i class="fe fe-sliders mr-2"></i> General Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.officials') }}" class="nav-link {{ request()->routeIs('admin.settings.officials') ? 'active' : '' }}">
                        <i class="fe fe-users mr-2"></i> Barangay Officials
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.system') }}" class="nav-link {{ request()->routeIs('admin.settings.system') ? 'active' : '' }}">
                        <i class="fe fe-cpu mr-2"></i> System
                    </a>
                </li>
            </ul>
        </div>
    </li>

    {{-- User Management --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }} collapsed" href="#users" data-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.users*') ? 'true' : 'false' }}" aria-controls="users">
            <i class="fe fe-shield fe-16 mr-2"></i>
            <span>User Management</span>
            <span class="fe fe-chevron-down fe-16 ml-auto"></span>
        </a>
        <div class="collapse {{ request()->routeIs('admin.users*') ? 'show' : '' }}" id="users">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                        <i class="fe fe-users mr-2"></i> All Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.create') }}" class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                        <i class="fe fe-user-plus mr-2"></i> Add User
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.roles') }}" class="nav-link {{ request()->routeIs('admin.users.roles') ? 'active' : '' }}">
                        <i class="fe fe-key mr-2"></i> Roles & Permissions
                    </a>
                </li>
            </ul>
        </div>
    </li>

    {{-- Activity Logs --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.logs*') ? 'active' : '' }}" href="{{ route('admin.logs') }}">
            <i class="fe fe-activity fe-16 mr-2"></i>
            <span>Activity Logs</span>
        </a>
    </li>
</ul>