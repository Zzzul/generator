<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GeneratorOnlyWorkOnLocal
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        abort_if(env('APP_ENV') !== 'local', 403, 'Generator only work on local or development');

        return $next($request);
    }
}
