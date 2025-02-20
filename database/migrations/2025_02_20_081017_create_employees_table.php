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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('branch_code');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('designation');
            $table->text('address');
            $table->string('ssn')->unique();
            $table->decimal('pay_rate', 10, 2);
            $table->date('dob');
            $table->string('routing_number');
            $table->string('account_number');
            $table->string('bank');
            $table->string('mobile');
            $table->date('start_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
