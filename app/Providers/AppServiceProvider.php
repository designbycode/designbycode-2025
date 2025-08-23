<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

//        DB::listen(function ($query) {
//            // $query->sql contains the SQL statement
//            // $query->bindings contains the parameters
//            // $query->time contains execution time in ms
//            logger()->info('Query executed: ' . $query->sql, [
////                'bindings' => $query->bindings,
////                'time' => $query->time,
//            ]);
//        });

        Schema::defaultStringLength(191);
    }
}
