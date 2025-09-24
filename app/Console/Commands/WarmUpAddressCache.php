<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class WarmUpAddressCache extends Command
{
   protected $signature = 'cache:warm-address';
    protected $description = 'Warm up regions, provinces, cities, and barangays cache';

    public function handle()
    {
        $this->info('Warming up address cache...');

        Cache::rememberForever('regions_all', function () {
            return DB::table('regions')->orderBy('name')->get();
        });

        Cache::rememberForever('provinces_all', function () {
            return DB::table('provinces')->orderBy('name')->get();
        });

        Cache::rememberForever('cities_all', function () {
            return DB::table('cities')->orderBy('name')->get();
        });

        Cache::rememberForever('barangays_all', function () {
            return DB::table('barangays')->orderBy('name')->get();
        });

        $this->info('✅ Address cache warmed up successfully.');
        return 0;
    }
}
