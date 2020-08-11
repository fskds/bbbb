<?php

namespace App\Models\Backend\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nav extends Model
{
	use SoftDeletes;
    protected $table = 'navs';
	protected $fillable = ['name','pid','href','target','sort','state'];
	protected $dates = ['delete_at'];
	    // 子权限
    public function child()
    {
        return $this->hasMany(Nav::class, 'pid', 'id');
    }

}
