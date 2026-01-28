<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Fix animals with birth dates in the future by adjusting them to the past
     */
    public function up(): void
    {
        // Get all animals with future birth dates
        $futureAnimals = DB::table('animals')
            ->where('tanggal_lahir', '>', now())
            ->get();

        if ($futureAnimals->count() > 0) {
            echo "\n=== Fixing {$futureAnimals->count()} animals with invalid birth dates ===\n";

            foreach ($futureAnimals as $animal) {
                $futureBirthDate = Carbon::parse($animal->tanggal_lahir);
                $yearsInFuture = now()->diffInYears($futureBirthDate, false);

                // Calculate corrected birth date (subtract the years to move to past)
                $correctedBirthDate = $futureBirthDate->subYears(abs($yearsInFuture) + 1);

                // Ensure it's at least 1 year old but not more than 10 years
                if ($correctedBirthDate->isFuture()) {
                    $correctedBirthDate = now()->subYears(2);
                } elseif ($correctedBirthDate->diffInYears(now()) > 10) {
                    $correctedBirthDate = now()->subYears(2);
                }

                DB::table('animals')
                    ->where('id', $animal->id)
                    ->update(['tanggal_lahir' => $correctedBirthDate]);

                echo "✅ Fixed {$animal->kode_hewan}: {$futureBirthDate->format('Y-m-d')} → {$correctedBirthDate->format('Y-m-d')}\n";
            }

            echo "\n=== All invalid birth dates have been fixed ===\n\n";
        } else {
            echo "\n✅ No animals with invalid birth dates found.\n\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this migration as we don't store original invalid dates
        echo "Cannot reverse birth date corrections.\n";
    }
};
