<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\Handler;

class AdminMiddleware
{
    private $Auth;

    public function handle($request, Closure $next)
    {
        $this->auth = auth()->user() ?
            (auth()->user()->level === 1 || auth()->user()->level === 2) :false;

    if($this->auth === true)
        return $next($request);
    return back();
    }
}
