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
}
