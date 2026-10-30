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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['INCOME', 'EXPENSE']);
            $table->string('category'); // PROJECT_DP, PROJECT_SETTLEMENT, CLIENT_RETAINER, PAYROLL, AD_SPEND, STUDIO_KEMANG, PROPS_GEAR, SUBSCRIPTION, OTHER
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->string('description');
            $table->string('location')->default('KEMANG'); // KEMANG, SURABAYA, ONLINE
            $table->foreignId('payroll_slip_id')->nullable()->constrained('payroll_slips')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
