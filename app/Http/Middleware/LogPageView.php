<?php

namespace App\Http\Middleware;

use App\Models\SiteAnalytics;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ActivityLogger;

class LogPageView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            SiteAnalytics::create([
                'event_type' => 'page_view',
                'url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_id' => optional($request->user())->id,
                'visited_at' => now(),
            ]);

            // More specific activity labels based on route name
            $routeName = optional($request->route())->getName();
            $action = match ($routeName) {
                'home' => 'view_home',
                'about' => 'view_about',
                'dashboard.admin' => 'view_admin_dashboard',
                'dashboard.registrar' => 'view_registrar_dashboard',
                'dashboard.usg' => 'view_usg_dashboard',
                'students.show', 'students.edit', 'students.export' => 'view_manage_students',
                'faculty.show', 'faculty.export' => 'view_manage_faculty',
                'announcements.index', 'announcement' => 'view_announcements',
                'announcements.show' => 'view_announcement_detail',
                'contact' => 'view_contact',
                'logs.index' => 'view_activity_logs',
                default => 'page_view',
            };

            ActivityLogger::log($action, null, null);
        } catch (\Throwable $exception) {
            // Silently ignore logging errors to avoid breaking page loads.
        }

        return $next($request);
    }
}


