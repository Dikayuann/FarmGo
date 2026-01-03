<?php

namespace App\Http\Controllers;

use App\Exports\AnimalsExport;
use App\Exports\HealthRecordsExport;
use App\Exports\ReproductionExport;
use App\Exports\ComprehensiveExport;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Display export page
     */
    public function index()
    {
        // Get user's animals for dropdown filter
        $animals = Animal::where('user_id', Auth::id())
            ->orderBy('nama_hewan')
            ->get();

        return view('ekspor', compact('animals'));
    }

    /**
     * Export animals data
     */
    public function exportAnimals(Request $request)
    {
        // Check if user is premium
        if (!$this->isPremiumUser()) {
            return back()->with('error', 'Fitur ekspor hanya tersedia untuk pengguna premium. Silakan upgrade paket Anda.');
        }

        $request->validate([
            'format' => 'required|in:excel,csv',
            'jenis_hewan' => 'nullable|string',
            'status_ternak' => 'nullable|string',
            'tanggal_dari' => 'nullable|date',
            'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_dari',
        ]);

        $filters = [
            'jenis_hewan' => $request->jenis_hewan,
            'status_ternak' => $request->status_ternak,
            'tanggal_dari' => $request->tanggal_dari,
            'tanggal_sampai' => $request->tanggal_sampai,
        ];

        $fileName = 'data-ternak_' . date('Y-m-d') . '.' . ($request->format === 'excel' ? 'xlsx' : 'csv');

        if ($request->format === 'excel') {
            return Excel::download(new AnimalsExport($filters), $fileName);
        } else {
            return Excel::download(new AnimalsExport($filters), $fileName, \Maatwebsite\Excel\Excel::CSV);
        }
    }

    /**
     * Export health records data
     */
    public function exportHealthRecords(Request $request)
    {
        // Check if user is premium
        if (!$this->isPremiumUser()) {
            return back()->with('error', 'Fitur ekspor hanya tersedia untuk pengguna premium. Silakan upgrade paket Anda.');
        }

        $request->validate([
            'format' => 'required|in:excel,csv',
            'animal_id' => 'nullable|exists:animals,id',
            'status_kesehatan' => 'nullable|string',
            'tanggal_dari' => 'nullable|date',
            'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_dari',
        ]);

        $filters = [
            'animal_id' => $request->animal_id,
            'status_kesehatan' => $request->status_kesehatan,
            'tanggal_dari' => $request->tanggal_dari,
            'tanggal_sampai' => $request->tanggal_sampai,
        ];

        $fileName = 'riwayat-kesehatan_' . date('Y-m-d') . '.' . ($request->format === 'excel' ? 'xlsx' : 'csv');

        if ($request->format === 'excel') {
            return Excel::download(new HealthRecordsExport($filters), $fileName);
        } else {
            return Excel::download(new HealthRecordsExport($filters), $fileName, \Maatwebsite\Excel\Excel::CSV);
        }
    }

    /**
     * Export reproduction data
     */
    public function exportReproduction(Request $request)
    {
        // Check if user is premium
        if (!$this->isPremiumUser()) {
            return back()->with('error', 'Fitur ekspor hanya tersedia untuk pengguna premium. Silakan upgrade paket Anda.');
        }

        $request->validate([
            'format' => 'required|in:excel,csv',
            'status_reproduksi' => 'nullable|string',
            'tanggal_dari' => 'nullable|date',
            'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_dari',
        ]);

        $filters = [
            'status_reproduksi' => $request->status_reproduksi,
            'tanggal_dari' => $request->tanggal_dari,
            'tanggal_sampai' => $request->tanggal_sampai,
        ];

        $fileName = 'data-reproduksi_' . date('Y-m-d') . '.' . ($request->format === 'excel' ? 'xlsx' : 'csv');

        if ($request->format === 'excel') {
            return Excel::download(new ReproductionExport($filters), $fileName);
        } else {
            return Excel::download(new ReproductionExport($filters), $fileName, \Maatwebsite\Excel\Excel::CSV);
        }
    }

    /**
     * Export comprehensive report (multi-sheet Excel only)
     */
    public function exportComprehensive(Request $request)
    {
        // Check if user is premium
        if (!$this->isPremiumUser()) {
            return back()->with('error', 'Fitur ekspor hanya tersedia untuk pengguna premium. Silakan upgrade paket Anda.');
        }

        $filters = [];
        $fileName = 'laporan-farmgo_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new ComprehensiveExport($filters), $fileName);
    }

    /**
     * Check if current user is premium
     */
    private function isPremiumUser(): bool
    {
        $user = Auth::user();

        // Check if user has active premium subscription
        $activeSubscription = $user->langganans()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->where('package_type', '!=', 'trial')
            ->first();

        return $activeSubscription !== null;
    }
}
