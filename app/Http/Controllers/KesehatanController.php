<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthRecord;
use App\Models\Animal;
use App\Http\Requests\StoreHealthRecordRequest;
use Illuminate\Support\Facades\Auth;

class KesehatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $animalId = $request->input('animal_id', 'all');
        $status = $request->input('status', 'all');
        $jenisPemeriksaan = $request->input('jenis_pemeriksaan', 'all');

        // Get health records for user's animals only
        $healthRecords = HealthRecord::whereHas('animal', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with('animal')
            ->search($search)
            ->byAnimal($animalId)
            ->byStatus($status)
            ->byJenisPemeriksaan($jenisPemeriksaan)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->paginate(10);

        // Get user's animals for filter dropdown
        $animals = Animal::where('user_id', Auth::id())
            ->orderBy('nama_hewan')
            ->get();

        // Stats for cards
        $stats = [
            'total' => HealthRecord::whereHas('animal', function ($query) {
                $query->where('user_id', Auth::id());
            })->count(),
            'sehat' => HealthRecord::whereHas('animal', function ($query) {
                $query->where('user_id', Auth::id());
            })->where('status_kesehatan', 'sehat')->count(),
            'sakit' => HealthRecord::whereHas('animal', function ($query) {
                $query->where('user_id', Auth::id());
            })->where('status_kesehatan', 'sakit')->count(),
            'bulan_ini' => HealthRecord::whereHas('animal', function ($query) {
                $query->where('user_id', Auth::id());
            })->whereMonth('tanggal_pemeriksaan', now()->month)
                ->whereYear('tanggal_pemeriksaan', now()->year)
                ->count(),
        ];

        return view('kesehatan.index', compact('healthRecords', 'animals', 'stats', 'search', 'animalId', 'status', 'jenisPemeriksaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get user's animals for dropdown
        $animals = Animal::where('user_id', Auth::id())
            ->orderBy('nama_hewan')
            ->get();

        $animalId = $request->query('animal_id');
        return view('kesehatan.create', compact('animals', 'animalId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHealthRecordRequest $request)
    {
        // Verify animal belongs to user
        $animal = Animal::where('id', $request->animal_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $data = $request->validated();
        $data['tanggal_pemeriksaan'] = \Carbon\Carbon::parse($request->tanggal_pemeriksaan)->format('Y-m-d H:i:s');

        HealthRecord::create($data);

        return redirect()->route('kesehatan.index')
            ->with('success', 'Catatan kesehatan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $healthRecord = HealthRecord::whereHas('animal', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('animal')->findOrFail($id);

        return view('kesehatan.show', compact('healthRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $healthRecord = HealthRecord::whereHas('animal', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('animal')->findOrFail($id);

        // Get user's animals for dropdown
        $animals = Animal::where('user_id', Auth::id())
            ->orderBy('nama_hewan')
            ->get();

        return view('kesehatan.edit', compact('healthRecord', 'animals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreHealthRecordRequest $request, string $id)
    {
        $healthRecord = HealthRecord::whereHas('animal', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        // Verify animal belongs to user
        $animal = Animal::where('id', $request->animal_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $healthRecord->update($request->validated());

        return redirect()->route('kesehatan.index')
            ->with('success', 'Catatan kesehatan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $healthRecord = HealthRecord::whereHas('animal', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        $healthRecord->delete();

        return redirect()->route('kesehatan.index')
            ->with('success', 'Catatan kesehatan berhasil dihapus!');
    }
}
