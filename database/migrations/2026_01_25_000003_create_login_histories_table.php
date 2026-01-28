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
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45); // IPv4 & IPv6 support
            $table->text('user_agent');
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('device_name')->nullable(); // Windows, macOS, Android, iOS
            $table->string('browser')->nullable(); // Chrome, Firefox, Safari
            $table->string('platform')->nullable(); // Windows 10, macOS, Android
            $table->string('location')->nullable(); // Country/City if available
            $table->enum('login_status', ['success', 'failed'])->default('success');
            $table->timestamp('login_at');
            $table->timestamps();

            $table->index('user_id');
            $table->index('login_at');
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};
