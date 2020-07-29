<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siteconfig', function (Blueprint $table) {
            $table->id();
            $table->string('site_name',40)->nullable()->comment('网站名称');
			$table->string('site_url',40)->nullable()->comment('网站域名');
			$table->string('site_logo',60)->nullable()->comment('网站logo');
			$table->string('site_icp',30)->nullable()->comment('网站备案');
			$table->text('site_tongji')->nullable()->comment('统计代码');
			$table->text('site_copyright')->nullable()->comment('版权信息');
			$table->string('co_name',30)->nullable()->comment('公司名称');
			$table->string('address',60)->nullable()->comment('公司地址');
			$table->string('map_lat',20)->nullable()->comment('地图lat');
			$table->string('map_lng',20)->nullable()->comment('地图lng');
			$table->string('co_phone',20)->nullable()->comment('联系电话');
			$table->string('co_email',20)->nullable()->comment('联系邮箱');
			$table->string('co_qq',20)->nullable()->comment('qq');
			$table->string('co_wechat',20)->nullable()->comment('微信');
			$table->string('seo_title',60)->nullable()->comment('SEO标题');
			$table->string('seo_keywords',60)->nullable()->comment('SEO关键字');
			$table->text('seo_description')->nullable()->comment('SEO描述');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siteconfig');
    }
}
