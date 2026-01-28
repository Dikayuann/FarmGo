<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\HealthRecord;
use App\Models\Perkawinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // 1. Total Ternak milik user ini
        $totalTernak = Animal::where('user_id', $user->id)->count();

        // 2. Ternak Hasil Perkawinan
        $ternakPerkawinan = Animal::where('user_id', $user->id)
            ->where('status_ternak', 'perkawinan')
            ->count();

        // 3. Status Peternakan (berdasarkan total ternak dan reproduksi)
        if ($totalTernak === 0) {
            $statusPeternakan = '-';
        } elseif ($totalTernak >= 20) {
            $statusPeternakan = 'Berkembang';
        } elseif ($totalTernak >= 10) {
            $statusPeternakan = 'Baik';
        } else {
            $statusPeternakan = 'Mulai Berkembang';
        }

        // 4. Data Populasi untuk Chart (6 bulan terakhir)
        $populationData = $this->getPopulationData($user->id);
        $monthLabels = $this->getMonthLabels();

        // 5. Tugas Kesehatan Mendatang (health records dengan pemeriksaan_berikutnya di masa depan)
        $tugasKesehatan = HealthRecord::whereHas('animal', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereNotNull('pemeriksaan_berikutnya')
            ->where('pemeriksaan_berikutnya', '>=', Carbon::today())
            ->orderBy('pemeriksaan_berikutnya', 'asc')
            ->limit(5)
            ->with('animal') // Eager loading untuk menghindari N+1 query
            ->get();

        // 6. Reproduksi Mendatang (upcoming reminders in next 14 days)
        $reproduksiMendatang = Perkawinan::byUser($user->id)
            ->upcomingReminders(14)
            ->count();

        // 7. Data Reproduksi untuk Chart (success rate per month)
        $reproductionData = $this->getReproductionSuccessRate($user->id);

        // 8. Calendar: Generate auto-events if needed
        $this->generateAutoEvents($user->id);

        // 9. Calendar: Get upcoming events
        $upcomingEvents = \App\Models\CalendarEvent::where('user_id', $user->id)
            ->upcoming(30)
            ->with('animal')
            ->get();

        // 10. Calendar: Get calendar data for current month
        $calendarData = $this->getCalendarDataForView($user->id);

        // 11. AI Health Analytics
        $aiAnalyzer = new \App\Services\AiHealthAnalyzer();
        $highRiskAnimals = $aiAnalyzer->getHighRiskAnimals($user->id, 5);
        $farmHealthAnalysis = $aiAnalyzer->analyzeFarmHealth($user->id);

        // Kirim semua data ke view
        return view('dashboard', compact(
            'totalTernak',
            'ternakPerkawinan',
            'statusPeternakan',
            'populationData',
            'monthLabels',
            'reproductionData',
            'tugasKesehatan',
            'reproduksiMendatang',
            'upcomingEvents',
            'calendarData',
            'highRiskAnimals',
            'farmHealthAnalysis'
        ));
    }

    /**
     * Get reproduction success rate for the last 5 months
     */
    private function getReproductionSuccessRate($userId)
    {
        $data = [];

        for ($i = 4; $i >= 0; $i--) {
            $startDate = Carbon::now()->subMonths($i)->startOfMonth();
            $endDate = Carbon::now()->subMonths($i)->endOfMonth();

            // Count total matings in this month
            $totalMatings = Perkawinan::byUser($userId)
                ->whereBetween('tanggal_perkawinan', [$startDate, $endDate])
                ->count();

            // Count successful births
            $successfulBirths = Perkawinan::byUser($userId)
                ->where('status_reproduksi', 'melahirkan')
                ->whereBetween('tanggal_perkawinan', [$startDate, $endDate])
                ->count();

            // Calculate success rate as percentage
            $successRate = $totalMatings > 0 ? round(($successfulBirths / $totalMatings) * 100) : 0;
            $data[] = $successRate;
        }

        return $data;
    }

    /**
     * Ambil data populasi 6 bulan terakhir
     */
    private function getPopulationData($userId)
    {
        $data = [];

        // Loop 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i)->endOfMonth();

            // Hitung jumlah ternak yang created_at <= akhir bulan tersebut
            // Menggunakan created_at lebih akurat daripada tanggal_lahir
            $count = Animal::where('user_id', $userId)
                ->where('created_at', '<=', $date)
                ->count();

            $data[] = $count;
        }

        return $data;
    }

    /**
     * Generate label bulan untuk 6 bulan terakhir
     */
    private function getMonthLabels()
    {
        $labels = [];

        // Loop 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            // Format: Jan, Feb, Mar, dst
            $labels[] = $date->translatedFormat('M');
        }

        return $labels;
    }

    /**
     * Generate auto calendar events from existing data
     */
    private function generateAutoEvents($userId)
    {
        // Only generate if no events exist for this user (first time)
        $existingEvents = \App\Models\CalendarEvent::where('user_id', $userId)->count();

        if ($existingEvents > 0) {
            return; // Already has events
        }

        // 1. Generate vaccination reminders from health records
        $healthRecords = HealthRecord::whereHas('animal', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereNotNull('pemeriksaan_berikutnya')
            ->where('pemeriksaan_berikutnya', '>=', now())
            ->get();

        foreach ($healthRecords as $record) {
            \App\Models\CalendarEvent::create([
                'user_id' => $userId,
                'animal_id' => $record->animal_id,
                'event_type' => \App\Models\CalendarEvent::TYPE_VACCINATION,
                'title' => 'Pemeriksaan Kesehatan',
                'description' => 'Pemeriksaan berikutnya untuk ' . ($record->animal->nama ?? 'Ternak'),
                'event_date' => $record->pemeriksaan_berikutnya,
            ]);
        }

        // 2. Generate birth estimate from perkawinan data
        $perkawinanRecords = Perkawinan::byUser($userId)
            ->where('status_reproduksi', 'bunting')
            ->get();

        foreach ($perkawinanRecords as $perkawinan) {
            // Cattle gestation period: ~280-285 days
            $estimatedBirthDate = Carbon::parse($perkawinan->tanggal_perkawinan)->addDays(283);

            if ($estimatedBirthDate->isFuture()) {
                \App\Models\CalendarEvent::create([
                    'user_id' => $userId,
                    'animal_id' => $perkawinan->hewan_id,
                    'event_type' => \App\Models\CalendarEvent::TYPE_BIRTH_ESTIMATE,
                    'title' => 'Perkiraan Kelahiran',
                    'description' => 'Estimasi kelahiran untuk ' . ($perkawinan->animal->nama ?? 'Ternak'),
                    'event_date' => $estimatedBirthDate,
                ]);
            }
        }
    }

    /**
     * Get calendar data for specified or current month (for view)
     */
    private function getCalendarDataForView($userId)
    {
        // Get month and year from request, default to current
        $month = request('month', Carbon::now()->month);
        $year = request('year', Carbon::now()->year);

        // Create date for specified month
        $date = Carbon::create($year, $month, 1);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Get events for specified month
        $events = \App\Models\CalendarEvent::where('user_id', $userId)
            ->whereBetween('event_date', [$startOfMonth, $endOfMonth])
            ->with('animal')
            ->get();

        // Group events by date
        $eventsByDate = $events->groupBy(function ($event) {
            return $event->event_date->format('Y-m-d');
        });

        return [
            'current_month' => $date->format('F Y'),
            'month' => $date->month,
            'year' => $date->year,
            'days_in_month' => $date->daysInMonth,
            'start_day_of_week' => $startOfMonth->dayOfWeek, // 0 = Sunday
            'events_by_date' => $eventsByDate,
        ];
    }

    /**
     * Get calendar data for AJAX requests
     */
    public function getCalendarData(Request $request)
    {
        $userId = Auth::id();
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        // Create date for specified month
        $date = Carbon::create($year, $month, 1);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Get events for specified month
        $events = \App\Models\CalendarEvent::where('user_id', $userId)
            ->whereBetween('event_date', [$startOfMonth, $endOfMonth])
            ->with('animal')
            ->get();

        // Transform events to include type_color and type_icon
        $eventsData = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->event_date->format('Y-m-d'),
                'event_type' => $event->event_type,
                'type_color' => $event->type_color,
                'type_icon' => $event->type_icon,
                'completed' => $event->completed,
                'animal' => $event->animal ? [
                    'id' => $event->animal->id,
                    'nama_hewan' => $event->animal->nama_hewan,
                    'kode_hewan' => $event->animal->kode_hewan,
                ] : null,
            ];
        });

        // Group events by date
        $eventsByDate = $eventsData->groupBy('event_date');

        return response()->json([
            'success' => true,
            'month' => $date->month,
            'year' => $date->year,
            'events_by_date' => $eventsByDate,
        ]);
    }

    /**
     * Mark calendar event as complete (AJAX)
     */
    public function markEventComplete($id)
    {
        try {
            $event = \App\Models\CalendarEvent::where('user_id', Auth::id())
                ->findOrFail($id);

            $event->update(['completed' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Event ditandai selesai'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai event sebagai selesai'
            ], 500);
        }
    }
}