<?php

namespace Vinlon\Laravel\LayAdmin;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Vinlon\Laravel\LayAdmin\Exceptions\AdminException;

class AdminResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $e = $response->exception;

        $code = 0;
        $httpStatus = 200;
        $count = null;
        $message = '';
        $data = [];
        $trace = null;
        if ($e) {
            $code = 'fail';
            if ($e instanceof AdminException) {
                $message = $e->getMessage();
            } else {
                if ($e instanceof ValidationException) {
                    //参数错误
                    $errors = $e->errors();
                    $message = Arr::first($errors)[0];
                    $data = $errors;
                } elseif ($e instanceof AuthenticationException) {
                    $httpStatus = 401;
                    $code = 'unauthenticated';
                    $message = $e->getMessage();
                } else {
                    //未知错误
                    $code = 'unknown';
                    $httpStatus = 500;
                    if (config('app.debug', false)) {
                        $message = $e->getMessage();
                        $trace['debug'] = $e->getTrace();
                    } else {
                        $message = '服务器错误';
                    }
                }
            }
        } else {
            if ($response instanceof PaginateResponse) {
                $count = $response->count;
                $data = $response->list;
            } elseif ($response instanceof JsonResponse) {
                $data = $response->getData();
            }
        }

        $response = new JsonResponse();
        $response->setData(array_filter([
            'code' => $code,
            'msg' => $message,
            'data' => $data,
            'count' => $count,
            'trace' => $trace,
        ], function ($var) {
            return !is_null($var);
        }))->setStatusCode($httpStatus);

        return $response;
    }
}
