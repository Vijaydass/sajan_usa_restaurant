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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('branch_code');
            $table->string('equipment_name');
            $table->enum('payment_type', ['cash', 'credit', 'debit']);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'on_hold', 'awaiting_approval', 'scheduled', 'cancelled', 'completed', 'done'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
