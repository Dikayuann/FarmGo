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

        // System prompt untuk konteks FarmGo
        $systemPrompt = "Kamu adalah asisten virtual FarmGo, sistem manajemen peternakan sapi. 
Tugasmu adalah membantu peternak dengan:
1. Menjawab pertanyaan tentang kesehatan ternak
2. Memberikan tips perawatan dan nutrisi sapi
3. Membantu navigasi fitur sistem FarmGo
4. Memberikan saran tentang reproduksi dan breeding

Fitur-fitur FarmGo yang tersedia:
- Dashboard: Ringkasan data peternakan
- Manajemen Ternak: Daftar dan detail semua ternak
- Monitoring Kesehatan: Catatan kesehatan dan vaksinasi
- Catatan Reproduksi: Data perkawinan dan kelahiran
- Ekspor Data: Export data ke Excel/PDF
- Langganan: Paket premium dan trial

Jawab dengan bahasa Indonesia yang ramah dan profesional. 
Jika ditanya tentang fitur yang tidak ada di sistem, jelaskan dengan jujur.
Berikan jawaban yang singkat dan praktis (maksimal 3-4 kalimat).";

        try {
            // Inisialisasi Gemini client
            $client = \Gemini::client(config('services.gemini.api_key'));

            // Gabungkan system prompt dengan user message
            $fullPrompt = $systemPrompt . "\n\nPertanyaan: " . $userMessage . "\n\nJawaban:";

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
