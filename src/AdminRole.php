<?php

namespace Vinlon\Laravel\LayAdmin;

use BenSampo\Enum\Enum;

/**
 * @method static static ROOT()
 */
class AdminRole extends Enum
{
    public const ROOT = '超级管理员';

    /**
     * @return bool
     */
    public function isRoot()
    {
        return self::ROOT == $this->value;
    }

    /**
     * @return string[]
     */
    public function getMenuIds()
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getPrivileges()
    {
        return [];
    }

    public function toArray()
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
        ];
    }
}
