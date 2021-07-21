<?php

namespace Vinlon\Laravel\LayAdmin;

use Illuminate\Support\Collection;

class SideBarCollection extends Collection
{
    public static function _all()
    {
        $sidebarConfig = config('lay-admin.sidebars');
        $sidebars = new SideBarCollection([]);
        foreach ($sidebarConfig as $item) {
            $sidebars->add(SideBar::fromArray($item));
        }

        return $sidebars;
    }
}
