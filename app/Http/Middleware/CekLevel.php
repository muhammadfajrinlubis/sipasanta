<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle($request, Closure $next, ...$levels)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $array_level = array_map('intval', $levels);

        if (!in_array((int) Auth::user()->level, $array_level)) {
            return abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }

}
