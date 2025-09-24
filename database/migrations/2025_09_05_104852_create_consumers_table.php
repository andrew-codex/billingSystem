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
        Schema::create('consumers', function (Blueprint $table) {
            $table->id();
                $table->string('full_name', 100); 
                $table->string('email', 150)->unique();
                $table->string('password');
                $table->string('address', 255)->nullable();
                $table->string('phone', 20)->nullable(); 
                $table->date('installation_date')->nullable();
                $table->string('house_type')->required();
                $table->enum('status', ['active', 'inactive', 'archived'])->default('active')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumers');
    }
};
