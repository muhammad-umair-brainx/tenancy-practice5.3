<?php

namespace App\Http\Middleware;

use Closure;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Website;
use Illuminate\Support\Facades\URL;

class EnforceTenancy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url = URL::current();
        $url = parse_url($url);
        $url = $url['host'].'/';
        $website = Website::whereHas('hostnames', function ($query) use ($url) {
            $query->where('fqdn', $url);
        })->first();

        app(Environment::class)->tenant($website->website);

        return $next($request);
    }
}
