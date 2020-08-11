<?php

namespace App\Models\Backend\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;
    protected $table = 'images';
	protected $fillable = ['name','path','title','size'];
    protected $dates = ['delete_at'];
    
    public function Article()
    {
        return $this->belongsToMany(Article::class);
    }
    
}
