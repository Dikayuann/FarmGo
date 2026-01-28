<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\AiHealthAnalyzer;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth:sanctum')->get('/ai-health-insights', function (Request $request) {
    $user = Auth::user();
    $aiAnalyzer = new AiHealthAnalyzer();

    $highRiskAnimals = $aiAnalyzer->getHighRiskAnimals($user->id, 5);
    $farmHealthAnalysis = $aiAnalyzer->analyzeFarmHealth($user->id);

    return response()->json([
        'animals' => $highRiskAnimals->map(function ($animal) {
            return [
                'id' => $animal->id,
                'kode_hewan' => $animal->kode_hewan,
                'nama' => $animal->nama,
                'risk_score' => $animal->risk_score,
                'recommendations' => $animal->recommendations
            ];
        }),
        'farmHealth' => $farmHealthAnalysis
    ]);
});
