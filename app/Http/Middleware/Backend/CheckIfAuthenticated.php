<?php

namespace App\Http\Middleware\Backend;

use Closure;
use App\Http\Controllers\Backend\Authentication\LoginController;

class CheckIfAuthenticated
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

        if ($request->session()->has(LoginController::BACKEND_LOGGED_IN_SESSION_NAME)) {
            return $next($request);
        }
        
        return redirect()->route('admin.login');

    }
}
