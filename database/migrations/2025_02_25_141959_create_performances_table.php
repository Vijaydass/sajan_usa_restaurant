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
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('weekly_metric_id');
            $table->decimal('sale', 10, 2);
            $table->decimal('growth', 10, 2);
            $table->string('speed_service');
            $table->text('complaints');
            $table->string('osat');
            $table->string('redbook');
            $table->timestamps();

            $table->foreign('weekly_metric_id')->references('id')->on('weekly_metrics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performances');
    }
};
