<?php

namespace Vinlon\Laravel\LayAdmin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdminConfig
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $key
 * @property string $value
 * @method static \Illuminate\Database\Eloquent\Builder|AdminConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminConfig query()
 */
class AdminConfig extends Model
{
    public static function set($key, $value)
    {
        $opt = self::query()->where('key', $key)->first();
        if (!$opt) {
            $opt = new static();
            $opt->key = $key;
        }
        $opt->value = $value;
        $opt->save();
    }

    public static function get($key, $default = null)
    {
        $opt = self::query()->where('key', $key)->first();
        if ($opt) {
            return $opt->value;
        }
        return $default;
    }
}
