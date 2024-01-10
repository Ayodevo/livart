<?php

namespace App\Http\Middleware;

use App\CentralLogics\Helpers;
use App\Traits\ActivationClass;
use Brian2694\Toastr\Facades\Toastr;
use Closure;
use http\Client;
use Illuminate\Support\Facades\Redirect;

class ActivationCheckMiddleware
{
    use ActivationClass;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('admin/auth/login')) {
            if (!$this->actch()) {
                return Redirect::away(base64_decode('aHR0cHM6Ly82YW10ZWNoLmNvbS9zb2Z0d2FyZS1hY3RpdmF0aW9u'))->send();
            }
        }
        return $next($request);

        //return redirect(base64_decode('aHR0cHM6Ly82YW10ZWNoLmNvbS9zb2Z0d2FyZS1hY3RpdmF0aW9u'));
    }
}
