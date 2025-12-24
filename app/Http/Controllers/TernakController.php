<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Http\Requests\StoreAnimalRequest;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class TernakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $jenis = $request->input('jenis', 'all');
        $status = $request->input('status', 'all');

        $animals = Animal::where('user_id', Auth::id())
            ->search($search)
            ->byJenis($jenis)
            ->byStatus($status)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Stats for cards
        $stats = [
            'total' => Animal::where('user_id', Auth::id())->count(),
            'sapi' => Animal::where('user_id', Auth::id())->where('jenis_hewan', 'sapi')->count(),
            'kambing' => Animal::where('user_id', Auth::id())->where('jenis_hewan', 'kambing')->count(),
            'domba' => Animal::where('user_id', Auth::id())->where('jenis_hewan', 'domba')->count(),
            'sehat' => Animal::where('user_id', Auth::id())->where('status_kesehatan', 'sehat')->count(),
        ];

        // Check if print all QR is requested
        if ($request->has('print_all')) {
            $allAnimals = Animal::where('user_id', Auth::id())->get();
            return view('ternak.print-all-qr', compact('allAnimals'));
        }

        return view('ternak.index', compact('animals', 'stats', 'search', 'jenis', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnimalRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // Generate kode_hewan if not provided
        if (empty($data['kode_hewan'])) {
            // Set prefix based on jenis_hewan
            $prefixMap = [
                'sapi' => 'SA',
                'kambing' => 'K',
                'domba' => 'D',
            ];
            $prefix = $prefixMap[$data['jenis_hewan']] ?? 'X';

            // Find last animal code with same prefix
            $lastAnimal = Animal::where('user_id', Auth::id())
                ->where('kode_hewan', 'like', "{$prefix}-%")
                ->orderByRaw('CAST(SUBSTRING(kode_hewan, ' . (strlen($prefix) + 2) . ') AS UNSIGNED) DESC')
                ->first();

            $number = 1;
            if ($lastAnimal) {
                // Extract number after prefix and dash (e.g., "SA-001" -> 001)
                $parts = explode('-', $lastAnimal->kode_hewan);
                if (count($parts) == 2) {
                    $lastNumber = (int) $parts[1];
                    $number = $lastNumber + 1;
                }
            }

            $data['kode_hewan'] = $prefix . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        }

        $animal = Animal::create($data);

        // Generate QR Code
        $this->generateQRCode($animal);

        return redirect()->route('ternak.index')
            ->with('success', 'Hewan ternak berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $animal = Animal::where('user_id', Auth::id())->findOrFail($id);
        return view('ternak.show', compact('animal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAnimalRequest $request, string $id)
    {
        $animal = Animal::where('user_id', Auth::id())->findOrFail($id);
        $animal->update($request->validated());

        // Regenerate QR Code
        $this->generateQRCode($animal);

        return redirect()->route('ternak.index')
            ->with('success', 'Data hewan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $animal = Animal::where('user_id', Auth::id())->findOrFail($id);
        $animal->delete();

        return redirect()->route('ternak.index')
            ->with('success', 'Hewan ternak berhasil dihapus!');
    }

    /**
     * Generate QR Code for animal
     */
    private function generateQRCode(Animal $animal)
    {
        // Create QR code directory if it doesn't exist
        $qrPath = public_path('storage/qrcodes');
        if (!file_exists($qrPath)) {
            mkdir($qrPath, 0755, true);
        }

        // Generate QR code URL - points to animal detail page
        $url = route('ternak.show', $animal->id);

        // Generate QR code image
        $fileName = 'qr_' . $animal->kode_hewan . '.svg';
        $filePath = $qrPath . '/' . $fileName;

        QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($url, $filePath);

        // Update animal with QR URL
        $animal->update([
            'qr_url' => asset('storage/qrcodes/' . $fileName)
        ]);
    }
}
