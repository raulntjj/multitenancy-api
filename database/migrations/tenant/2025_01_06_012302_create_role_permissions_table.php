<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->string('role');
            $table->string('permission');

            $table->primary(['role', 'permission']);

            $table->foreign('role')
                ->references('role')
                ->on('user_roles')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('permission')
                ->references('permission')
                ->on('permissions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
