<?php

use Vinlon\Laravel\LayAdmin\PaginateResponse;

/**
 * @param \Illuminate\Database\Eloquent\Builder $query
 * @param callable                              $mapFunc
 *
 * @return PaginateResponse
 */
function paginate_result(
    Illuminate\Database\Eloquent\Builder $query,
    callable $mapFunc = null
) {
    $count = $query->count();
    $page = request()->get('page', 0);
    $limit = request()->get('limit', 10); // 如果没有设置limit，则默认只查询10条记录
    $items = $query->offset($limit * ($page - 1))->limit($limit)->get();
    $result = $items->toArray();
    if ($mapFunc) {
        $result = $items->map($mapFunc)->toArray();
    }

    return new PaginateResponse($count, $result);
}

/**
 * @param mixed $entityClass
 *
 * @return \Illuminate\Database\Eloquent\Model
 */
function get_entity($entityClass)
{
    $id = request()->get('id');
    if ($id) {
        return $entityClass::query()->find($id);
    }

    return new $entityClass();
}
