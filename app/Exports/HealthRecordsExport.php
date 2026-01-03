<?php

namespace App\Exports;

use App\Models\HealthRecord;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Auth;

class HealthRecordsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Query health records milik user yang sedang login
     */
    public function query()
    {
        $query = HealthRecord::query()
            ->with('animal')
            ->whereHas('animal', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->orderBy('tanggal_pemeriksaan', 'desc');

        // Apply filters
        if (!empty($this->filters['animal_id']) && $this->filters['animal_id'] !== 'all') {
            $query->where('animal_id', $this->filters['animal_id']);
        }

        if (!empty($this->filters['status_kesehatan']) && $this->filters['status_kesehatan'] !== 'all') {
            $query->where('status_kesehatan', $this->filters['status_kesehatan']);
        }

        if (!empty($this->filters['tanggal_dari'])) {
            $query->whereDate('tanggal_pemeriksaan', '>=', $this->filters['tanggal_dari']);
        }

        if (!empty($this->filters['tanggal_sampai'])) {
            $query->whereDate('tanggal_pemeriksaan', '<=', $this->filters['tanggal_sampai']);
        }

        return $query;
    }

    /**
     * Header kolom
     */
    public function headings(): array
    {
        return [
            'Nama Hewan',
            'Kode Hewan',
            'Tanggal Pemeriksaan',
            'Jenis Pemeriksaan',
            'Berat Badan (kg)',
            'Suhu Tubuh (°C)',
            'Status Kesehatan',
            'Gejala',
            'Diagnosis',
            'Tindakan',
            'Obat',
            'Biaya (Rp)',
            'Catatan',
        ];
    }

    /**
     * Map data untuk setiap row
     */
    public function map($record): array
    {
        return [
            $record->animal->nama_hewan ?? '-',
            $record->animal->kode_hewan ?? '-',
            $record->tanggal_pemeriksaan ? $record->tanggal_pemeriksaan->format('d/m/Y H:i') : '-',
            ucfirst($record->jenis_pemeriksaan ?? '-'),
            $record->berat_badan ? number_format($record->berat_badan, 2, ',', '.') : '-',
            $record->suhu_tubuh ? number_format($record->suhu_tubuh, 1, ',', '.') : '-',
            ucfirst($record->status_kesehatan ?? '-'),
            $record->gejala ?? '-',
            $record->diagnosis ?? '-',
            $record->tindakan ?? '-',
            $record->obat ?? '-',
            $record->biaya ? 'Rp ' . number_format($record->biaya, 0, ',', '.') : '-',
            $record->catatan ?? '-',
        ];
    }

    /**
     * Apply styles to worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold header
            1 => ['font' => ['bold' => true]],
        ];
    }
}
