<?php

namespace Vinlon\Laravel\LayAdmin;

use Illuminate\Support\Collection;

class SideBarCollection extends Collection
{
    public static function _all()
    {
        $baseMenu = self::_base();
        /** @var SideBarCollection $appMenu */
        $appMenuConfig = config('lay-admin.sidebars');
        $appMenu = new SideBarCollection([]);
        foreach ($appMenuConfig as $item) {
            $appMenu->add(SideBar::fromArray($item));
        }

        return $appMenu->merge($baseMenu);
    }

    private static function _base()
    {
        return new self([
            SideBar::create('_system', '系统设置')->iconClass('layui-icon-set-sm')
                ->add(SideBar::create('_system.content', '内容管理')->jumpTo('_base/system/content/')),
            SideBar::create('_user', '用户')->iconClass('layui-icon-user')
                ->add(SideBar::create('_user.list', '后台管理员')->jumpTo('_base/user/user/'))
                ->add(SideBar::create('_user.role', '角色管理')->jumpTo('_base/user/role/')),
            SideBar::create('_my', '我的')->iconClass('layui-icon-username')
                ->add(SideBar::create('_my.profile', '基本资料')->jumpTo('_base/user/user/info'))
                ->add(SideBar::create('_my.password', '修改密码')->jumpTo('_base/user/user/password')),
        ]);
    }
}
