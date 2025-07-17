<!-- Admin Sidebar Component -->
<div class="w-full md:w-64 bg-white md:border-r border-gray-200 md:min-h-screen">
    <!-- Add logo at the top of sidebar with correct path -->
    <div class="flex items-center justify-center p-4 border-b border-gray-200">
        <img src="{{ asset('images/logo.png') }}" alt="Lumanglipa Logo" class="h-16 w-auto">
    </div>
    <nav class="mt-5 px-2">
        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-4 py-2 text-base leading-6 font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'text-gray-900 bg-gray-100' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} focus:outline-none focus:bg-gray-200 transition ease-in-out duration-150">
            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.dashboard') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary']))
        <a href="{{ route('admin.documents') }}" class="mt-1 group flex items-center px-4 py-2 text-base leading-6 font-medium rounded-md {{ request()->routeIs('admin.documents') ? 'text-gray-900 bg-gray-100' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} focus:outline-none focus:bg-gray-200 transition ease-in-out duration-150">
            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.documents') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Document Requests
        </a>
        @endif

        @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary', 'Health Worker']))
        <a href="{{ route('admin.health') }}"
           class="mt-1 group flex items-center px-4 py-2 text-base leading-6 font-medium rounded-md
           {{ (request()->routeIs('admin.health') || request()->routeIs('admin.health-services.*')) ? 'text-gray-900 bg-gray-100' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}
           focus:outline-none focus:bg-gray-200 transition ease-in-out duration-150">
            <svg class="mr-3 h-6 w-6 {{ (request()->routeIs('admin.health') || request()->routeIs('admin.health-services.*')) ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            Health Services
        </a>
        @endif

        @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary', 'Complaint Manager']))
        <a href="{{ route('admin.complaints') }}" class="mt-1 group flex items-center px-4 py-2 text-base leading-6 font-medium rounded-md {{ (request()->routeIs('admin.complaints') || request()->routeIs('admin.complaint-management')) ? 'text-gray-900 bg-gray-100' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} focus:outline-none focus:bg-gray-200 transition ease-in-out duration-150">
            <svg class="mr-3 h-6 w-6 {{ (request()->routeIs('admin.complaints') || request()->routeIs('admin.complaint-management')) ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            Complaints
        </a>
        @endif

        @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary']))
        <a href="{{ route('admin.officials.edit-single') }}" class="mt-1 group flex items-center px-4 py-2 text-base leading-6 font-medium rounded-md {{ request()->routeIs('admin.officials.edit-single') ? 'text-gray-900 bg-gray-100' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} focus:outline-none focus:bg-gray-200 transition ease-in-out duration-150">
            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.officials.edit-single') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Officials
        </a>
        @endif

        @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary']))
        <a href="{{ route('admin.analytics') }}" class="mt-1 group flex items-center px-4 py-2 text-base leading-6 font-medium rounded-md {{ request()->routeIs('admin.analytics') ? 'text-gray-900 bg-gray-100' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} focus:outline-none focus:bg-gray-200 transition ease-in-out duration-150">
            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.analytics') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Analytics
        </a>
        @endif

        @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary']))
        <a href="{{ route('admin.security.audit-logs') }}" class="mt-1 group flex items-center px-4 py-2 text-base leading-6 font-medium rounded-md {{ request()->routeIs('admin.security.audit-logs') ? 'text-gray-900 bg-gray-100' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} focus:outline-none focus:bg-gray-200 transition ease-in-out duration-150">
            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.security.audit-logs') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 0a2 2 0 002 2h2a2 2 0 002-2m-6 0V9a2 2 0 012-2h2a2 2 0 012 2v8" />
            </svg>
            Audit Logs
        </a>
        @endif

        <!-- User Profile Section -->
        <div class="mt-8 border-t border-gray-200 pt-4">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->role->name }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1 px-2">
                <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    Your Profile
                </a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 text-left">
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </nav>
</div>