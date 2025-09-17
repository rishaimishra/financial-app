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
        Schema::create('investment_records', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id');
            $table->string('name', 255);
            $table->enum('type', ['mutual_fund', 'fd', 'stock', 'gold', 'sip', 'other']);
            $table->decimal('amount_invested', 15, 2)->nullable();
            $table->decimal('current_value', 15, 2)->nullable();
            $table->string('account_number', 100)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('meta_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_records');
    }
};
