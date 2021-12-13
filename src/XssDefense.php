<?php

namespace Vinlon\Laravel\LayAdmin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class XssDefense
{
    protected $except = [];

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $uri = $request->getRequestUri();
        $exceptFields = Arr::get($this->except, $uri, []);
        $input = $request->all();
        foreach ($input as $key => &$value) {
            if (!in_array($key, $exceptFields)) {
                $value = htmlspecialchars($value);
            }
        }
        $request->merge($input);

        return $next($request);
    }
}
