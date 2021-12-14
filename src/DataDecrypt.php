<?php

namespace Vinlon\Laravel\LayAdmin;

use Closure;
use Illuminate\Http\Request;

class DataDecrypt
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Request::METHOD_POST !== $request->getMethod()) {
            return $next($request);
        }
        $request = request();
        if ($request->lay_admin_encrypted_data) {
            $data = json_decode(base64_decode($request->encrypt_data), true);
            $request->replace($data);

            return $next($request);
        }

        return $next($request);
    }
}
