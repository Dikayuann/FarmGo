<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return User::with('langganans')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Role',
            'Farm Name',
            'Phone',
            'Status Langganan',
            'Batas Ternak',
            'Batas Vaksin',
            'Batas Reproduksi',
            'Google ID',
            'Email Verified',
            'Created At',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->role,
            $user->farm_name ?? '-',
            $user->phone ?? '-',
            $user->status_langganan ?? '-',
            $user->batas_ternak ?? 0,
            $user->batas_vaksin ?? 0,
            $user->batas_reproduksi ?? 0,
            $user->google_id ?? '-',
            $user->email_verified_at ? 'Yes' : 'No',
            $user->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

