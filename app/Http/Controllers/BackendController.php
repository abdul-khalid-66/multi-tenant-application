<?php

namespace App\Http\Controllers;



class BackendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard');
    }
}
