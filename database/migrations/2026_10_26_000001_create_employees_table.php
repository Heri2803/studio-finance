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
            $table->string('name');
            $table->string('role'); // e.g. Video Content Creator, Model Konten TikTok, Ads Specialist
            $table->enum('employment_type', ['FULLTIME', 'FREELANCE', 'RETAINER']);
            $table->decimal('base_salary_or_rate', 15, 2);
            $table->string('bank_name')->default('BCA');
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('location')->default('KEMANG'); // KEMANG, SURABAYA, REMOTE
            $table->boolean('is_active')->default(true);
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
