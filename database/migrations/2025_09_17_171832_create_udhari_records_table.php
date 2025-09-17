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
        Schema::create('udhari_records', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id');
            $table->string('contact_name', 100);
            $table->string('contact_phone', 15)->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('direction', ['lent', 'borrowed']);
            $table->enum('status', ['pending', 'partially_paid', 'paid'])->default('pending');
            $table->string('currency', 3)->default('INR');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('udhari_records');
    }
};
