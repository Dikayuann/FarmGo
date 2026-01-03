<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notifikasis', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id');
        });

        // Populate user_id from related perkawinan or animal
        DB::statement('
            UPDATE notifikasis n
            LEFT JOIN perkawinans p ON n.perkawinan_id = p.id
            LEFT JOIN animals a ON n.animal_id = a.id OR p.betina_id = a.id
            SET n.user_id = COALESCE(a.user_id, 1)
            WHERE n.user_id IS NULL
        ');

        // Now make it non-nullable and add constraints
        Schema::table('notifikasis', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifikasis', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['user_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
