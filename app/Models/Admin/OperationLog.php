<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class OperationLog extends Model
{
	protected $table = 'admin_operation_logs';
    protected $fillable = ['user_name', 'menu_name', 'sub_menu_name', 'input', 'ip', 'path', 'method', 'user_id', 'operate_name'];
}
