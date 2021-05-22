<?php

use Vinlon\Laravel\LayAdmin\SideBar;
use Vinlon\Laravel\LayAdmin\SideBarCollection;

$sidebars = new SideBarCollection([]);

/* 在此处定义应用菜单 */
//$sidebars->add(
//    SideBar::create('test', '一极菜单')->iconClass('test')
//        ->add(SideBar::create('test.sub', '二级菜单')->jumpTo('test'))
//);

return [
    /*
     * Admin页面Route Prefix
     * 默认值： admin, 此时管理页面访问地址为 http://localhost:8000/admin
     */
    'route_prefix' => env('LAY_ADMIN_ROUTE_PREFIX', 'admin'),

    /*
     * Admin后台显示名称
     */
    'display_name' => env('LAY_ADMIN_DISPLAY_NAME', '后台管理系统'),

    /*
     * 菜单定义
     */
    'sidebars' => $sidebars,
];
