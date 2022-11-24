<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Broadcast::routes();
        Broadcast::routes(["prefix" => "api", "middleware" => ["auth:api"]]);
        // Broadcast::routes([ "middleware" => "auth:api"]);
        // Broadcast::routes(['middleware' => 'auth:admin']);
        // Broadcast::routes(['middleware' => ['web', 'auth:admin, master']]);

        require base_path('routes/channels.php');
    }
}
