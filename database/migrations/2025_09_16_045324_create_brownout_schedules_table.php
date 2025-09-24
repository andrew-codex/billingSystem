<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('brownout_schedules', function (Blueprint $table) {
            $table->id();
               $table->string('area')->index(); 
            $table->date('schedule_date')->index(); 
            $table->time('start_time');
            $table->time('end_time');
            $table->text('description')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'archived'])->default('scheduled')->index();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('brownout_schedules');
    }
};
