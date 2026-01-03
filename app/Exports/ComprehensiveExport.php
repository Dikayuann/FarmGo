<?php

namespace App\Exports;

use App\Models\Animal;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ComprehensiveExport implements WithMultipleSheets
{
    public $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Return array of sheets
     */
    public function sheets(): array
    {
        $sheets = [];

        // Sheet 1: Data Ternak
        $sheets[] = new class ($this->filters) extends AnimalsExport {
            public function title(): string
            {
                return 'Data Ternak';
            }
        };

        // Sheet 2: Riwayat Kesehatan
        $sheets[] = new class ($this->filters) extends HealthRecordsExport {
            public function title(): string
            {
                return 'Riwayat Kesehatan';
            }
        };

        // Sheet 3: Data Reproduksi
        $sheets[] = new class ($this->filters) extends ReproductionExport {
            public function title(): string
            {
                return 'Data Reproduksi';
            }
        };

        // Sheet 4: Ringkasan/Statistik
        $sheets[] = new ComprehensiveSummarySheet();

        return $sheets;
    }
}

/**
 * Summary sheet with statistics
 */
class ComprehensiveSummarySheet implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithTitle, \Maatwebsite\Excel\Concerns\WithStyles
{
    use \Maatwebsite\Excel\Concerns\Exportable;

    public function collection()
    {
        $userId = Auth::id();

        // Get statistics
        $totalAnimals = Animal::where('user_id', $userId)->count();
        $totalSapi = Animal::where('user_id', $userId)->where('jenis_hewan', 'sapi')->count();
        $totalKambing = Animal::where('user_id', $userId)->where('jenis_hewan', 'kambing')->count();
        $totalDomba = Animal::where('user_id', $userId)->where('jenis_hewan', 'domba')->count();

        $totalSehat = Animal::where('user_id', $userId)->where('status_kesehatan', 'sehat')->count();
        $totalSakit = Animal::where('user_id', $userId)->where('status_kesehatan', 'sakit')->count();
        $totalPerawatan = Animal::where('user_id', $userId)->where('status_kesehatan', 'dalam perawatan')->count();

        $totalJantan = Animal::where('user_id', $userId)->where('jenis_kelamin', 'jantan')->count();
        $totalBetina = Animal::where('user_id', $userId)->where('jenis_kelamin', 'betina')->count();

        return collect([
            ['Kategori', 'Jumlah'],
            ['', ''],
            ['TOTAL TERNAK', $totalAnimals],
            ['', ''],
            ['Berdasarkan Jenis:', ''],
            ['- Sapi', $totalSapi],
            ['- Kambing', $totalKambing],
            ['- Domba', $totalDomba],
            ['', ''],
            ['Berdasarkan Status Kesehatan:', ''],
            ['- Sehat', $totalSehat],
            ['- Sakit', $totalSakit],
            ['- Dalam Perawatan', $totalPerawatan],
            ['', ''],
            ['Berdasarkan Jenis Kelamin:', ''],
            ['- Jantan', $totalJantan],
            ['- Betina', $totalBetina],
        ]);
    }

    public function headings(): array
    {
        return ['RINGKASAN DATA FARMGO', ''];
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            3 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
            10 => ['font' => ['bold' => true]],
            15 => ['font' => ['bold' => true]],
        ];
    }
}
