<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Support\Facades\Auth;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;
use Vinlon\Laravel\LayAdmin\SideBar;
use Vinlon\Laravel\LayAdmin\SideBarCollection;

class MenuController extends BaseController
{
    /** 查询侧边栏菜单数据 */
    public function sidebar()
    {
        $allMenu = SideBarCollection::_all();
        /** @var AdminUser $user */
        $user = Auth::user();
        $role = $user->role;
        if (!$role->is_root) {
            $allMenu = $this->filterRoleMenu($allMenu, $role);
        }

        return $this->successResponse($allMenu->toArray());
    }

    /** 查询菜单列表（树形结构） */
    public function getMenuTree()
    {
        $allMenus = SideBarCollection::_all();
        $result = $allMenus->map(function (SideBar $sideBar) {
            $row = [
                'title' => $sideBar->title,
                'id' => $sideBar->uniqId,
                'spread' => true,
                'children' => [],
            ];
            /** @var SideBar $sub */
            foreach ($sideBar->children as $sub) {
                $row['children'][] = [
                    'title' => $sub->title,
                    'id' => $sub->uniqId,
                ];
            }

            return $row;
        })->toArray();

        return $this->successResponse($result);
    }

    private function filterRoleMenu($menus, $role)
    {
        $roleMenu = new SideBarCollection();
        /** @var SideBar $menu */
        foreach ($menus as $menu) {
            if (!in_array($menu->uniqId, $role->menu_ids)) {
                continue;
            }
            if ($menu->children->count() > 0) {
                $menu->children = $this->filterRoleMenu($menu->children, $role);
            }
            $roleMenu->add($menu);
        }

        return $roleMenu;
    }
}
