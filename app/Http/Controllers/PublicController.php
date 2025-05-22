<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display the home page
     */
    public function home()
    {
        return view('public.home');
    }
    
    /**
     * Display the about page
     */
    public function about()
    {
        return view('public.about');
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
