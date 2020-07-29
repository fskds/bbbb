<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('href')->nullable()->comment('链接地址');
            $table->string('target',20);
            $table->integer('pid')->default(0);
			$table->integer('sort')->default(0)->comment('排序');
			$table->boolean('status')->default(1)->comment('状态');
			$table->softDeletes();
            $table->timestamps();
        });
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
			$table->boolean('status')->default(1)->comment('状态');
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
        Schema::dropIfExists('navs');
        Schema::dropIfExists('tags');
    }
}
