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
    Schema::table('consumers', function (Blueprint $table) {
    $table->string('region_code')->after('suffix')->nullable();
    $table->string('province_code')->after('region_code')->nullable();
    $table->string('city_code')->after('province_code')->nullable();
    $table->string('barangay_code')->after('city_code')->nullable();
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
