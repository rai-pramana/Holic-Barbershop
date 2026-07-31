<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('queue_prefix', 4)->unique()->nullable()->after('is_active');
        });

        // Auto-assign prefix based on branch order (0-indexed)
        $branches = DB::table('branches')->orderBy('id')->get();
        foreach ($branches as $index => $branch) {
            DB::table('branches')
                ->where('id', $branch->id)
                ->update(['queue_prefix' => (string) $index]);
        }
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('queue_prefix');
        });
    }
};
