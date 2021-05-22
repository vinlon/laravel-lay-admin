<?php

namespace Vinlon\Laravel\LayAdmin;

use Illuminate\Contracts\Support\Arrayable;

class SideBar implements Arrayable
{
    /** @var string */
    public $uniqId;

    /** @var string */
    public $title;

    /** @var string */
    public $jump;

    /** @var string */
    public $iconClass;

    /** @var SideBarCollection */
    public $children;

    /**
     * SideBar constructor.
     */
    public function __construct(string $uniqId, string $title, string $jump, string $iconClass)
    {
        $this->uniqId = $uniqId;
        $this->title = $title;
        $this->jump = $jump;
        $this->iconClass = $iconClass;
        $this->children = new SideBarCollection([]);
    }

    public function toArray()
    {
        return [
            'id' => $this->uniqId,
            'title' => $this->title,
            'jump' => $this->jump,
            'icon' => $this->iconClass,
            'list' => $this->children->toArray(),
        ];
    }

    /** 创建Sidebar实例 */
    public static function create($uniqId, $name, $jump = '', $iconClass = '')
    {
        return new self($uniqId, $name, $jump, $iconClass);
    }

    /** 设置跳转链接 */
    public function jumpTo($jump)
    {
        $this->jump = $jump;

        return $this;
    }

    /** 设置图标的样式class */
    public function iconClass($iconClass)
    {
        $this->iconClass = $iconClass;

        return $this;
    }

    /** 添加子菜单 */
    public function add(SideBar $sideBar)
    {
        $this->children->add($sideBar);

        return $this;
    }
}
