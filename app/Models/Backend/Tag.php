<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
	use SoftDeletes;
    protected $table = 'tags';
	protected $fillable = ['name','state'];
	protected $dates = ['delete_at'];

}
