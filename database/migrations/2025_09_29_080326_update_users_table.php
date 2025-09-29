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
                      Schema::table('users', function (Blueprint $table) {
     
        $table->string('city_name')->nullable()->after('name');
        $table->string('city_code')->nullable()->after('city_name');
           $table->string('barangay_name')->nullable()->after('city_code');
        $table->string('barangay_code')->nullable()->after('barangay_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
