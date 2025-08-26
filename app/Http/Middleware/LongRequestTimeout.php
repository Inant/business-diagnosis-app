<?php

namespace App\Http\Middleware;

use Closure;

class LongRequestTimeout
{
    public function handle($request, Closure $next)
    {
        // Naikkan batas waktu proses ke 180 detik (3 menit)
        @ignore_user_abort(true);
        @ini_set('max_execution_time', '180');
        @ini_set('default_socket_timeout', '180');
        @set_time_limit(180);

        return $next($request);
    }
}
