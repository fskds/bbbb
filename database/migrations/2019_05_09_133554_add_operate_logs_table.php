<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddOperateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_operation_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('user_name');
            $table->string('menu_name');
            $table->string('sub_menu_name');
            $table->string('operate_name');
            $table->string('path');
            $table->string('method', 10)->nullable()->comment('请求方式');
            $table->string('ip');
            $table->text('input');
            $table->index('user_id');
            $table->timestamps();
        });

         // 表注释
         DB::statement("ALTER TABLE admin_operation_logs comment '用户操作表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_operation_logs');
    }
}