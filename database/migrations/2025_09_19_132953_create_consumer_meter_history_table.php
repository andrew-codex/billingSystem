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
        Schema::create('consumer_meter_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->constrained('consumers')->onDelete('cascade');
            $table->foreignId('meter_id')->constrained('electric_meters')->onDelete('cascade');
            $table->enum('transaction_type', [ 'replacement', 'transfer']);
            $table->dateTime('start_date')->useCurrent(); 
            $table->dateTime('end_date')->nullable();      
            $table->text('remarks')->nullable();        
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumer_meter_history');
    }
};
