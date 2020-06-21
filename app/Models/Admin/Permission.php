<?php

namespace App\Models\Admin;

class Permission extends \Spatie\Permission\Models\Permission
{
	protected $guard_name = 'web';
    // 菜单图标
    public function icon()
    {
        return $this->belongsTo('App\Models\Icon', 'icon_id', 'id');
    }

    // 子权限
    public function childs()
    {
        return $this->hasMany('App\Models\Admin\Permission', 'parent_id', 'id');
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