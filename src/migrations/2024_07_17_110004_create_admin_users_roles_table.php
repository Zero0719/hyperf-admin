<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAdminUsersRolesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_users_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->integer('role_id')->default(0)->comment('角色ID');
            $table->unique(['user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users_roles');
    }
}
