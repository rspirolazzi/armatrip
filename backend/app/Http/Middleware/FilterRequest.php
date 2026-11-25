<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;

class FilterRequest
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $prefix = $request->route()->action['as'];
        $index = $request->route()->action['as'] . '.search';
        if ($request->get('clean', null)) {
            \Session::forget($index);
            \Session::save();
            $request->merge(['search' => null]);
            return $next($request);
        }
        $params = collect($request->toArray())->filter(function ($value, $key) {
            return ($key !== 'search' && !empty($value));
        });
        $valuesOnSession = [];


        if (sizeof($params) > 0) {
            $valuesOnSession = $params->each(function ($value, $key) use ($prefix) {
                \Session::put("$prefix.$key", $value);
            });
            \Session::save();
        } else {
            $valuesOnSession = collect($request->session()->get($prefix, []))->filter(function ($value, $key) {
                return ($key !== 'search' && !empty($value));
            });
        }
        if (!empty($valuesOnSession) && sizeof($valuesOnSession) > 0) {
            $valuesOnSession['search'] = collect($valuesOnSession)
                ->map(function ($value, $key) {
                    return "$key:$value";
                })->values()->implode(';');
            if (sizeof($valuesOnSession) > 2) {
                $valuesOnSession['searchJoin'] = 'and';
            }
            $request->merge($valuesOnSession->all());
        }
        //dd($request->toArray(), $valuesOnSession);
        return $next($request);
    }

}
