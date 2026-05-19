<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Deletes reminders if user is deleted
            $table->text('description')->nullable();
            $table->date('next_occurrence'); // The next computed occurrence
            $table->enum('recurrence_type', ['none', 'monthly', 'yearly'])->default('none');
            $table->timestamps();
        });

        Schema::create('calendar_event_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_event_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_event_products');
        Schema::dropIfExists('calendar_events');
    }
};
