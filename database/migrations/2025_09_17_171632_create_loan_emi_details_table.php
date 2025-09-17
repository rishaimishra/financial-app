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
        Schema::create('loan_emi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id');
            $table->string('lender_name', 100);
            $table->decimal('loan_amount', 15, 2)->nullable();
            $table->decimal('emi_amount', 15, 2);
            $table->decimal('outstanding_balance', 15, 2)->nullable();
            $table->unsignedTinyInteger('emi_day');
            $table->boolean('is_auto_detected')->nullable();
            $table->string('account_number', 50)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_emi_details');
    }
};
