<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Vinlon\Laravel\LayAdmin\AdminRole;

class RoleController extends BaseController
{
    /** 查询角色列表 */
    public function getRoleList()
    {
        $roleClass = config('lay-admin.role_class', AdminRole::class);
        $instances = $roleClass::getInstances();
        $result = [];
        foreach ($instances as $instance) {
            $result[] = $instance->toArray();
        }

        return $this->successResponse($result);
    }
}
