<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TeacherDashboardController extends Controller
{
    /**
     * Display the teacher dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Get recent notifications for the notification dropdown
        $recentNotifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $unreadCount = $user->unreadNotifications()->count();
        
        return view('teacher.dashboard', compact('recentNotifications', 'unreadCount'));
    }
}
