<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class StudentViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share notification data with all student layout views
        View::composer(['components.student-layout', 'components.student-top-nav', 'components.notification-dropdown-simple'], function ($view) {
            if (Auth::check() && Auth::user()->role === 'student') {
                $user = Auth::user();
                
                $recentNotifications = $user->notifications()
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                
                $unreadCount = $user->unreadNotifications()->count();
                
                $view->with([
                    'recentNotifications' => $recentNotifications,
                    'unreadCount' => $unreadCount
                ]);
            } else {
                $view->with([
                    'recentNotifications' => collect(),
                    'unreadCount' => 0
                ]);
            }
        });
    }
}