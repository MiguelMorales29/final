<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index(): View
    {
        return view('student.dashboard');
    }
}
