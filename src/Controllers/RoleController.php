<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Vinlon\Laravel\LayAdmin\Models\AdminRole;
use Vinlon\Laravel\LayAdmin\SideBar;
use Vinlon\Laravel\LayAdmin\SideBarCollection;

class RoleController extends BaseController
{
    public function getRole($id)
    {
        $role = AdminRole::query()->find($id);
        $menuIds = $role->menu_ids;
        $topMenuIds = SideBarCollection::_all()->map(function (SideBar $sideBar) {
            return $sideBar->uniqId;
        })->toArray();
        $subMenuIds = array_filter($menuIds, function ($id) use ($topMenuIds) {
            return !in_array($id, $topMenuIds);
        });
        $result = $role->toArray();
        //方便前端初始化
        $result['sub_menu_ids'] = $subMenuIds;

        return $this->successResponse($result);
    }

    public function saveRole()
    {
        $param = request()->validate([
            'name' => 'required',
            'description' => 'nullable',
            'menu_id_list' => 'array',
        ]);
        $role = $this->getEntity(AdminRole::class);
        if ($role->is_root) {
            return $this->errorResponse('readonly_role', '该角色不可修改');
        }
        $role->name = $param['name'];
        $role->description = $param['description'] ?? '';
        $role->menu_ids = $param['menu_id_list'];
        $role->save();

        return $this->successResponse();
    }

    public function deleteRole($id)
    {
        $role = AdminRole::query()->find($id);
        $role->delete();

        return $this->successResponse();
    }

    public function getRoleList()
    {
        $roles = AdminRole::all();

        return $this->successResponse($roles->toArray());
    }
}
