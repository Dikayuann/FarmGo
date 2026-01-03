<?php

namespace App\Exports;

use App\Models\Animal;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Auth;

class AnimalsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Query data animals milik user yang sedang login
     */
    public function query()
    {
        $query = Animal::query()
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Apply filters
        if (!empty($this->filters['jenis_hewan']) && $this->filters['jenis_hewan'] !== 'all') {
            $query->where('jenis_hewan', $this->filters['jenis_hewan']);
        }

        if (!empty($this->filters['status_kesehatan']) && $this->filters['status_kesehatan'] !== 'all') {
            $query->where('status_kesehatan', $this->filters['status_kesehatan']);
        }

        if (!empty($this->filters['tanggal_dari'])) {
            $query->where('tanggal_lahir', '>=', $this->filters['tanggal_dari']);
        }

        if (!empty($this->filters['tanggal_sampai'])) {
            $query->where('tanggal_lahir', '<=', $this->filters['tanggal_sampai']);
        }

        return $query;
    }

    /**
     * Header kolom
     */
    public function headings(): array
    {
        return [
            'Kode Hewan',
            'Nama Hewan',
            'Jenis',
            'Ras',
            'Tanggal Lahir',
            'Usia',
            'Jenis Kelamin',
            'Berat Badan (kg)',
            'Status Kesehatan',
        ];
    }

    /**
     * Map data untuk setiap row
     */
    public function map($animal): array
    {
        return [
            $animal->kode_hewan,
            $animal->nama_hewan,
            ucfirst($animal->jenis_hewan),
            $animal->ras_hewan,
            $animal->tanggal_lahir ? $animal->tanggal_lahir->format('d/m/Y') : '-',
            $animal->usia,
            ucfirst($animal->jenis_kelamin),
            $animal->berat_badan ? number_format($animal->berat_badan, 2, ',', '.') : '-',
            ucfirst($animal->status_kesehatan),
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
