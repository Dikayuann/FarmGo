<?php

namespace App\Http\Controllers;

use App\Models\HeatDetection;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HeatDetectionController extends Controller
{
    /**
     * Display a listing of heat detections
     */
    public function index()
    {
        $user = Auth::user();

        $heatDetections = HeatDetection::with(['animal', 'perkawinan'])
            ->whereHas('animal', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('tanggal_deteksi', 'desc')
            ->paginate(20);

        return view('heat-detection.index', compact('heatDetections'));
    }

    /**
     * Show the form for creating a new heat detection
     */
    public function create()
    {
        $user = Auth::user();

        // Get only female animals (betina) that belong to user
        $betinas = Animal::where('user_id', $user->id)
            ->where('jenis_kelamin', 'betina')
            ->orderBy('nama_hewan')
            ->get();

        return view('heat-detection.create', compact('betinas'));
    }

    /**
     * Store a newly created heat detection
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'tanggal_deteksi' => 'required|date|before_or_equal:today',
            'gejala' => 'nullable|array',
            'gejala.*' => 'string',
            'catatan' => 'nullable|string|max:1000',
            'action' => 'required|in:save_only,breed_now',
        ]);

        // Verify animal belongs to user and is female
        $animal = Animal::findOrFail($validated['animal_id']);
        if ($animal->user_id !== $user->id) {
            return back()->withErrors(['error' => 'Hewan tidak ditemukan atau bukan milik Anda.']);
        }

        if ($animal->jenis_kelamin !== 'betina') {
            return back()->withErrors(['error' => 'Hanya hewan betina yang dapat dicatat birahi.']);
        }

        // Check breeding eligibility
        if (!$animal->isEligibleForBreeding()) {
            return back()->withErrors([
                'error' => 'Betina tidak eligible untuk birahi. ' . $animal->getBreedingStatusMessage()
            ]);
        }

        // Create heat detection record
        $heatDetection = HeatDetection::create([
            'animal_id' => $validated['animal_id'],
            'tanggal_deteksi' => $validated['tanggal_deteksi'],
            'gejala' => $validated['gejala'] ?? [],
            'catatan' => $validated['catatan'] ?? null,
            'status' => 'pending',
        ]);

        // Handle action
        if ($validated['action'] === 'breed_now') {
            // Redirect to reproduksi create with pre-filled data
            return redirect()->route('reproduksi.create', [
                'heat_detection_id' => $heatDetection->id,
                'betina_id' => $animal->id,
                'tanggal_birahi' => $validated['tanggal_deteksi'],
            ])->with('info', 'Silakan lengkapi data perkawinan');
        }

        return redirect()->route('reproduksi.index')
            ->with('success', 'Catatan birahi berhasil disimpan!');
    }

    /**
     * Display the specified heat detection
     */
    public function show(HeatDetection $heatDetection)
    {
        $user = Auth::user();

        // Verify access
        if ($heatDetection->animal->user_id !== $user->id) {
            abort(403);
        }

        return view('heat-detection.show', compact('heatDetection'));
    }

    /**
     * Remove the specified heat detection
     */
    public function destroy(HeatDetection $heatDetection)
    {
        $user = Auth::user();

        // Verify access
        if ($heatDetection->animal->user_id !== $user->id) {
            abort(403);
        }

        // Don't allow deletion if already bred
        if ($heatDetection->status === 'bred' && $heatDetection->perkawinan_id) {
            return back()->withErrors(['error' => 'Catatan birahi yang sudah dikawinkan tidak dapat dihapus.']);
        }

        $heatDetection->delete();

        return redirect()->route('reproduksi.index')
            ->with('success', 'Catatan birahi berhasil dihapus!');
    }
}
