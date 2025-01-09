<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classroom_id');
            $table->unsignedBigInteger('schedules_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('discipline_id');
            $table->timestamps();
            $table->userActions();
            $table->softDeletes();

            $table->foreign('classroom_id')
                ->references('id')
                ->on('classrooms')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->foreign('schedules_id')
                ->references('id')
                ->on('schedules')
                ->onDelete('cascade')
                ->onUpdate('cascade');

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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('timetables');
    }
};
