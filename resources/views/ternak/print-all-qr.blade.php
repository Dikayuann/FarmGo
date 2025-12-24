<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Semua QR Code - FarmGo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .qr-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .qr-item {
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .qr-item h3 {
            font-size: 16px;
            margin-bottom: 5px;
            color: #1f2937;
        }

        .qr-item .code {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .qr-item .details {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .qr-item img {
            width: 150px;
            height: 150px;
            margin: 10px auto;
            display: block;
        }

        .no-print {
            margin-bottom: 20px;
        }

        .btn {
            background-color: #10b981;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
        }

        .btn:hover {
            background-color: #059669;
        }

        .btn-secondary {
            background-color: #6b7280;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        @media print {
            .no-print {
                display: none;
            }

            .qr-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .qr-item {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }

        @page {
            size: A4;
            margin: 15mm;
        }
    </style>
</head>

<body>
    <div class="no-print">
        <h1 style="margin-bottom: 10px;">Cetak Semua QR Code</h1>
        <p style="color: #6b7280; margin-bottom: 20px;">Total: {{ $allAnimals->count() }} ternak</p>
        <button onclick="window.print()" class="btn">
            <i class="fa-solid fa-print"></i> Cetak
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i class="fa-solid fa-times"></i> Tutup
        </button>
    </div>

    <div class="qr-grid">
        @foreach($allAnimals as $animal)
            <div class="qr-item">
                <h3>{{ $animal->nama_hewan }}</h3>
                <div class="code">{{ $animal->kode_hewan }}</div>
                <div class="details">
                    {{ ucfirst($animal->jenis_hewan) }} - {{ $animal->ras_hewan }}
                </div>
                @if($animal->qr_url)
                    <img src="{{ $animal->qr_url }}" alt="QR Code {{ $animal->kode_hewan }}">
                @else
                    <div
                        style="width: 150px; height: 150px; background: #f3f4f6; display: flex; align-items: center; justify-center; margin: 10px auto; border-radius: 4px;">
                        <span style="color: #9ca3af; font-size: 12px;">QR belum dibuat</span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>

</html>