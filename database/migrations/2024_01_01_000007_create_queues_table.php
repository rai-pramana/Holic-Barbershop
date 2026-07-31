<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('queue_number', 10);
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('barber_id')->nullable()->constrained('barbers')->onDelete('set null');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'active', 'called', 'completed', 'skipped', 'expired'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('estimated_start')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('called_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->index(['branch_id', 'status']);
            $table->index(['barber_id', 'status']);
            $table->index(['customer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
