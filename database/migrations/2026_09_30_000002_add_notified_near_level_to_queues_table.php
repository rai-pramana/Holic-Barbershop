<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->unsignedTinyInteger('notified_near_level')->nullable()->after('notified_near_at');
        });
        // Yang sudah pernah terima notifikasi sekali tidak dikirim level 3 lagi,
        // tapi tetap menyusul level 2 dan 1.
        DB::table('queues')->whereNotNull('notified_near_at')->update(['notified_near_level' => 3]);
    }

    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->dropColumn('notified_near_level');
        });
    }
};
