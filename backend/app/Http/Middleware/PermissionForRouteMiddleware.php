<?php
namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionForRouteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $routeName = $request->route()->action['as'];
        if (! Auth::user()->hasAnyPermission($routeName)) {
            throw UnauthorizedException::forRolesOrPermissions([$routeName]);
        }

        return $next($request);
    }
}
