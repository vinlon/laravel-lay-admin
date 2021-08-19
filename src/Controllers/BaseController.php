<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    /**
     * @param $entityClass
     *
     * @return Model
     */
    protected function getEntity($entityClass)
    {
        $id = request()->get('id');
        if ($id) {
            return $entityClass::query()->find($id);
        }

        return new $entityClass();
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

    protected function paginateResponse(Builder $query)
    {
        $count = $query->count();
        $page = request()->get('page', 0);
        $limit = request()->get('limit', 10); //如果没有设置limit，则默认只查询10条记录
        $items = $query
            ->skip($limit * ($page - 1))
            ->limit($limit)
            ->get()
        ;

        return $this->successResponse($items->toArray(), [
            'count' => $count,
        ]);
    }
}
