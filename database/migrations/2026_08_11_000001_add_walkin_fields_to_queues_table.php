<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            // Walk-in queue support — for customers without an account
            $table->string('guest_name', 100)->nullable()->after('notes');
            $table->string('guest_phone', 20)->nullable()->after('guest_name');

            // Make customer_id nullable for walk-in
            $table->foreignId('customer_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->dropColumn(['guest_name', 'guest_phone']);
            $table->foreignId('customer_id')->nullable(false)->change();
        });
    }
};
