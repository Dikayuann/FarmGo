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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('animal_id')->nullable()->constrained('animals')->onDelete('cascade');
            $table->enum('event_type', ['vaccination', 'birth_estimate', 'health_checkup', 'custom']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->boolean('completed')->default(false);
            $table->boolean('reminder_sent')->default(false);
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'event_date']);
            $table->index('completed');
            $table->index('event_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
