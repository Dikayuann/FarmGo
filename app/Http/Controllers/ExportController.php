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
        try {
            // Check if user is premium
            if (!$this->isPremiumUser()) {
                return back()->with('error', 'Fitur ekspor hanya tersedia untuk pengguna premium. Silakan upgrade paket Anda.');
            }

            $request->validate([
                'format' => 'required|in:excel,csv',
                'jenis_hewan' => 'nullable|string',
                'status_kesehatan' => 'nullable|string',
                'tanggal_dari' => 'nullable|date',
                'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_dari',
            ]);

            // Build query safely with filled() method
            $query = Animal::where('user_id', Auth::id());

            if ($request->filled('jenis_hewan') && $request->jenis_hewan !== 'all') {
                $query->where('jenis_hewan', $request->jenis_hewan);
            }
            if ($request->filled('status_kesehatan') && $request->status_kesehatan !== 'all') {
                $query->where('status_kesehatan', $request->status_kesehatan);
            }
            if ($request->filled('tanggal_dari')) {
                $query->whereDate('tanggal_lahir', '>=', $request->tanggal_dari);
            }
            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('tanggal_lahir', '<=', $request->tanggal_sampai);
            }

            // Check if data exists
            if ($query->count() === 0) {
                return back()->with('error', 'Tidak ada data ternak yang sesuai dengan filter yang dipilih. Silakan ubah filter atau tambahkan data terlebih dahulu.');
            }

            $filters = [
                'jenis_hewan' => $request->input('jenis_hewan'),
                'status_kesehatan' => $request->input('status_kesehatan'),
                'tanggal_dari' => $request->input('tanggal_dari'),
                'tanggal_sampai' => $request->input('tanggal_sampai'),
            ];

            $format = $request->input('format');
            $fileName = 'data-ternak_' . date('Y-m-d') . '.' . ($format === 'excel' ? 'xlsx' : 'csv');
            $export = new AnimalsExport($filters);

            if ($format === 'excel') {
                return Excel::download($export, $fileName);
            } else {
                return Excel::download($export, $fileName, \Maatwebsite\Excel\Excel::CSV);
            }

        } catch (\Exception $e) {
            \Log::error('Export Animals Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'filters' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat mengekspor data. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Export health records data
     */
    public function exportHealthRecords(Request $request)
    {
        try {
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

            // Build query safely
            $query = \App\Models\HealthRecord::whereHas('animal', function ($q) {
                $q->where('user_id', Auth::id());
            });

            if ($request->filled('animal_id') && $request->animal_id !== 'all') {
                $query->where('animal_id', $request->animal_id);
            }
            if ($request->filled('status_kesehatan') && $request->status_kesehatan !== 'all') {
                $query->where('status_kesehatan', $request->status_kesehatan);
            }
            if ($request->filled('tanggal_dari')) {
                $query->whereDate('tanggal_pemeriksaan', '>=', $request->tanggal_dari);
            }
            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('tanggal_pemeriksaan', '<=', $request->tanggal_sampai);
            }

            if ($query->count() === 0) {
                return back()->with('error', 'Tidak ada data riwayat kesehatan yang sesuai dengan filter yang dipilih. Silakan ubah filter atau tambahkan data terlebih dahulu.');
            }

            $filters = [
                'animal_id' => $request->input('animal_id'),
                'status_kesehatan' => $request->input('status_kesehatan'),
                'tanggal_dari' => $request->input('tanggal_dari'),
                'tanggal_sampai' => $request->input('tanggal_sampai'),
            ];

            $format = $request->input('format');
            $fileName = 'riwayat-kesehatan_' . date('Y-m-d') . '.' . ($format === 'excel' ? 'xlsx' : 'csv');
            $export = new HealthRecordsExport($filters);

            if ($format === 'excel') {
                return Excel::download($export, $fileName);
            } else {
                return Excel::download($export, $fileName, \Maatwebsite\Excel\Excel::CSV);
            }

        } catch (\Exception $e) {
            \Log::error('Export Health Records Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'filters' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat mengekspor data kesehatan. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Export reproduction data
     */
    public function exportReproduction(Request $request)
    {
        try {
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

            // Build query safely
            $query = \App\Models\Perkawinan::byUser(Auth::id());

            if ($request->filled('status_reproduksi') && $request->status_reproduksi !== 'all') {
                $query->where('status_reproduksi', $request->status_reproduksi);
            }
            if ($request->filled('tanggal_dari')) {
                $query->whereDate('tanggal_perkawinan', '>=', $request->tanggal_dari);
            }
            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('tanggal_perkawinan', '<=', $request->tanggal_sampai);
            }

            if ($query->count() === 0) {
                return back()->with('error', 'Tidak ada data reproduksi yang sesuai dengan filter yang dipilih. Silakan ubah filter atau tambahkan data terlebih dahulu.');
            }

            $filters = [
                'status_reproduksi' => $request->input('status_reproduksi'),
                'tanggal_dari' => $request->input('tanggal_dari'),
                'tanggal_sampai' => $request->input('tanggal_sampai'),
            ];

            $format = $request->input('format');
            $fileName = 'data-reproduksi_' . date('Y-m-d') . '.' . ($format === 'excel' ? 'xlsx' : 'csv');
            $export = new ReproductionExport($filters);

            if ($format === 'excel') {
                return Excel::download($export, $fileName);
            } else {
                return Excel::download($export, $fileName, \Maatwebsite\Excel\Excel::CSV);
            }

        } catch (\Exception $e) {
            \Log::error('Export Reproduction Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'filters' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat mengekspor data reproduksi. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Export comprehensive report (multi-sheet Excel only)
     */
    public function exportComprehensive(Request $request)
    {
        try {
            // Check if user is premium
            if (!$this->isPremiumUser()) {
                return back()->with('error', 'Fitur ekspor hanya tersedia untuk pengguna premium. Silakan upgrade paket Anda.');
            }

            // Check if user has any data to export
            $hasAnimals = Animal::where('user_id', Auth::id())->count() > 0;
            $hasHealthRecords = \App\Models\HealthRecord::whereHas('animal', function ($q) {
                $q->where('user_id', Auth::id());
            })->count() > 0;
            $hasReproduction = \App\Models\Perkawinan::byUser(Auth::id())->count() > 0;

            if (!$hasAnimals && !$hasHealthRecords && !$hasReproduction) {
                return back()->with('error', 'Tidak ada data untuk diekspor. Silakan tambahkan data ternak, kesehatan, atau reproduksi terlebih dahulu.');
            }

            $filters = [];
            $fileName = 'laporan-farmgo_' . date('Y-m-d') . '.xlsx';

            return Excel::download(new ComprehensiveExport($filters), $fileName);

        } catch (\Exception $e) {
            \Log::error('Export Comprehensive Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat mengekspor laporan. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Check if current user is premium
     */
    private function isPremiumUser(): bool
    {
        // Use the User model's built-in method
        return Auth::user()->canExportData();
    }
}
