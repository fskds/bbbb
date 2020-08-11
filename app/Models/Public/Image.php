<?php

namespace App\Models\Public;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;
    protected $table = 'images';
	protected $fillable = ['name','path','title','size'];
    protected $dates = ['delete_at'];
}
