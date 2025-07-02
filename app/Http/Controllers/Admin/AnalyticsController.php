<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use App\Services\ResidentService;
use App\Services\DocumentService;
use App\Services\ComplaintService;
use App\Services\EnhancedHealthServiceService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function __construct(
        private AnalyticsService $analyticsService,
        private ResidentService $residentService,
        private DocumentService $documentService,
        private ComplaintService $complaintService,
        private EnhancedHealthServiceService $healthServiceService
    ) {}

    /**
     * Display the analytics dashboard
     */
    public function index()
    {
        // Before: 60+ lines of complex database queries
        // After: Clean service calls with comprehensive data
        
        $dashboardData = [
            // Core analytics from dedicated service
            'overview' => $this->analyticsService->getDashboardMetrics(),
            
            // Detailed service data
            'population' => $this->residentService->getDashboardData(),
            'documents' => $this->documentService->getDashboardData(),
            'complaints' => $this->complaintService->getDashboardData(),
            'health_services' => $this->healthServiceService->getDashboardData(),
            
            // Trend analysis
            'trends' => [
                'monthly_registrations' => $this->analyticsService->getMonthlyTrends(),
                'document_trends' => $this->documentService->getMonthlyTrends(),
                'complaint_trends' => $this->complaintService->getMonthlyTrends(),
                'health_trends' => $this->healthServiceService->getMonthlyTrends(),
            ],
            
            // Performance metrics
            'performance' => [
                'document_processing' => $this->documentService->getProcessingMetrics(),
                'complaint_resolution' => $this->complaintService->getPerformanceMetrics(),
                'health_service_performance' => $this->healthServiceService->getPerformanceMetrics(),
            ],
            
            // Attention items
            'attention_items' => [
                'complaints_needing_attention' => $this->complaintService->getComplaintsNeedingAttention(),
                'health_services_needing_attention' => $this->healthServiceService->getServicesNeedingAttention(),
            ],
        ];

        return view('admin.analytics.index', compact('dashboardData'));
    }

    /**
     * Get analytics data for AJAX requests
     */
    public function getAnalyticsData(Request $request)
    {
        $type = $request->get('type', 'overview');
        
        return match($type) {
            'population' => response()->json($this->residentService->getDemographicsSummary()),
            'documents' => response()->json($this->documentService->getDashboardData()),
            'complaints' => response()->json($this->complaintService->getDashboardData()),
            'health' => response()->json($this->healthServiceService->getDashboardData()),
            'trends' => response()->json($this->getTrendsData()),
            default => response()->json($this->analyticsService->getDashboardMetrics())
        };
    }

    /**
     * Generate analytics report
     */
    public function generateReport(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'type']);
        
        $reportData = $this->analyticsService->generateReportData($filters);
        
        return response()->json([
            'success' => true,
            'data' => $reportData
        ]);
    }

    /**
     * Get chart data for specific metrics
     */
    public function getChartData(Request $request)
    {
        $chartType = $request->get('chart');
        
        return match($chartType) {
            'gender_distribution' => response()->json($this->residentService->getGenderDistribution()),
            'age_distribution' => response()->json($this->residentService->getAgeDistribution()),
            'document_types' => response()->json($this->documentService->getPopularDocumentTypes()),
            'complaint_categories' => response()->json($this->complaintService->getCategoryDistribution()),
            'health_service_types' => response()->json($this->healthServiceService->getServiceTypeDistribution()),
            default => response()->json(['error' => 'Invalid chart type'])
        };
    }

    /**
     * Get trends data for charts
     */
    private function getTrendsData(): array
    {
        return [
            'population_growth' => $this->analyticsService->getMonthlyTrends(),
            'service_usage' => [
                'documents' => $this->documentService->getMonthlyTrends(),
                'complaints' => $this->complaintService->getMonthlyTrends(),
                'health_services' => $this->healthServiceService->getMonthlyTrends(),
            ]
        ];
    }
}