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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('wk1_hrs', 5, 2)->default(0);
            $table->decimal('wk2_hrs', 5, 2)->default(0);
            $table->decimal('ot_wk1_hrs', 5, 2)->default(0);
            $table->decimal('ot_wk2_hrs', 5, 2)->default(0);
            $table->decimal('total_hrs', 6, 2)->default(0);
            $table->decimal('total_ot_hrs', 6, 2)->default(0);
            $table->decimal('pay_rate', 8, 2);
            $table->decimal('ot_rate', 8, 2);
            $table->decimal('total_pay', 10, 2);
            $table->timestamps();

            // Prevent multiple entries for the same user within the same week
            $table->unique(['employee_id', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
