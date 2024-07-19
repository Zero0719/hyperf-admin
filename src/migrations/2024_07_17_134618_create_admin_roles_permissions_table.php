<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAdminRolesPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_roles_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('role_id')->default(0)->comment('角色ID');
            $table->integer('permission_id')->default(0)->comment('权限ID');
            $table->unique(['role_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles_permissions');
    }
}
