<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Official;

class PublicController extends Controller
{
    /**
     * Display the home page
     */
    public function home()
    {
        $officials = Official::getOrderedOfficials();
        
        return view('public.home', compact('officials'));
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
        // You can fetch announcements data here if needed
        return view('public.announcements');
    }
}
