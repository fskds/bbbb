<?php

namespace App\Models\Backend\Admin;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends \Spatie\Permission\Models\Role
{
	use SoftDeletes;
	protected $guard_name = 'web';
	protected $dates = ['delete_at'];
}
