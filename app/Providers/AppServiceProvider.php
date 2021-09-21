<?php

namespace App\Providers;

use App\Http\Lib\Classes\DiskStorage;
use App\Http\Lib\Interfaces\StorageManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
            $this->app->bind(StorageManagement::class,DiskStorage::class);
    }
}
