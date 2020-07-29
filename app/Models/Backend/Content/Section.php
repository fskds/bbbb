<?php

namespace App\Models\Backend\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Section extends Model
{
    protected $table = 'sections';
    protected $fillable = ['name','column_id','html','css','js','pic','sort','state'];
    protected $dates = ['delete_at'];
	use SoftDeletes;
	public function Column()
    {
        return $this->belongsTo(Column::class);
    }

}
