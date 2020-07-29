<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Website extends Model
{
    use SoftDeletes;
    protected $table    = 'siteconfig';
    protected $fillable = ['site_name','site_url','site_logo','site_icp','site_tongji','site_copyright','co_name','address','map_lat','map_lng','co_phone','co_email','co_qq','co_wechat','seo_title','seo_keywords','seo_description'];

}