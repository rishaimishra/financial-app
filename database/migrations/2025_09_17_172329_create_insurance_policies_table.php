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
        Schema::create('insurance_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id');
            $table->string('provider_name', 100);
            $table->enum('policy_type', ['health', 'term', 'life', 'motor', 'other']);
            $table->string('policy_number', 100);
            $table->decimal('premium_amount', 15, 2)->nullable();
            $table->string('premium_frequency', 20)->nullable();
            $table->decimal('sum_assured', 15, 2)->nullable();
            $table->date('maturity_date')->nullable();
            $table->json('meta_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_policies');
    }
};
