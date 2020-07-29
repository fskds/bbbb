<?php

namespace App\Models\Backend\Admin;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends \Spatie\Permission\Models\Permission
{
	use SoftDeletes;
	protected $guard_name = 'web';
    protected $fillable = ['name', 'display_name', 'guard_name', 'icon', 'sort', 'pid', 'route','status'];
	protected $dates = ['delete_at'];

    // 子权限
    public function child()
    {
        return $this->hasMany('App\Models\Backend\Admin\Permission'::class, 'pid', 'id');
    }

    // 获取权限表属性
    public function getAllCacheAttributes(): array
    {
        $cachePermissions = self::getPermissions();

        $attributesArr = [];
        foreach ($cachePermissions as $per) {
            $attributesArr[] = $per->attributes;
        }

        return $attributesArr;
    }
}