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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->string('branch_code');
            $table->decimal('expected_deposit', 10, 2);
            $table->decimal('actual_deposit', 10, 2);
            $table->integer('shortage'); // Changed to integer for proper calculations
            $table->text('comments')->nullable();
            $table->text('deposit_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
