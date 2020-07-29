<?php

namespace App\Models\Backend\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Column extends Model
{
	use SoftDeletes;
    protected $table = 'Columns';
	protected $fillable = ['title','keywords','description','pic'];
	protected $dates = ['delete_at'];
	    // 子权限
    public function Section()
    {
        return $this->hasMany(Section::class);
    }

}
