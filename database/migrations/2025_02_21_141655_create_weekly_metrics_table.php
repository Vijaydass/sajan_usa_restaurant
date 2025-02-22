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
        Schema::create('weekly_metrics', function (Blueprint $table) {
            $table->id();
            $table->date('week_start');
            $table->date('week_end');
            $table->decimal('ndcp', 10, 2);
            $table->decimal('cml', 10, 2);
            $table->decimal('payrolls', 10, 2);
            $table->decimal('last_year_sale', 10, 2);
            $table->decimal('current_year_sale', 10, 2);
            $table->decimal('growth', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_metrics');
    }
};
