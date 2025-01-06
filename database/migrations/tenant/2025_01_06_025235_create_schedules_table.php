<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('time');
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
            $table->boolean('break')->default(false);
            $table->enum('break_type', ['interval', 'end_of_shift'])->nullable();
            $table->timestamps();
        });

        $schedules = [];
        $times = [
            ['time' => '07:00', 'break' => false],
            ['time' => '07:50', 'break' => false],
            ['time' => '08:40', 'break' => false],
            ['time' => '09:30', 'break' => true, 'break_type' => 'interval'],
            ['time' => '09:45', 'break' => false],
            ['time' => '10:35', 'break' => false],
            ['time' => '11:25', 'break' => true, 'break_type' => 'end_of_shift'],
            ['time' => '12:30', 'break' => false],
            ['time' => '13:20', 'break' => false],
            ['time' => '14:10', 'break' => false],
            ['time' => '15:00', 'break' => true, 'break_type' => 'interval'],
            ['time' => '15:15', 'break' => false],
            ['time' => '16:05', 'break' => false],
            ['time' => '16:55', 'break' => true, 'break_type' => 'end_of_shift'],
        ];
        
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        
        foreach ($days as $day) {
            foreach ($times as $time) {
                $schedule = [
                    'time' => $time['time'],
                    'day' => $day,
                    'break' => $time['break'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
        
                if ($time['break'] && isset($time['break_type'])) {
                    $schedule['break_type'] = $time['break_type'];
                } else {
                    $schedule['break_type'] = null;
                }
        
                $schedules[] = $schedule;
            }
        }
        
        DB::table('schedules')->insert($schedules);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
