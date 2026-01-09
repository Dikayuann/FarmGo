<?php

namespace App\Http\Controllers;

use App\Models\Perkawinan;
use App\Models\Animal;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReproduksiController extends Controller
{
    /**
     * Display reproduction dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Build query with eager loading
        $query = Perkawinan::with(['jantan', 'betina', 'offspring'])
            ->byUser($user->id);

        // Search by animal code or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('jantan', function ($sq) use ($search) {
                    $sq->where('kode_hewan', 'like', "%{$search}%")
                        ->orWhere('nama_hewan', 'like', "%{$search}%");
                })->orWhereHas('betina', function ($sq) use ($search) {
                    $sq->where('kode_hewan', 'like', "%{$search}%")
                        ->orWhere('nama_hewan', 'like', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Get records with pagination
        $perkawinans = $query->latest()->paginate(15);

        // Calculate status counts
        $statusCounts = [
            'total' => Perkawinan::byUser($user->id)->count(),
            'menunggu' => Perkawinan::byUser($user->id)->where('status_reproduksi', 'menunggu')->count(),
            'bunting' => Perkawinan::byUser($user->id)->where('status_reproduksi', 'bunting')->count(),
            'melahirkan' => Perkawinan::byUser($user->id)->where('status_reproduksi', 'melahirkan')->count(),
            'gagal' => Perkawinan::byUser($user->id)->where('status_reproduksi', 'gagal')->count(),
        ];

        // Get upcoming reminders (next 14 days)
        $upcomingReminders = Perkawinan::byUser($user->id)
            ->upcomingReminders(14)
            ->with(['jantan', 'betina'])
            ->get();

        // Get pending heat detections (birahi yang belum dikawinkan)
        $pendingHeatDetections = \App\Models\HeatDetection::with('animal')
            ->whereHas('animal', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'pending')
            ->orderBy('tanggal_deteksi', 'desc')
            ->take(5)
            ->get();

        return view('reproduksi.index', compact('perkawinans', 'statusCounts', 'upcomingReminders', 'pendingHeatDetections'));
    }

    /**
     * Show the form for creating a new reproduction record
     */
    public function create()
    {
        $user = Auth::user();

        // Get all active animals for this user, grouped by gender
        $jantanList = Animal::where('user_id', $user->id)
            ->where('jenis_kelamin', 'jantan')
            ->orderBy('nama_hewan')
            ->get(['id', 'kode_hewan', 'nama_hewan', 'jenis_hewan']);

        // Get ALL betinas (don't filter by eligibility - show all with status)
        $betinaList = Animal::where('user_id', $user->id)
            ->where('jenis_kelamin', 'betina')
            ->orderBy('nama_hewan')
            ->get(['id', 'kode_hewan', 'nama_hewan', 'jenis_hewan']);

        // Add eligibility status to each betina
        $betinaList->each(function ($betina) {
            $betina->is_eligible = $betina->isEligibleForBreeding();
            $betina->status_message = $betina->getBreedingStatusMessage();
        });

        return view('reproduksi.create', compact('jantanList', 'betinaList'));
    }

    /**
     * Store a newly created reproduction record
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Base validation rules
        $rules = [
            'jantan_type' => 'required|in:owned,external,semen',
            'betina_id' => 'required|exists:animals,id',
            'tanggal_birahi' => 'nullable|date|before_or_equal:tanggal_perkawinan',
            'tanggal_perkawinan' => 'required|date',
            'metode_perkawinan' => 'required|in:alami,ib,et,ivf,moet',
            'catatan' => 'nullable|string|max:1000',
        ];

        // Conditional validation based on jantan_type
        if ($request->jantan_type === 'owned') {
            $rules['jantan_id'] = 'required|exists:animals,id|different:betina_id';
        } elseif ($request->jantan_type === 'external') {
            $rules['jantan_external_name'] = 'required|string|max:100';
            $rules['jantan_external_breed'] = 'required|string|max:100';
            $rules['jantan_external_owner'] = 'required|string|max:100';
            $rules['jantan_external_reg_number'] = 'nullable|string|max:50';
        } elseif ($request->jantan_type === 'semen') {
            $rules['semen_code'] = 'required|string|max:50';
            $rules['semen_producer'] = 'required|string|max:100';
            $rules['semen_breed'] = 'required|string|max:100';
            // Semen must use IB method
            $rules['metode_perkawinan'] = 'required|in:ib';
        }

        // IB-specific fields
        if ($request->metode_perkawinan === 'ib') {
            $rules['inseminator_name'] = 'nullable|string|max:100';
            $rules['ib_time'] = 'nullable|in:pagi,siang,sore';
            $rules['straw_count'] = 'nullable|integer|min:1';
        }

        $validated = $request->validate($rules);

        // Verify betina belongs to user
        $betina = Animal::findOrFail($validated['betina_id']);
        if ($betina->user_id !== $user->id) {
            return back()->withErrors(['error' => 'Hewan betina tidak ditemukan atau bukan milik Anda.']);
        }

        // If jantan_type is owned, verify jantan as well
        if ($request->jantan_type === 'owned') {
            $jantan = Animal::findOrFail($validated['jantan_id']);
            if ($jantan->user_id !== $user->id) {
                return back()->withErrors(['error' => 'Hewan jantan tidak ditemukan atau bukan milik Anda.']);
            }
            if ($jantan->jenis_kelamin !== 'jantan') {
                return back()->withErrors(['error' => 'Hewan jantan harus berjenis kelamin jantan.']);
            }
        }

        // Verify betina gender
        if ($betina->jenis_kelamin !== 'betina') {
            return back()->withErrors(['error' => 'Hewan betina harus berjenis kelamin betina.']);
        }

        DB::beginTransaction();
        try {
            // Check if user can add more reproduction records (quota limit for trial)
            if (!$user->canAddReproduction()) {
                return back()->withErrors([
                    'error' => 'Anda telah mencapai batas maksimal 5 catatan reproduksi untuk akun Trial. Upgrade ke Premium untuk tracking unlimited!'
                ])->with('show_upgrade_modal', true);
            }

            // Calculate gestation period and reminder date
            $gestationPeriod = Perkawinan::getGestationPeriod($betina->jenis_hewan);
            $heatCycleInterval = Perkawinan::getHeatCycleInterval();

            $tanggalPerkawinan = Carbon::parse($validated['tanggal_perkawinan']);
            $estimasiKelahiran = $tanggalPerkawinan->copy()->addDays($gestationPeriod);
            $reminderBirahi = $tanggalPerkawinan->copy()->addDays($heatCycleInterval);

            // Prepare data for creation
            $data = [
                'jantan_type' => $validated['jantan_type'],
                'betina_id' => $validated['betina_id'],
                'tanggal_birahi' => $validated['tanggal_birahi'] ?? null,
                'tanggal_perkawinan' => $validated['tanggal_perkawinan'],
                'metode_perkawinan' => $validated['metode_perkawinan'],
                'status_reproduksi' => 'menunggu',
                'estimasi_kelahiran' => $estimasiKelahiran,
                'reminder_birahi_berikutnya' => $reminderBirahi,
                'reminder_status' => 'aktif',
                'catatan' => $validated['catatan'] ?? null,
            ];

            // Add jantan-specific fields based on type
            if ($request->jantan_type === 'owned') {
                $data['jantan_id'] = $validated['jantan_id'];
            } elseif ($request->jantan_type === 'external') {
                $data['jantan_external_name'] = $validated['jantan_external_name'];
                $data['jantan_external_breed'] = $validated['jantan_external_breed'];
                $data['jantan_external_owner'] = $validated['jantan_external_owner'];
                $data['jantan_external_reg_number'] = $validated['jantan_external_reg_number'] ?? null;
            } elseif ($request->jantan_type === 'semen') {
                $data['semen_code'] = $validated['semen_code'];
                $data['semen_producer'] = $validated['semen_producer'];
                $data['semen_breed'] = $validated['semen_breed'];
            }

            // Add IB-specific fields if applicable
            if ($request->metode_perkawinan === 'ib') {
                $data['inseminator_name'] = $validated['inseminator_name'] ?? null;
                $data['ib_time'] = $validated['ib_time'] ?? null;
                $data['straw_count'] = $validated['straw_count'] ?? null;
            }

            // Generate unique kode_perkawinan
            // Format: P-YYYYMM-USERID-SEQUENCE (e.g., P-202512-2-001)
            $yearMonth = date('Ym');
            $userId = $user->id;
            $prefix = "P-{$yearMonth}-{$userId}";

            $lastPerkawinan = Perkawinan::where('user_id', $userId)
                ->where('kode_perkawinan', 'like', "{$prefix}-%")
                ->orderByRaw('CAST(SUBSTRING_INDEX(kode_perkawinan, "-", -1) AS UNSIGNED) DESC')
                ->first();

            $number = 1;
            if ($lastPerkawinan) {
                $parts = explode('-', $lastPerkawinan->kode_perkawinan);
                $number = (int) end($parts) + 1;
            }

            $kodePerkawinan = "{$prefix}-" . str_pad($number, 3, '0', STR_PAD_LEFT);

            // Create perkawinan record
            $perkawinan = Perkawinan::create(array_merge(['kode_perkawinan' => $kodePerkawinan], $data));

            // Create notification reminder
            Notifikasi::create([
                'perkawinan_id' => $perkawinan->id,
                'animal_id' => $betina->id,
                'jenis_notifikasi' => 'reproduksi',
                'pesan' => "Pemeriksaan birahi berikutnya untuk {$betina->kode_hewan} - {$betina->nama_hewan}",
                'tanggal_kirim' => $reminderBirahi,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('reproduksi.index')
                ->with('success', 'Catatan reproduksi berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified reproduction record
     */
    public function show($id)
    {
        $user = Auth::user();

        $perkawinan = Perkawinan::with(['jantan', 'betina', 'offspring', 'notifikasis'])
            ->findOrFail($id);

        // Verify user owns this record (check betina only since jantan might be external/semen)
        if ($perkawinan->betina->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('reproduksi.show', compact('perkawinan'));
    }

    /**
     * Show the form for editing the specified reproduction record
     */
    public function edit($id)
    {
        $user = Auth::user();

        $perkawinan = Perkawinan::with(['jantan', 'betina'])
            ->findOrFail($id);

        // Verify ownership - check betina only since jantan might be external/semen
        if ($perkawinan->betina->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('reproduksi.edit', compact('perkawinan'));
    }

    /**
     * Update the specified reproduction record
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $perkawinan = Perkawinan::findOrFail($id);

        // Verify ownership - check betina only since jantan might be external/semen
        if ($perkawinan->betina->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Validate input
        $validated = $request->validate([
            'status_reproduksi' => 'required|in:menunggu,bunting,melahirkan,gagal',
            'tanggal_melahirkan' => 'nullable|required_if:status_reproduksi,melahirkan|date',
            'jumlah_anak' => 'nullable|required_if:status_reproduksi,melahirkan|integer|min:0',
            'catatan' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Update basic fields
            $perkawinan->status_reproduksi = $validated['status_reproduksi'];
            $perkawinan->catatan = $validated['catatan'] ?? $perkawinan->catatan;

            // If status is melahirkan or gagal, mark reminder as selesai
            if (in_array($validated['status_reproduksi'], ['melahirkan', 'gagal'])) {
                $perkawinan->reminder_status = 'selesai';

                // Update related notifications
                Notifikasi::where('perkawinan_id', $perkawinan->id)
                    ->update(['status' => 'dibaca']);
            }

            // If status is melahirkan, save birth details
            if ($validated['status_reproduksi'] === 'melahirkan') {
                $perkawinan->tanggal_melahirkan = $validated['tanggal_melahirkan'];
                $perkawinan->jumlah_anak = $validated['jumlah_anak'] ?? 0;
            }

            $perkawinan->save();

            DB::commit();

            return redirect()->route('reproduksi.index')
                ->with('success', 'Catatan reproduksi berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified reproduction record
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $perkawinan = Perkawinan::with('offspring')->findOrFail($id);

        // Verify ownership - check betina only since jantan might be external/semen
        if ($perkawinan->betina->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        DB::beginTransaction();
        try {
            // Orphan any offspring (set perkawinan_id to null)
            if ($perkawinan->offspring->count() > 0) {
                Animal::where('perkawinan_id', $id)->update(['perkawinan_id' => null]);
            }

            // Delete related notifications
            Notifikasi::where('perkawinan_id', $id)->delete();

            // Delete the perkawinan record
            $perkawinan->delete();

            DB::commit();

            return redirect()->route('reproduksi.index')
                ->with('success', 'Catatan reproduksi berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Show form to add offspring from a mating record
     */
    public function addOffspring($perkawinanId)
    {
        $user = Auth::user();

        $perkawinan = Perkawinan::with(['jantan', 'betina'])
            ->findOrFail($perkawinanId);

        // Verify ownership - check betina only since jantan might be external/semen
        if ($perkawinan->betina->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Verify status is melahirkan
        if ($perkawinan->status_reproduksi !== 'melahirkan') {
            return back()->withErrors(['error' => 'Hanya bisa menambah anak dari catatan dengan status "Melahirkan".']);
        }

        // Check offspring count limit
        $currentOffspringCount = $perkawinan->offspring()->count();
        $declaredCount = $perkawinan->jumlah_anak ?? 0;

        if ($currentOffspringCount >= $declaredCount) {
            return back()->withErrors([
                'error' => "Jumlah anak sudah mencapai batas ({$declaredCount} ekor). Tidak bisa menambah lagi. Silakan update jumlah anak di catatan perkawinan jika diperlukan."
            ]);
        }

        // Generate next kode_hewan with proper prefix (match TernakController format)
        // Format: PREFIX-USERID-SEQUENCE (e.g., SA-2-001)
        $jenisHewan = $perkawinan->betina->jenis_hewan;
        $prefixMap = [
            'sapi' => 'SA',
            'kambing' => 'K',
            'domba' => 'D',
        ];
        $prefix = $prefixMap[$jenisHewan] ?? 'X';
        $userId = $user->id;

        // Find last animal with same prefix and user_id
        $lastAnimal = Animal::where('user_id', $userId)
            ->where('kode_hewan', 'like', "{$prefix}-{$userId}-%")
            ->orderByRaw('CAST(SUBSTRING_INDEX(kode_hewan, "-", -1) AS UNSIGNED) DESC')
            ->first();

        $number = 1;
        if ($lastAnimal) {
            $parts = explode('-', $lastAnimal->kode_hewan);
            $number = (int) end($parts) + 1;
        }

        $suggestedKode = "{$prefix}-{$userId}-" . str_pad($number, 3, '0', STR_PAD_LEFT);

        return view('reproduksi.modals.add-offspring', compact('perkawinan', 'suggestedKode'));
    }

    /**
     * Store offspring from a mating record
     */
    public function storeOffspring(Request $request, $perkawinanId)
    {
        $user = Auth::user();

        $perkawinan = Perkawinan::findOrFail($perkawinanId);

        // Verify ownership - check betina only since jantan might be external/semen
        if ($perkawinan->betina->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Validate input
        $validated = $request->validate([
            'kode_hewan' => 'required|string|max:50',
            'nama_hewan' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'tanggal_lahir' => 'required|date',
            'berat_badan' => 'required|numeric|min:0',
        ]);

        // Check if kode_hewan already exists, if yes, auto-generate new one
        $kodeHewan = $validated['kode_hewan'];
        $existingAnimal = Animal::where('user_id', $user->id)
            ->where('kode_hewan', $kodeHewan)
            ->first();

        if ($existingAnimal) {
            // Auto-generate unique kode with proper prefix (match TernakController format)
            // Format: PREFIX-USERID-SEQUENCE (e.g., SA-2-001)
            $jenisHewan = $perkawinan->betina->jenis_hewan;
            $prefixMap = [
                'sapi' => 'SA',
                'kambing' => 'K',
                'domba' => 'D',
            ];
            $prefix = $prefixMap[$jenisHewan] ?? 'X';
            $userId = $user->id;

            // Find last animal with same prefix and user_id
            $lastAnimal = Animal::where('user_id', $userId)
                ->where('kode_hewan', 'like', "{$prefix}-{$userId}-%")
                ->orderByRaw('CAST(SUBSTRING_INDEX(kode_hewan, "-", -1) AS UNSIGNED) DESC')
                ->first();

            $number = 1;
            if ($lastAnimal) {
                $parts = explode('-', $lastAnimal->kode_hewan);
                $number = (int) end($parts) + 1;
            }

            $kodeHewan = "{$prefix}-{$userId}-" . str_pad($number, 3, '0', STR_PAD_LEFT);
        }

        DB::beginTransaction();
        try {
            // Inherit breed from parents (use betina's breed)
            $animal = Animal::create([
                'kode_hewan' => $kodeHewan,
                'nama_hewan' => $validated['nama_hewan'],
                'jenis_hewan' => $perkawinan->betina->jenis_hewan,
                'ras_hewan' => $perkawinan->betina->ras_hewan,
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'berat_badan' => $validated['berat_badan'],
                'status_ternak' => 'perkawinan',
                'user_id' => $user->id,
                'perkawinan_id' => $perkawinanId,
            ]);

            // Generate QR Code for the offspring
            $this->generateQRCode($animal);

            // Auto-increment jumlah_anak if not manually set
            if (!$perkawinan->jumlah_anak || $perkawinan->jumlah_anak == 0) {
                $perkawinan->jumlah_anak = 1;
            } else {
                $perkawinan->jumlah_anak += 1;
            }
            $perkawinan->save();

            DB::commit();

            return redirect()->route('ternak.show', $animal->id)
                ->with('success', 'Anak berhasil ditambahkan ke manajemen ternak!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.']);
        }
    }

    /**
     * Generate QR Code for animal
     */
    private function generateQRCode(Animal $animal)
    {
        // Create QR code directory if it doesn't exist
        $qrPath = public_path('storage/qrcodes');
        if (!file_exists($qrPath)) {
            mkdir($qrPath, 0755, true);
        }

        // Generate QR code URL - points to animal detail page
        $url = route('ternak.show', $animal->id);

        // Generate QR code image
        $fileName = 'qr_' . $animal->kode_hewan . '.svg';
        $filePath = $qrPath . '/' . $fileName;

        QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($url, $filePath);

        // Update animal with QR URL
        $animal->update([
            'qr_url' => asset('storage/qrcodes/' . $fileName)
        ]);
    }
}
