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
        Schema::create('udhari_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('udhari_id');
            $table->text('reminder_message')->nullable();
            $table->string('channel', 20)->nullable();
            $table->string('status', 20)->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('udhari_reminders');
    }
};
