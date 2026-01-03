<?php

namespace App\Exports;

use App\Models\Langganan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LangganansExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Langganan::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'User Name',
            'User Email',
            'Paket Langganan',
            'Harga',
            'Metode Pembayaran',
            'Transaction Reference',
            'Tanggal Mulai',
            'Tanggal Berakhir',
            'Status',
            'Auto Renew',
            'Created At',
        ];
    }

    public function map($langganan): array
    {
        return [
            $langganan->id,
            $langganan->user->name ?? '-',
            $langganan->user->email ?? '-',
            match($langganan->paket_langganan) {
                'trial' => 'Trial',
                'premium_monthly' => 'Premium Bulanan',
                'premium_yearly' => 'Premium Tahunan',
                default => $langganan->paket_langganan,
            },
            'Rp ' . number_format($langganan->harga, 0, ',', '.'),
            match($langganan->metode_pembayaran) {
                'midtrans' => 'Midtrans',
                'manual_transfer' => 'Transfer Manual',
                'other' => 'Lainnya',
                default => $langganan->metode_pembayaran,
            },
            $langganan->transaction_reference ?? '-',
            $langganan->tanggal_mulai->format('Y-m-d'),
            $langganan->tanggal_berakhir->format('Y-m-d'),
            match($langganan->status) {
                'aktif' => 'Aktif',
                'kadaluarsa' => 'Kadaluarsa',
                'dibatalkan' => 'Dibatalkan',
                default => $langganan->status,
            },
            $langganan->auto_renew ? 'Yes' : 'No',
            $langganan->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

