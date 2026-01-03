<?php

namespace App\Exports;

use App\Models\Perkawinan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Auth;

class ReproductionExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Query perkawinan milik user yang sedang login
     */
    public function query()
    {
        $query = Perkawinan::query()
            ->with(['jantan', 'betina'])
            ->where(function ($q) {
                $q->whereHas('jantan', function ($jq) {
                    $jq->where('user_id', Auth::id());
                })->orWhereHas('betina', function ($bq) {
                    $bq->where('user_id', Auth::id());
                });
            })
            ->orderBy('tanggal_perkawinan', 'desc');

        // Apply filters
        if (!empty($this->filters['status_reproduksi']) && $this->filters['status_reproduksi'] !== 'all') {
            $query->where('status_reproduksi', $this->filters['status_reproduksi']);
        }

        if (!empty($this->filters['tanggal_dari'])) {
            $query->whereDate('tanggal_perkawinan', '>=', $this->filters['tanggal_dari']);
        }

        if (!empty($this->filters['tanggal_sampai'])) {
            $query->whereDate('tanggal_perkawinan', '<=', $this->filters['tanggal_sampai']);
        }

        return $query;
    }

    /**
     * Header kolom
     */
    public function headings(): array
    {
        return [
            'Kode Perkawinan',
            'Jantan',
            'Betina',
            'Tanggal Birahi',
            'Tanggal Kawin',
            'Metode Perkawinan',
            'Status Reproduksi',
            'Estimasi/Tanggal Lahir',
            'Jumlah Anak',
            'Catatan',
        ];
    }

    /**
     * Map data untuk setiap row
     */
    public function map($perkawinan): array
    {
        // Handle jantan (bisa dari database atau eksternal)
        $jantanName = '-';
        if ($perkawinan->jantan_type === 'internal' && $perkawinan->jantan) {
            $jantanName = $perkawinan->jantan->nama_hewan . ' (' . $perkawinan->jantan->kode_hewan . ')';
        } elseif ($perkawinan->jantan_type === 'external' && $perkawinan->jantan_external_name) {
            $jantanName = $perkawinan->jantan_external_name . ' (Eksternal)';
        } elseif ($perkawinan->jantan_type === 'ib' && $perkawinan->semen_code) {
            $jantanName = 'IB - ' . $perkawinan->semen_code;
        }

        // Betina
        $betinaName = $perkawinan->betina
            ? $perkawinan->betina->nama_hewan . ' (' . $perkawinan->betina->kode_hewan . ')'
            : '-';

        // Tanggal lahir atau estimasi
        $tanggalLahir = '-';
        if ($perkawinan->status_reproduksi === 'melahirkan' && $perkawinan->tanggal_melahirkan) {
            $tanggalLahir = $perkawinan->tanggal_melahirkan->format('d/m/Y');
        } elseif ($perkawinan->estimasi_kelahiran) {
            $tanggalLahir = 'Est: ' . $perkawinan->estimasi_kelahiran->format('d/m/Y');
        }

        return [
            $perkawinan->kode_perkawinan,
            $jantanName,
            $betinaName,
            $perkawinan->tanggal_birahi ? $perkawinan->tanggal_birahi->format('d/m/Y') : '-',
            $perkawinan->tanggal_perkawinan ? $perkawinan->tanggal_perkawinan->format('d/m/Y') : '-',
            ucfirst($perkawinan->metode_perkawinan ?? '-'),
            ucfirst($perkawinan->status_reproduksi ?? '-'),
            $tanggalLahir,
            $perkawinan->jumlah_anak ?? '-',
            $perkawinan->catatan ?? '-',
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
