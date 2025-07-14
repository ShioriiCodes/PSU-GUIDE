<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use App\Models\SiteAnalytics;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        app()->terminating(function () {
            if (
                Schema::hasTable('site_analytics') &&
                Session::has('visit_start') &&
                Session::has('visit_id')
            ) {
                $start = Session::get('visit_start');
                $duration = now()->diffInSeconds($start);

                \App\Models\SiteAnalytics::where('id', Session::get('visit_id'))->update([
                    'duration' => $duration
                ]);

                Session::forget(['visit_start', 'visit_id']);
            }
        });
        
    }
    
}
