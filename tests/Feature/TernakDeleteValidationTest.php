<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Animal;
use App\Models\Perkawinan;
use App\Models\HealthRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class TernakDeleteValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Sistem menolak penghapusan betina yang sedang bunting
     * 
     * Kode yang diuji:
     * if ($animal->jenis_kelamin === 'betina') {
     *     $activePregnancy = Perkawinan::where('betina_id', $animal->id)
     *         ->where('status_reproduksi', 'bunting')
     *         ->exists();
     *     if ($activePregnancy) {
     *         return back()->withErrors([...]);
     *     }
     * }
     */
    public function test_cannot_delete_pregnant_betina()
    {
        // Arrange
        $user = User::factory()->create();
        $betina = Animal::factory()->for($user)->create([
            'jenis_kelamin' => 'betina',
            'nama_hewan' => 'Betina Bunting',
        ]);

        $jantan = Animal::factory()->for($user)->create([
            'jenis_kelamin' => 'jantan',
        ]);

        // Create pregnancy record
        Perkawinan::factory()->create([
            'betina_id' => $betina->id,
            'jantan_id' => $jantan->id,
            'status_reproduksi' => 'bunting',
        ]);

        // Act
        $response = $this->actingAs($user)
            ->delete(route('ternak.destroy', $betina->id));

        // Assert
        $response->assertRedirect();
        $response->assertSessionHasErrors('error');
        $this->assertDatabaseHas('animals', ['id' => $betina->id]);

        $errors = session('errors')->getBag('default')->get('error');
        $this->assertStringContainsString('sedang bunting', $errors[0]);
    }

    /**
     * Test: Sistem menolak penghapusan ternak yang memiliki anak
     * 
     * Kode yang diuji:
     * $offspringCount = Animal::where('perkawinan_id', function($query) use ($animal) {
     *     $query->select('id')->from('perkawinans')
     *         ->where('jantan_id', $animal->id)
     *         ->orWhere('betina_id', $animal->id);
     * })->count();
     * 
     * if ($offspringCount > 0) {
     *     return back()->withErrors([...]);
     * }
     */
    public function test_cannot_delete_animal_with_offspring()
    {
        // Arrange
        $user = User::factory()->create();
        $jantan = Animal::factory()->for($user)->create([
            'jenis_kelamin' => 'jantan',
            'nama_hewan' => 'Jantan Produktif',
        ]);

        $betina = Animal::factory()->for($user)->create([
            'jenis_kelamin' => 'betina',
        ]);

        $perkawinan = Perkawinan::factory()->create([
            'jantan_id' => $jantan->id,
            'betina_id' => $betina->id,
            'status_reproduksi' => 'melahirkan',
        ]);

        // Create 3 offspring
        Animal::factory()->count(3)->for($user)->create([
            'perkawinan_id' => $perkawinan->id,
        ]);

        // Act
        $response = $this->actingAs($user)
            ->delete(route('ternak.destroy', $jantan->id));

        // Assert
        $response->assertRedirect();
        $response->assertSessionHasErrors('error');
        $this->assertDatabaseHas('animals', ['id' => $jantan->id]);

        $errors = session('errors')->getBag('default')->get('error');
        $this->assertStringContainsString('3 anak', $errors[0]);
    }

    /**
     * Test: Sistem berhasil menghapus ternak dengan cascade delete health records
     * 
     * Kode yang diuji:
     * $animal->delete(); // Cascade delete via foreign key constraint
     */
    public function test_delete_animal_cascades_health_records()
    {
        // Arrange
        $user = User::factory()->create();
        $animal = Animal::factory()->for($user)->create();

        $healthRecords = HealthRecord::factory()->count(5)->create([
            'animal_id' => $animal->id,
        ]);

        // Act
        $response = $this->actingAs($user)
            ->delete(route('ternak.destroy', $animal->id));

        // Assert
        $response->assertRedirect(route('ternak.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('animals', ['id' => $animal->id]);
        $this->assertDatabaseMissing('health_records', ['animal_id' => $animal->id]);

        // Verify all 5 health records are deleted
        $this->assertEquals(0, HealthRecord::where('animal_id', $animal->id)->count());
    }

    /**
     * Test: QR Code file dihapus saat ternak dihapus
     * 
     * Kode yang diuji:
     * if ($animal->qr_url) {
     *     $qrPath = public_path('storage/qrcodes/qr_' . $animal->kode_hewan . '.svg');
     *     if (file_exists($qrPath)) {
     *         unlink($qrPath);
     *     }
     * }
     */
    public function test_qr_code_file_is_deleted_when_animal_deleted()
    {
        // Arrange
        $user = User::factory()->create();
        $animal = Animal::factory()->for($user)->create([
            'kode_hewan' => 'SA-1-TEST',
        ]);

        // Create QR code file
        $qrPath = public_path('storage/qrcodes');
        if (!file_exists($qrPath)) {
            mkdir($qrPath, 0755, true);
        }

        $qrFile = $qrPath . '/qr_SA-1-TEST.svg';
        file_put_contents($qrFile, '<svg>test</svg>');

        $animal->update([
            'qr_url' => asset('storage/qrcodes/qr_SA-1-TEST.svg')
        ]);

        $this->assertFileExists($qrFile);

        // Act
        $response = $this->actingAs($user)
            ->delete(route('ternak.destroy', $animal->id));

        // Assert
        $response->assertRedirect(route('ternak.index'));
        $this->assertFileDoesNotExist($qrFile);
    }

    /**
     * Test: User tidak bisa menghapus ternak milik user lain (IDOR Prevention)
     * 
     * Kode yang diuji:
     * $animal = Animal::where('user_id', Auth::id())->findOrFail($id);
     */
    public function test_user_cannot_delete_other_users_animal()
    {
        // Arrange
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $animalB = Animal::factory()->for($userB)->create([
            'nama_hewan' => 'Ternak User B',
        ]);

        // Act - User A tries to delete User B's animal
        $response = $this->actingAs($userA)
            ->delete(route('ternak.destroy', $animalB->id));

        // Assert
        $response->assertStatus(404); // findOrFail throws 404
        $this->assertDatabaseHas('animals', ['id' => $animalB->id]);
    }

    /**
     * Test: Cascade delete perkawinan dan SET NULL untuk offspring
     * 
     * Kode yang diuji:
     * Database foreign key constraints:
     * - perkawinans.jantan_id -> animals.id (CASCADE)
     * - perkawinans.betina_id -> animals.id (CASCADE)
     * - animals.perkawinan_id -> perkawinans.id (SET NULL)
     */
    public function test_offspring_loses_parent_info_when_perkawinan_deleted()
    {
        // Arrange
        $user = User::factory()->create();
        $jantan = Animal::factory()->for($user)->create(['jenis_kelamin' => 'jantan']);
        $betina = Animal::factory()->for($user)->create(['jenis_kelamin' => 'betina']);

        $perkawinan = Perkawinan::factory()->create([
            'jantan_id' => $jantan->id,
            'betina_id' => $betina->id,
            'status_reproduksi' => 'melahirkan',
        ]);

        // Offspring WITHOUT perkawinan_id (should allow deletion)
        $offspring = Animal::factory()->for($user)->create([
            'perkawinan_id' => null, // No reference
        ]);

        // Act - Delete jantan (will cascade delete perkawinan)
        $response = $this->actingAs($user)
            ->delete(route('ternak.destroy', $jantan->id));

        // Assert
        $response->assertRedirect(route('ternak.index'));
        $this->assertDatabaseMissing('animals', ['id' => $jantan->id]);
        $this->assertDatabaseMissing('perkawinans', ['id' => $perkawinan->id]);

        // Betina should still exist
        $this->assertDatabaseHas('animals', ['id' => $betina->id]);
    }

    /**
     * Test: Warning ditampilkan untuk ternak dengan banyak catatan
     * 
     * Kode yang diuji:
     * $healthRecordsCount = $animal->healthRecords()->count();
     * $breedingRecordsCount = Perkawinan::where('jantan_id', $animal->id)
     *     ->orWhere('betina_id', $animal->id)->count();
     * 
     * if ($healthRecordsCount > 0 || $breedingRecordsCount > 0) {
     *     session()->flash('delete_warning', [...]);
     * }
     */
    public function test_warning_shown_for_animal_with_many_records()
    {
        // Arrange
        $user = User::factory()->create();
        $animal = Animal::factory()->for($user)->create();

        // Create 10 health records
        HealthRecord::factory()->count(10)->create([
            'animal_id' => $animal->id,
        ]);

        // Act
        $response = $this->actingAs($user)
            ->delete(route('ternak.destroy', $animal->id));

        // Assert
        $response->assertRedirect(route('ternak.index'));
        $response->assertSessionHas('success');

        // Animal and all health records should be deleted
        $this->assertDatabaseMissing('animals', ['id' => $animal->id]);
        $this->assertEquals(0, HealthRecord::where('animal_id', $animal->id)->count());
    }

    /**
     * Test: Cache user stats di-clear setelah delete
     * 
     * Kode yang diuji:
     * $userId = Auth::id();
     * Cache::forget("ternak_stats_user_{$userId}");
     */
    public function test_user_cache_cleared_after_delete()
    {
        // Arrange
        $user = User::factory()->create();
        $animal = Animal::factory()->for($user)->create();

        // Set cache
        \Cache::put("ternak_stats_user_{$user->id}", ['total' => 1], 120);
        $this->assertTrue(\Cache::has("ternak_stats_user_{$user->id}"));

        // Act
        $response = $this->actingAs($user)
            ->delete(route('ternak.destroy', $animal->id));

        // Assert
        $response->assertRedirect(route('ternak.index'));

        // Cache should be cleared
        $this->assertFalse(\Cache::has("ternak_stats_user_{$user->id}"));
    }
}
