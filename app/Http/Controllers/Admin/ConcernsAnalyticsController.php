<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlotterComplaint;
use App\Models\HealthServiceRequest;
use App\Models\DocumentRequest;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConcernsAnalyticsController extends Controller
{
    /**
     * Display the concerns and service requests analytics dashboard
     */
    public function index()
    {
        $data = [
            // Complaint Analytics
            'complaintsByCategory' => $this->getComplaintsByCategory(),
            'complaintsByPriority' => $this->getComplaintsByPriority(),
            'complaintsByArea' => $this->getComplaintsByArea(),
            'complaintsByStatus' => $this->getComplaintsByStatus(),
            
            // Resolution Metrics
            'resolutionMetrics' => $this->getResolutionMetrics(),
            'avgResolutionTimeByCategory' => $this->getAvgResolutionTimeByCategory(),
            
            // Trends
            'monthlyTrends' => $this->getMonthlyTrends(),
            'priorityTrends' => $this->getPriorityTrends(),
            
            // Top Concerns
            'topConcerns' => $this->getTopConcerns(),
            'urgentConcerns' => $this->getUrgentConcerns(),
            
            // Budget Analysis
            'budgetAnalysis' => $this->getBudgetAnalysis(),
            'costByCategory' => $this->getCostByCategory(),
            
            // Service Requests Analytics
            'healthServiceStats' => $this->getHealthServiceStats(),
            'documentRequestStats' => $this->getDocumentRequestStats(),
            
            // Combined KPIs
            'kpis' => $this->getKeyPerformanceIndicators(),
            
            // Feedback Integration
            'satisfactionMetrics' => $this->getSatisfactionMetrics(),
        ];

        return view('admin.concerns-analytics.index', $data);
    }

    /**
     * Get complaints grouped by category
     */
    private function getComplaintsByCategory()
    {
        return BlotterComplaint::select('complaint_category', DB::raw('count(*) as count'))
            ->whereNotNull('complaint_category')
            ->groupBy('complaint_category')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'category' => $this->formatCategory($item->complaint_category),
                    'count' => $item->count,
                    'percentage' => 0 // Will be calculated in view
                ];
            });
    }

    /**
     * Get complaints grouped by priority level
     */
    private function getComplaintsByPriority()
    {
        return BlotterComplaint::select('priority_level', DB::raw('count(*) as count'))
            ->groupBy('priority_level')
            ->get();
    }

    /**
     * Get complaints grouped by affected area (top 10)
     */
    private function getComplaintsByArea()
    {
        return BlotterComplaint::select('affected_area', DB::raw('count(*) as count'))
            ->whereNotNull('affected_area')
            ->where('affected_area', '!=', '')
            ->groupBy('affected_area')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get complaints grouped by status
     */
    private function getComplaintsByStatus()
    {
        return BlotterComplaint::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
    }

    /**
     * Get resolution metrics
     */
    private function getResolutionMetrics()
    {
        $totalComplaints = BlotterComplaint::count();
        $resolvedComplaints = BlotterComplaint::where('status', 'resolved')->count();
        
        return [
            'total_complaints' => $totalComplaints,
            'resolved_complaints' => $resolvedComplaints,
            'pending_complaints' => BlotterComplaint::where('status', 'pending')->count(),
            'avg_resolution_days' => BlotterComplaint::where('status', 'resolved')
                ->whereNotNull('resolution_duration_days')
                ->avg('resolution_duration_days') ?? 0,
            'total_resolution_cost' => BlotterComplaint::where('status', 'resolved')
                ->sum('resolution_cost') ?? 0,
            'resolution_rate' => $totalComplaints > 0 ? 
                round(($resolvedComplaints / $totalComplaints) * 100, 2) : 0,
            'total_affected_residents' => BlotterComplaint::sum('affected_residents_count') ?? 0,
        ];
    }

    /**
     * Get average resolution time by category
     */
    private function getAvgResolutionTimeByCategory()
    {
        return BlotterComplaint::select(
                'complaint_category', 
                DB::raw('AVG(resolution_duration_days) as avg_days'),
                DB::raw('COUNT(*) as count')
            )
            ->where('status', 'resolved')
            ->whereNotNull('complaint_category')
            ->whereNotNull('resolution_duration_days')
            ->groupBy('complaint_category')
            ->orderBy('avg_days', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'category' => $this->formatCategory($item->complaint_category),
                    'avg_days' => round($item->avg_days, 1),
                    'count' => $item->count
                ];
            });
    }

    /**
     * Get monthly trends for current year
     */
    private function getMonthlyTrends()
    {
        $currentYear = Carbon::now()->year;
        
        return BlotterComplaint::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "resolved" THEN 1 ELSE 0 END) as resolved'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending')
            )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'month' => Carbon::create()->month($item->month)->format('M'),
                    'total' => $item->total,
                    'resolved' => $item->resolved,
                    'pending' => $item->pending
                ];
            });
    }

    /**
     * Get priority trends over time
     */
    private function getPriorityTrends()
    {
        return BlotterComplaint::select(
                DB::raw('MONTH(created_at) as month'),
                'priority_level',
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month', 'priority_level')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get top concerns (highest impact)
     */
    private function getTopConcerns()
    {
        return BlotterComplaint::select(
                'case_number',
                'complaint_category',
                'complaint_details',
                'affected_area',
                'affected_residents_count',
                'priority_level',
                'status',
                'created_at'
            )
            ->where('status', '!=', 'resolved')
            ->orderByRaw("FIELD(priority_level, 'urgent', 'high', 'medium', 'low')")
            ->orderBy('affected_residents_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'case_number' => $item->case_number,
                    'category' => $this->formatCategory($item->complaint_category),
                    'details' => \Str::limit($item->complaint_details, 80),
                    'area' => $item->affected_area ?? 'N/A',
                    'affected_count' => $item->affected_residents_count,
                    'priority' => ucfirst($item->priority_level),
                    'status' => $item->status,
                    'days_open' => Carbon::parse($item->created_at)->diffInDays(now())
                ];
            });
    }

    /**
     * Get urgent concerns requiring immediate attention
     */
    private function getUrgentConcerns()
    {
        return BlotterComplaint::where('priority_level', 'urgent')
            ->where('status', '!=', 'resolved')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get budget analysis
     */
    private function getBudgetAnalysis()
    {
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();
        
        return [
            'total_spent' => BlotterComplaint::where('status', 'resolved')
                ->sum('resolution_cost') ?? 0,
            'spent_this_month' => BlotterComplaint::where('status', 'resolved')
                ->whereMonth('resolved_at', $currentMonth->month)
                ->whereYear('resolved_at', $currentMonth->year)
                ->sum('resolution_cost') ?? 0,
            'spent_last_month' => BlotterComplaint::where('status', 'resolved')
                ->whereMonth('resolved_at', $lastMonth->month)
                ->whereYear('resolved_at', $lastMonth->year)
                ->sum('resolution_cost') ?? 0,
            'avg_cost_per_resolution' => BlotterComplaint::where('status', 'resolved')
                ->whereNotNull('resolution_cost')
                ->avg('resolution_cost') ?? 0,
        ];
    }

    /**
     * Get cost by category
     */
    private function getCostByCategory()
    {
        return BlotterComplaint::select(
                'complaint_category', 
                DB::raw('SUM(resolution_cost) as total_cost'),
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(resolution_cost) as avg_cost')
            )
            ->where('status', 'resolved')
            ->whereNotNull('resolution_cost')
            ->whereNotNull('complaint_category')
            ->groupBy('complaint_category')
            ->orderBy('total_cost', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'category' => $this->formatCategory($item->complaint_category),
                    'total_cost' => $item->total_cost,
                    'count' => $item->count,
                    'avg_cost' => round($item->avg_cost, 2)
                ];
            });
    }

    /**
     * Get health service statistics
     */
    private function getHealthServiceStats()
    {
        return [
            'total_requests' => HealthServiceRequest::count(),
            'pending' => HealthServiceRequest::where('status', 'pending')->count(),
            'completed' => HealthServiceRequest::where('status', 'completed')->count(),
            'completed_this_month' => HealthServiceRequest::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];
    }

    /**
     * Get document request statistics
     */
    private function getDocumentRequestStats()
    {
        return [
            'total_requests' => DocumentRequest::count(),
            'pending' => DocumentRequest::where('status', 'pending')->count(),
            'approved' => DocumentRequest::where('status', 'approved')->count(),
            'approved_this_month' => DocumentRequest::where('status', 'approved')
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];
    }

    /**
     * Get Key Performance Indicators
     */
    private function getKeyPerformanceIndicators()
    {
        $resolutionMetrics = $this->getResolutionMetrics();
        
        return [
            // Efficiency KPIs
            'avg_response_time' => $resolutionMetrics['avg_resolution_days'],
            'resolution_rate' => $resolutionMetrics['resolution_rate'],
            
            // Volume KPIs
            'total_concerns' => $resolutionMetrics['total_complaints'],
            'pending_concerns' => $resolutionMetrics['pending_complaints'],
            
            // Impact KPIs
            'residents_affected' => $resolutionMetrics['total_affected_residents'],
            'urgent_cases' => BlotterComplaint::where('priority_level', 'urgent')
                ->where('status', '!=', 'resolved')
                ->count(),
            
            // Cost KPIs
            'total_budget_used' => $resolutionMetrics['total_resolution_cost'],
            'avg_cost_per_case' => $resolutionMetrics['total_complaints'] > 0 ?
                $resolutionMetrics['total_resolution_cost'] / $resolutionMetrics['total_complaints'] : 0,
        ];
    }

    /**
     * Get satisfaction metrics from feedback system
     */
    private function getSatisfactionMetrics()
    {
        $avgRating = Feedback::avg('rating') ?? 0;
        $totalFeedbacks = Feedback::count();
        
        return [
            'average_rating' => round($avgRating, 2),
            'total_feedbacks' => $totalFeedbacks,
            'satisfaction_rate' => $avgRating > 0 ? round(($avgRating / 5) * 100, 1) : 0,
            'recent_feedbacks' => Feedback::latest()->limit(5)->get(),
        ];
    }

    /**
     * Format category for display
     */
    private function formatCategory($category)
    {
        if (!$category) return 'Uncategorized';
        
        $categories = [
            'infrastructure' => 'Infrastructure & Utilities',
            'public_safety' => 'Public Safety & Security',
            'community_services' => 'Community Services',
            'environmental' => 'Environmental',
            'property_legal' => 'Property & Legal',
            'others' => 'Others',
        ];
        
        return $categories[$category] ?? ucwords(str_replace('_', ' ', $category));
    }

    /**
     * Export report to CSV
     */
    public function exportReport(Request $request)
    {
        $type = $request->get('type', 'all');
        
        // Implementation for CSV export
        // Will be added based on specific requirements
        
        return response()->json(['message' => 'Export feature coming soon']);
    }

    /**
     * Get analytics data for AJAX requests
     */
    public function getAnalyticsData(Request $request)
    {
        $dataType = $request->get('type', 'overview');
        
        $data = match($dataType) {
            'complaints' => $this->getComplaintsByCategory(),
            'priority' => $this->getComplaintsByPriority(),
            'trends' => $this->getMonthlyTrends(),
            'kpis' => $this->getKeyPerformanceIndicators(),
            default => ['error' => 'Invalid data type']
        };
        
        return response()->json($data);
    }
}
