<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Response;
use Illuminate\Http\Response as HttpResponse;

class ResponseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Response::macro('success', function (string $message = ''): HttpResponse {
            return Response::make([
                'status' => 'success',
                'message' => $message
            ]);
        });
    }

    public function register()
    {
        //
    }
}
