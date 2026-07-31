<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add name & phone directly to barbers
        Schema::table('barbers', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('phone', 20)->nullable()->after('name');
            $table->string('user_id_old')->nullable(); // temp backup
        });

        // 2. Copy name & phone from related user accounts
        $barbers = DB::table('barbers')->get();
        foreach ($barbers as $barber) {
            if ($barber->user_id) {
                $user = DB::table('users')->where('id', $barber->user_id)->first();
                if ($user) {
                    DB::table('barbers')->where('id', $barber->id)->update([
                        'name'        => $user->name,
                        'phone'       => $user->phone ?? null,
                        'user_id_old' => $barber->user_id,
                    ]);
                }
            }
        }

        // 3. Delete barber user accounts (role = 'barber')
        DB::table('users')->where('role', 'barber')->delete();

        // 4. Make user_id nullable then drop it
        Schema::table('barbers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'user_id_old']);
        });
    }

    public function down(): void
    {
        Schema::table('barbers', function (Blueprint $table) {
            $table->dropColumn(['name', 'phone']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
    }
};
