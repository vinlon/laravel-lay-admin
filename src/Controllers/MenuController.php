<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Support\Facades\Auth;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;
use Vinlon\Laravel\LayAdmin\SideBar;
use Vinlon\Laravel\LayAdmin\SideBarCollection;

class MenuController extends BaseController
{
    public function sidebar()
    {
        $allMenu = SideBarCollection::_all();
        /** @var AdminUser $user */
        $user = Auth::user();
        $role = $user->role;
        if (!$role->is_root) {
            $allMenu = $allMenu->filter(function (SideBar $sidebar) use ($role) {
                return in_array($sidebar->uniqId, $role->menu_ids);
            });
        }

        return $this->successResponse($allMenu->toArray());
    }

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
}
