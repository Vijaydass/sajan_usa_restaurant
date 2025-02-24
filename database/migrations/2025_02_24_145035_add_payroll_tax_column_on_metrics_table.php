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
        Schema::table('weekly_metrics', function (Blueprint $table) {
            $table->string('branch_code')->after('id');
            $table->decimal('payroll_tax', 10, 2)->after('payrolls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weekly_metrics', function (Blueprint $table) {
            $table->dropColumn('payroll_tax');
            $table->dropColumn('branch_code');
        });
    }
};
