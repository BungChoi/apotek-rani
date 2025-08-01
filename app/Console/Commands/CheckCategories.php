<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;

class CheckCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and fix categories issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking categories...');
        
        try {
            $categories = Category::all();
            $this->info("Found {$categories->count()} categories");
            
            if ($categories->count() == 0) {
                $this->warn('No categories found! Running seeder...');
                $this->call('db:seed', ['--class' => 'CategorySeeder']);
                
                $categories = Category::all();
                $this->info("Now found {$categories->count()} categories");
            }
            
            $activeCategories = Category::where('is_active', true)->count();
            $this->info("Active categories: {$activeCategories}");
            
            if ($activeCategories == 0) {
                $this->warn('No active categories found! Activating all categories...');
                Category::query()->update(['is_active' => true]);
                $this->info('All categories activated');
            }
            
            $this->info('Categories check completed successfully!');
            
        } catch (\Exception $e) {
            $this->error('Error checking categories: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
} 