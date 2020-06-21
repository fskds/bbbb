<?php

namespace App\Models\Admin;

class Role extends \Spatie\Permission\Models\Role
{
	protected $guard_name = 'web';
}
