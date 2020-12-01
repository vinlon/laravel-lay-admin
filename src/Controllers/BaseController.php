<?php


namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    protected function getEntity($entityClass)
    {
        $id = request()->get('id');
        if ($id) {
            return $entityClass::query()->find($id);
        } else {
            return new $entityClass();
        }
    }

    protected function successResponse($data = [], array $extra = null)
    {
        $result = [
            'code' => 0,
            'data' => $data,
        ];
        if ($extra) {
            $result = array_merge($result, $extra);
        }
        return $result;
    }

    protected function errorResponse($code, $msg, array $extra = null)
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
        ];
        if ($extra) {
            $result = array_merge($result, $extra);
        }
        return $result;
    }
}