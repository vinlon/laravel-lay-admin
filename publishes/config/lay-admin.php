<?php

use Vinlon\Laravel\LayAdmin\SideBar;
use Vinlon\Laravel\LayAdmin\SideBarCollection;

$sidebars = new SideBarCollection();

/* 在此处定义应用菜单 */
$sidebars->add(
    SideBar::create('test', '自定义一级菜单')->iconClass('layui-icon-app')
        ->add(SideBar::create('test.sub', '自定义二级菜单')->jumpTo('test'))
);

// 默认菜单
$sidebars->add(
    SideBar::create('_resource', '资源管理')->iconClass('layui-icon-component')
        ->add(SideBar::create('_resource.text', '富文本管理')->jumpTo('common/resource/text_resource'))
        ->add(SideBar::create('_resource.image', '图片管理')->jumpTo('common/resource/image_resource'))
);
$sidebars->add(
    SideBar::create('_user', '系统管理员')->iconClass('layui-icon-key')->jumpTo('_base/user/user/')
);
$sidebars->add(
    SideBar::create('_my', '我的')->iconClass('layui-icon-username')
        ->add(SideBar::create('_my.profile', '基本资料')->jumpTo('_base/user/user/info'))
        ->add(SideBar::create('_my.password', '修改密码')->jumpTo('_base/user/user/password'))
);

return [
    /*
     * Admin页面Route Prefix
     * 默认值： admin, 此时管理页面访问地址为 {{APP_URL}}/admin
     */
    'route_prefix' => env('LAY_ADMIN_ROUTE_PREFIX', 'admin'),

    /*
     * Admin后台显示名称
     */
    'display_name' => env('LAY_ADMIN_DISPLAY_NAME', '后台管理系统'),

    /*
     * 自定义middleware
     */
    'middleware' => [
    ],

    /*
     * 菜单定义
     */
    'sidebars' => $sidebars->toArray(),

    /*
     * 角色定义类
     */
    'role_class' => \Vinlon\Laravel\LayAdmin\AdminRole::class,
];
