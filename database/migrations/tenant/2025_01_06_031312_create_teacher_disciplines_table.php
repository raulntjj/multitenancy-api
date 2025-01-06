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
        Schema::create('teacher_disciplines', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('discipline_id');

            $table->primary(['user_id', 'discipline_id']);

            $table->foreign('user_id')
                ->references('user_id')
                ->on('teachers')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('discipline_id')
                ->references('id')
                ->on('disciplines')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_disciplines');
    }
};
