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
        Schema::create('payroll_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('period'); // e.g. "Oktober 2026"
            $table->integer('sessions_count')->default(0); // for FREELANCE model shooting sessions
            $table->decimal('basic_amount', 15, 2);
            $table->decimal('allowance', 15, 2)->default(0); // transport/makan
            $table->decimal('bonus', 15, 2)->default(0); // lembur/performance
            $table->decimal('deduction', 15, 2)->default(0); // kasbon/potongan
            $table->decimal('net_pay', 15, 2);
            $table->text('notes')->nullable();
            $table->enum('status', ['PENDING', 'PAID'])->default('PENDING');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_slips');
    }
};
