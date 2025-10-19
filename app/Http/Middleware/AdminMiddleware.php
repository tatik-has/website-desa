<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin_logged_in')) {
            return redirect('/admin/login')->with('error', 'Anda harus login sebagai admin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
