<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;

class LogTelegramRequests extends Middleware
{
    public function handle(Request $request, Closure $next): Response
    {
        //TODO сделать логику
        return $next($request);
    }
}
