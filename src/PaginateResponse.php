<?php

namespace Vinlon\Laravel\LayAdmin;

use Illuminate\Http\JsonResponse;

class PaginateResponse extends JsonResponse
{
    public $count;
    public $list;

    /**
     * PaginateResponse constructor.
     *
     * @param array $list
     * @param mixed $count
     */
    public function __construct($count, $list)
    {
        parent::__construct('');

        $this->count = $count;
        $this->list = $list;
    }
}
