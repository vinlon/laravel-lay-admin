<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Support\Facades\Auth;
use Vinlon\Laravel\LayAdmin\Models\AdminMenu;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;

class MenuController extends BaseController
{
    public function saveMenu()
    {
        /** @var AdminMenu $menu */
        $menu = $this->getEntity(AdminMenu::class);
        $param = request()->validate([
            'title' => 'required',
            'pid' => 'integer',
            'icon' => 'nullable',
            'path' => 'nullable',
            'insert_after' => 'integer',
        ]);
        $pid = $param['pid'];
        $menu->title = $param['title'];
        $menu->pid = $param['pid'];
        $menu->icon = $param['icon'] ?? '';
        $menu->path = $param['path'] ?? '';
        $menu->save();

        $insertAfter = $param['insert_after'];
        if ($insertAfter != $menu->id) {
            //重新排序
            $siblings = AdminMenu::findOrderedListByPid($pid);
            $sequences = $siblings->mapWithKeys(function (AdminMenu $menu) {
                return [$menu->id => $menu->sequence];
            })->toArray();
            unset($sequences[$menu->id]);
            $afterSequence = $sequences[$insertAfter] ?? 0;
            $sequences[$menu->id] = $afterSequence + 0.5;
            asort($sequences);
            foreach ($siblings as $menu) {
                $menu->sequence = array_search($menu->id, array_keys($sequences)) + 1;
                $menu->save();
            }
        }

        return $this->successResponse();
    }

    /**
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function deleteMenu($id)
    {
        $menu = AdminMenu::query()->find($id);
        $menu->delete();
        return $this->successResponse();
    }

    public function getMenuList()
    {
        $pid = request()->get('pid', 0);
        $pMenu = AdminMenu::query()->find($pid);
        $menus = AdminMenu::findOrderedListByPid($pid);
        $result = $menus->map(function (AdminMenu $menu) use ($pMenu) {
            $arr = $menu->toArray();
            if ($pMenu) {
                $arr['p_menu'] = $pMenu->toArray();
            }
            return $arr;
        });
        return $this->successResponse($result, [
            'count' => AdminMenu::query()->where('pid', $pid)->count()
        ]);
    }

    public function sidebar()
    {
        /** @var AdminUser $user */
        $user = Auth::user();
        $role = $user->role;
        if ($role->is_root) {
            //超级管理员自动拥有所有权限
            $myMenus = AdminMenu::query()->orderBy('sequence')->get();
        } else {
            $myMenus = AdminMenu::query()->find($role->menu_ids)->orderBy('sequence');
        }

        $groupMenus = $myMenus->groupBy(function (AdminMenu $menu) {
            return $menu->pid;
        });
        $result = [];

        /** @var AdminMenu $pMenu */
        foreach ($groupMenus->get(0) ?? [] as $pMenu) {
            $subMenus = $groupMenus->get($pMenu->id) ?? [];
            $row = [
                'title' => $pMenu->title,
                'icon' => $pMenu->icon,
                'list' => []
            ];
            /** @var AdminMenu $subMenu */
            foreach ($subMenus as $subMenu) {
                $row['list'][] = [
                    'title' => $subMenu->title,
                    'jump' => $subMenu->path,
                ];
            }
            $result[] = $row;
        }
        return $this->successResponse($result);
    }

    public function getMenuTree()
    {
        $allMenus = AdminMenu::all();
        $groupMenus = $allMenus->groupBy(function (AdminMenu $menu) {
            return $menu->pid;
        });
        $result = [];
        /** @var AdminMenu $pMenu */
        foreach ($groupMenus->get(0) ?? [] as $pMenu) {
            $subMenus = $groupMenus->get($pMenu->id) ?? [];
            $row = [
                'title' => $pMenu->title,
                'id' => $pMenu->id,
                'spread' => true,
                'children' => []
            ];
            /** @var AdminMenu $subMenu */
            foreach ($subMenus as $subMenu) {
                $row['children'][] = [
                    'title' => $subMenu->title,
                    'id' => $subMenu->id,
                ];
            }
            $result[] = $row;
        }
        return $this->successResponse($result);
    }
}