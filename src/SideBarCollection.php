<?php

namespace Vinlon\Laravel\LayAdmin;

use Illuminate\Support\Collection;

class SideBarCollection extends Collection
{
    public static function _all()
    {
        $baseMenu = self::_base();
        /** @var SideBarCollection $appMenu */
        $appMenu = config('lay-admin.sidebars');

        return $appMenu->merge($baseMenu);
    }

    private static function _base()
    {
        return new self([
            SideBar::create('_user', '用户')->iconClass('layui-icon-user')
                ->add(SideBar::create('_user.list', '后台管理员')->jumpTo('_base/user/user/'))
                ->add(SideBar::create('_user.role', '角色管理')->jumpTo('_base/user/role/')),
            SideBar::create('_my', '我的')->iconClass('layui-icon-username')
                ->add(SideBar::create('_my.profile', '基本资料')->jumpTo('_base/user/user/info'))
                ->add(SideBar::create('_my.password', '修改密码')->jumpTo('_base/user/user/password')),
        ]);
    }
}
