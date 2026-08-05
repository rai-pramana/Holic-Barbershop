<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barbers', function (Blueprint $table) {
            // Add name & phone as direct columns (barber no longer linked to a User account)
            $table->string('name')->after('branch_id')->default('');
            $table->string('phone')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('barbers', function (Blueprint $table) {
            $table->dropColumn(['name', 'phone']);
        });
    }
};
