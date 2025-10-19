<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Official;
use App\Models\Announcement;

class PublicController extends Controller
{
    /**
     * Display the home page
     */
    public function home()
    {
        $officials = Official::getOrderedOfficials();
        
        // Get recent announcements for the home page (limit to 5 most recent)
        $announcements = Announcement::currentlyActive()
                                   ->with('registrations')
                                   ->latest()
                                   ->limit(5)
                                   ->get();
        
        return view('public.home', compact('officials', 'announcements'));
    }
    
    /**
     * Display the about page
     */
    public function about()
    {
        $officials = Official::getOrderedOfficials();
        
        return view('public.about', compact('officials'));
    }
    
    /**
     * Display the services page
     */
    public function services()
    {
        return view('public.services');
    }
    
    /**
     * Display the contact page
     */
    public function contact()
    {
        return view('public.contact');
    }

    /**
     * Display the officials page
     */
    public function officials()
    {
        // You can fetch officials data here if needed
        return view('public.officials');
    }

    /**
     * Display the announcements page
     */
    public function announcements()
    {
        // Fetch announcements data for the view
        $announcements = Announcement::currentlyActive()
                                   ->with('registrations')
                                   ->latest()
                                   ->paginate(12);
        
        return view('public.announcements.index', compact('announcements'));
    }
}
