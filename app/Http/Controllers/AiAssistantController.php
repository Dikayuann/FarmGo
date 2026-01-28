<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiAssistantController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        $user = Auth::user();

        // Get user's farm context data
        $aiAnalyzer = new \App\Services\AiHealthAnalyzer();
        $farmAnalysis = $aiAnalyzer->analyzeFarmHealth($user->id);
        $highRiskAnimals = $aiAnalyzer->getHighRiskAnimals($user->id, 3);

        $totalAnimals = \App\Models\Animal::where('user_id', $user->id)->count();
        $recentHealthIssues = \App\Models\HealthRecord::whereHas('animal', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->where('status_kesehatan', '!=', 'sehat')
            ->latest()
            ->take(3)
            ->get()
            ->map(fn($h) => $h->animal->kode_hewan . ': ' . $h->diagnosis)
            ->toArray();

        // Build farm context for AI
        $farmContext = "Data Farm User saat ini:\n" .
            "- Total ternak: {$totalAnimals}\n" .
            "- Ternak risiko tinggi: {$farmAnalysis['high_risk']}\n" .
            "- Ternak risiko sedang: {$farmAnalysis['medium_risk']}\n" .
            "- Ternak sehat: {$farmAnalysis['low_risk']}\n";

        if ($highRiskAnimals->count() > 0) {
            $farmContext .= "\nTernak Berisiko:\n";
            foreach ($highRiskAnimals as $animal) {
                $farmContext .= "- {$animal->kode_hewan} ({$animal->nama}): Risk Score {$animal->risk_score}\n";
                if (count($animal->recommendations) > 0) {
                    $farmContext .= "  Rekomendasi: {$animal->recommendations[0]}\n";
                }
            }
        }

        if (count($recentHealthIssues) > 0) {
            $farmContext .= "\nMasalah Kesehatan Terkini:\n";
            foreach ($recentHealthIssues as $issue) {
                $farmContext .= "- {$issue}\n";
            }
        }

        // System prompt untuk konteks FarmGo
        $systemPrompt = "Kamu adalah asisten virtual FarmGo, sistem manajemen peternakan sapi. 
Tugasmu adalah membantu peternak dengan:
1. Menjawab pertanyaan tentang kesehatan ternak
2. Memberikan tips perawatan dan nutrisi sapi
3. Membantu navigasi fitur sistem FarmGo
4. Memberikan saran tentang reproduksi dan breeding
5. Menganalisa data farm mereka dan memberikan insight

Fitur-fitur FarmGo yang tersedia:
- Dashboard: Ringkasan data peternakan + AI Health Insights
- Manajemen Ternak: Daftar dan detail semua ternak
- Monitoring Kesehatan: Catatan kesehatan dan vaksinasi
- Catatan Reproduksi: Data perkawinan dan kelahiran
- Kalender Event: Auto-generate reminder vaksinasi & kelahiran
- AI Analytics: Risk scoring otomatis untuk setiap ternak
- Ekspor Data: Export data ke Excel/PDF
- Langganan: Paket premium dan trial

PENTING: Kamu punya akses ke data farm user. Gunakan data ini untuk memberikan jawaban yang personal dan spesifik.
Jika user bertanya tentang kondisi farm, ternak berisiko, atau saran, gunakan data yang tersedia.

{$farmContext}

Jawab dengan bahasa Indonesia yang ramah dan profesional. 
Jika ditanya tentang fitur yang tidak ada di sistem, jelaskan dengan jujur.
Berikan jawaban yang actionable dan praktis (maksimal 4-5 kalimat).
Jika menyebutkan ternak, gunakan kode hewan yang sebenarnya dari data user.";

        try {
            // Inisialisasi Gemini client
            $client = \Gemini::client(config('services.gemini.api_key'));

            // Gabungkan system prompt dengan user message
            $fullPrompt = $systemPrompt . "\n\nPertanyaan User: " . $userMessage . "\n\nJawaban:";

            // Panggil Gemini API (menggunakan gemini-2.5-flash untuk stabilitas)
            $result = $client->generativeModel(model: 'gemini-2.5-flash')
                ->generateContent($fullPrompt);

            $response = $result->text();

            return response()->json([
                'success' => true,
                'message' => $response,
            ]);

        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('AI Assistant Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id ?? null,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Maaf, terjadi kesalahan. Silakan coba lagi.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
