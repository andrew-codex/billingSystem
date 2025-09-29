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
                Schema::table('linemen', function (Blueprint $table) {
        $table->string('region_code')->nullable()->after('region_name');
        $table->string('province_code')->nullable()->after('province_name');
        $table->string('city_code')->nullable()->after('city_name');
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
