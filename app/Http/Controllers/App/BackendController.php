<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;


class BackendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        return view('app.dashboard');
    }
}
