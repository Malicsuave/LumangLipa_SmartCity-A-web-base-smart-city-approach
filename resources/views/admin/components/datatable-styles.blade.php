{{-- DataTables CSS --}}
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
{{-- Common Admin Tables Styles --}}
<link rel="stylesheet" href="{{ asset('css/admin/tables.css') }}">

<style>
/* Hide duplicate pagination elements - show only one set */
.dataTables_wrapper .dataTables_paginate span > a.paginate_button,
.dataTables_wrapper .dataTables_paginate span.paginate_button {
    display: none !important;
}

/* Remove all spacing and connect pagination buttons */
.dataTables_wrapper .dataTables_paginate {
    white-space: nowrap !important;
    background: none !important;
    border: none !important;
    box-shadow: none !important;
}

.dataTables_wrapper .dataTables_paginate .pagination {
    margin: 0 !important;
    padding: 0 !important;
    display: flex !important;
    gap: 0 !important;
    background: none !important;
    border: none !important;
    box-shadow: none !important;
}

.dataTables_wrapper .dataTables_paginate .page-item {
    margin: 0 !important;
    padding: 0 !important;
}

/* Use only Bootstrap pagination - connected buttons */
.dataTables_wrapper .dataTables_paginate .page-link {
    padding: .5rem .75rem !important;
    line-height: 1.25 !important;
    font-size: .875rem !important;
    box-sizing: border-box !important;
    transform: none !important;
    transition: none !important;
    margin: 0 !important;
    margin-left: -1px !important;
    border-radius: 0 !important;
    display: block !important;
    position: relative !important;
    border: 1px solid #dee2e6 !important;
    background-color: #fff !important;
    color: #007bff !important;
    text-decoration: none !important;
}

/* First button - no left margin, left rounded corners */
.dataTables_wrapper .dataTables_paginate .page-item:first-child .page-link {
    margin-left: 0 !important;
    border-top-left-radius: .25rem !important;
    border-bottom-left-radius: .25rem !important;
}

/* Last button - right rounded corners */
.dataTables_wrapper .dataTables_paginate .page-item:last-child .page-link {
    border-top-right-radius: .25rem !important;
    border-bottom-right-radius: .25rem !important;
}

/* Active state */
.dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: #fff !important;
    z-index: 3 !important;
}

/* Hover state */
.dataTables_wrapper .dataTables_paginate .page-link:hover {
    background-color: #e9ecef !important;
    border-color: #dee2e6 !important;
    color: #0056b3 !important;
    z-index: 2 !important;
}

/* Disabled state */
.dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
    background-color: #fff !important;
    border-color: #dee2e6 !important;
    color: #6c757d !important;
    pointer-events: none !important;
}

/* Prevent any size changes on focus, active, or click states */
.dataTables_wrapper .dataTables_paginate .page-link:focus,
.dataTables_wrapper .dataTables_paginate .page-link:active,
.dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
    padding: .5rem .75rem !important;
    line-height: 1.25 !important;
    font-size: .875rem !important;
    transform: none !important;
    box-shadow: none !important;
    outline: none !important;
    margin: 0 !important;
    margin-left: -1px !important;
}

/* Fix first button state margin */
.dataTables_wrapper .dataTables_paginate .page-item:first-child .page-link:focus,
.dataTables_wrapper .dataTables_paginate .page-item:first-child .page-link:active {
    margin-left: 0 !important;
}

/* Make DataTables info text smaller */
.dataTables_wrapper .dataTables_info {
    font-size: 0.75rem !important;
    color: #6c757d !important;
    margin-top: 0.5rem !important;
}

/* Fix dropdown positioning and prevent overlap on smaller screens */
.dataTables_wrapper .dt-buttons .dropdown-menu {
    position: absolute !important;
    z-index: 1060 !important;
    max-width: 250px !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175) !important;
    border: 1px solid rgba(0, 0, 0, 0.15) !important;
    background-color: #fff !important;
    opacity: 1 !important;
}

/* Mobile responsiveness for dropdown */
@media (max-width: 768px) {
    .dataTables_wrapper .dt-buttons .dropdown-menu {
        background-color: #fff !important;
        opacity: 1 !important;
        z-index: 1070 !important;
        max-width: 250px !important;
        border: 1px solid #dee2e6 !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
}

/* Tablet responsiveness */
@media (max-width: 992px) and (min-width: 769px) {
    .dataTables_wrapper .dt-buttons .dropdown-menu {
        right: 0 !important;
        left: auto !important;
        min-width: 200px !important;
        max-width: 300px !important;
        background-color: #fff !important;
        opacity: 1 !important;
    }
}

/* Prevent horizontal scroll issues */
.dataTables_wrapper {
    overflow-x: visible !important;
}

/* Customize search input - move label inside and add icon */
.dataTables_wrapper .dataTables_filter {
    position: relative !important;
    float: right !important;
}

.dataTables_wrapper .dataTables_filter label {
    position: relative !important;
    margin: 0 !important;
    font-weight: normal !important;
    width: auto !important;
    display: inline-block !important;
}

/* Hide the "Search:" text completely */
.dataTables_wrapper .dataTables_filter label::before {
    content: "" !important;
    display: none !important;
}

.dataTables_wrapper .dataTables_filter label {
    font-size: 0 !important;
    line-height: 0 !important;
}

.dataTables_wrapper .dataTables_filter input[type="search"] {
    margin-left: 0 !important;
    padding-left: 2.5rem !important;
    padding-right: 0.75rem !important;
    padding-top: 0.375rem !important;
    padding-bottom: 0.375rem !important;
    border-radius: 0.25rem !important;
    border: 1px solid #ced4da !important;
    background-color: #fff !important;
    font-size: 0.875rem !important;
    line-height: 1.5 !important;
    color: #495057 !important;
    width: 200px !important;
    display: inline-block !important;
}

/* Add "Search" text only */
.dataTables_wrapper .dataTables_filter::before {
    content: "Search" !important;
    position: absolute !important;
    left: 0.5rem !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    color: #6c757d !important;
    font-size: 0.875rem !important;
    pointer-events: none !important;
    z-index: 5 !important;
    transition: opacity 0.2s ease !important;
}

/* Hide search text when input is focused or has content */
.dataTables_wrapper .dataTables_filter input[type="search"]:focus ~ ::before,
.dataTables_wrapper .dataTables_filter input[type="search"]:not(:placeholder-shown) ~ ::before {
    opacity: 0 !important;
}

.dataTables_wrapper .dataTables_filter:focus-within::before {
    opacity: 0 !important;
}

.dataTables_wrapper .dataTables_filter input[type="search"]:focus {
    padding-left: 0.75rem !important;
}

.dataTables_wrapper .dataTables_filter input[type="search"]:not(:focus):placeholder-shown {
    padding-left: 2.5rem !important;
}

.dataTables_wrapper .dataTables_filter input[type="search"]::placeholder {
    color: #6c757d !important;
    opacity: 1 !important;
}

/* Make show entries dropdown smaller */
.dataTables_wrapper .dataTables_length {
    font-size: 0.75rem !important;
    margin-right: 1rem !important;
    float: left !important;
}

.dataTables_wrapper .dataTables_length label {
    font-size: 0.75rem !important;
    margin: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
    white-space: nowrap !important;
}

.dataTables_wrapper .dataTables_length select {
    font-size: 0.75rem !important;
    padding: 0.25rem 0.5rem !important;
    margin: 0 0.25rem !important;
    border-radius: 0.25rem !important;
    border: 1px solid #ced4da !important;
    background-color: #fff !important;
    color: #495057 !important;
    height: auto !important;
    min-height: 1.75rem !important;
    width: auto !important;
    min-width: 4rem !important;
}

/* Fix export buttons positioning */
.dataTables_wrapper .dt-buttons {
    float: left !important;
    clear: none !important;
    margin-left: 0.5rem !important;
}

/* Fix action dropdown menus in table - prevent disappearing on scroll */
.dataTables_wrapper table .dropdown-menu {
    z-index: 9999 !important;
    background-color: #fff !important;
    border: 1px solid rgba(0, 0, 0, 0.15) !important;
    border-radius: 0.25rem !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175) !important;
    opacity: 1 !important;
    will-change: transform !important;
}

/* When dropdown is shown, make it fixed to prevent disappearing on scroll */
.dataTables_wrapper table .dropdown.show .dropdown-menu {
    position: fixed !important;
    z-index: 10000 !important;
    transform: none !important;
    backface-visibility: hidden !important;
    /* Prevent overlap with navbar - ensure it stays below */
    max-height: calc(100vh - 120px) !important;
    overflow-y: auto !important;
    /* Keep within table boundaries */
    max-width: 200px !important;
    min-width: 150px !important;
    /* Force visibility during scroll */
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
    pointer-events: auto !important;
}

/* Ensure dropdown positions correctly relative to button */
.dataTables_wrapper table .dropdown {
    position: relative !important;
}

/* Prevent dropdown from going outside viewport boundaries */
.dataTables_wrapper table .dropdown.show .dropdown-menu {
    /* JavaScript will handle positioning, but CSS ensures boundaries */
    top: auto !important;
    bottom: auto !important;
    left: auto !important;
    right: auto !important;
}

/* Force dropdown to stay open - override any JS hiding */
.dataTables_wrapper table .dropdown.show .dropdown-menu,
.dataTables_wrapper table .dropdown-menu.show {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* Ensure parent dropdown container maintains show state */
.dataTables_wrapper table .dropdown.show {
    position: relative !important;
}

/* Mobile responsiveness - prevent dropdown from disappearing */
@media (max-width: 768px) {
    .dataTables_wrapper table .dropdown-menu {
        position: absolute !important;
        will-change: transform !important;
    }
    
    .dataTables_wrapper table .dropdown.show .dropdown-menu {
        position: fixed !important;
        z-index: 10001 !important;
        transform: none !important;
        -webkit-transform: none !important;
        backface-visibility: hidden !important;
        -webkit-backface-visibility: hidden !important;
    }
    
    /* Ensure dropdown container allows fixed positioning */
    .dataTables_wrapper table .dropdown {
        position: static !important;
    }
    
    /* Prevent table overflow from hiding dropdown */
    .dataTables_wrapper,
    .dataTables_wrapper .dataTables_scrollBody {
        overflow: visible !important;
    }
}

@media (max-width: 768px) {
    .dataTables_wrapper {
        overflow-x: visible !important;
    }
    
    /* Prevent centering on mobile - keep left aligned */
    .dataTables_wrapper .row {
        text-align: left !important;
        justify-content: flex-start !important;
    }
    
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dt-buttons,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        text-align: left !important;
        margin-left: 0 !important;
        float: none !important;
        display: block !important;
        width: auto !important;
    }
    
    /* Stack buttons vertically on very small screens */
    .dataTables_wrapper .dt-buttons {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
        margin-bottom: 0.5rem !important;
        justify-content: flex-start !important;
    }
    
    .dataTables_wrapper .dt-buttons .btn {
        font-size: 0.75rem !important;
        padding: 0.25rem 0.5rem !important;
    }
}
</style>


