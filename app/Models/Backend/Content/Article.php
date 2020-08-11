<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;
    protected $table = 'articles';
	protected $fillable = [];
    protected $dates = ['delete_at'];
    
    public function Image()
    {
        return $this->belongsToMany(Image::class);
    }
}
