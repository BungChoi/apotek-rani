<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share categories with all views that need them
        View::composer(['apoteker.products.*', 'public.home'], function ($view) {
            try {
                $categories = Category::where('is_active', true)->orderBy('name')->get();
                $view->with('categories', $categories);
            } catch (\Exception $e) {
                // Log the error and provide empty collection
                \Log::error('Error loading categories in view composer: ' . $e->getMessage());
                $view->with('categories', collect([]));
            }
        });
    }
}
