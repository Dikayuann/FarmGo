<?php

namespace App\Http\Controllers;

use App\Models\HeatDetection;
use App\Models\Animal;
use App\Models\CalendarEvent;
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

        // Prevent duplicate submissions (check for identical record within last 5 seconds)
        $recentDuplicate = HeatDetection::where('animal_id', $validated['animal_id'])
            ->where('tanggal_deteksi', $validated['tanggal_deteksi'])
            ->where('created_at', '>=', now()->subSeconds(5))
            ->first();

        if ($recentDuplicate) {
            // Duplicate detected - redirect based on action
            if ($validated['action'] === 'breed_now') {
                return redirect()->route('reproduksi.create', [
                    'betina_id' => $animal->id,
                    'tanggal_birahi' => $validated['tanggal_deteksi']
                ])->with('success', 'Deteksi birahi berhasil dicatat!');
            }
            return redirect()->route('heat-detection.index')
                ->with('success', 'Deteksi birahi berhasil dicatat!');
        }

        // Create heat detection record
        $heatDetection = HeatDetection::create([
            'animal_id' => $validated['animal_id'],
            'tanggal_deteksi' => $validated['tanggal_deteksi'],
            'gejala' => $validated['gejala'] ?? [],
            'catatan' => $validated['catatan'] ?? null,
            'status' => 'pending',
        ]);

        // Auto-create calendar event for optimal breeding time
        // Optimal time is 12-24 hours after heat detection, we'll use 18 hours as middle point
        $optimalBreedingTime = Carbon::parse($validated['tanggal_deteksi'])->addHours(18);

        CalendarEvent::create([
            'user_id' => $user->id,
            'animal_id' => $animal->id,
            'event_type' => CalendarEvent::TYPE_HEAT_DETECTION,
            'title' => "Waktu Kawin Optimal - {$animal->nama_hewan}",
            'description' => "Ternak {$animal->kode_hewan} ({$animal->nama_hewan}) sedang birahi. Waktu optimal untuk kawin adalah 12-24 jam setelah deteksi birahi.",
            'event_date' => $optimalBreedingTime,
            'completed' => false,
            'reminder_sent' => false,
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
            ->with('success', 'Catatan birahi berhasil disimpan! Event "Waktu Kawin Optimal" telah ditambahkan ke kalender.');
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

        // Delete related calendar events
        CalendarEvent::where('animal_id', $heatDetection->animal_id)
            ->where('event_type', CalendarEvent::TYPE_HEAT_DETECTION)
            ->where('event_date', '>=', Carbon::parse($heatDetection->tanggal_deteksi))
            ->where('event_date', '<=', Carbon::parse($heatDetection->tanggal_deteksi)->addDays(2))
            ->delete();

        $heatDetection->delete();

        return redirect()->route('reproduksi.index')
            ->with('success', 'Catatan birahi dan event terkait berhasil dihapus!');
    }
}
