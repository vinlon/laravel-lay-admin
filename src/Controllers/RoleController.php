<?php


namespace Vinlon\Laravel\LayAdmin\Controllers;

use Vinlon\Laravel\LayAdmin\Models\AdminMenu;
use Vinlon\Laravel\LayAdmin\Models\AdminRole;

class RoleController extends BaseController
{
    public function getRole($roleId)
    {
        $role = AdminRole::query()->find($roleId);
        $menuIds = $role->menu_ids;
        $menus = AdminMenu::query()->find($menuIds);
        $subMenuIds = $menus->reject(function (AdminMenu $menu) {
            return $menu->pid == 0;
        })->map(function (AdminMenu $menu) {
            return $menu->id;
        })->toArray();
        $result = $role->toArray();
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

    public function deleteRole()
    {
        $param = request()->validate([
            'id' => 'required'
        ]);
        AdminRole::query()->find($param['id'])->delete();
        return $this->successResponse();
    }

    public function getRoleList()
    {
        $roles = AdminRole::all();
        return $this->successResponse($roles->toArray());
    }
}