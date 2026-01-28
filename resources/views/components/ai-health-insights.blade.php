<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" id="ai-health-widget">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-2 rounded-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                    </path>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-800">AI Health Insights</h3>
        </div>
        <button onclick="refreshAIWidget()" id="refresh-btn"
            class="p-1.5 hover:bg-gray-100 rounded-lg transition text-gray-500 hover:text-gray-700"
            title="Refresh insights">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                </path>
            </svg>
        </button>
    </div>

    <!-- Farm Health Summary -->
    <div class="mb-6 p-4 bg-gradient-to-r from-emerald-50 to-blue-50 rounded-lg border border-emerald-100">
        <div class="grid grid-cols-3 gap-3">
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600" id="high-risk-count">{{ $farmHealthAnalysis['high_risk'] }}
                </div>
                <div class="text-xs text-gray-600">Risiko Tinggi</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600" id="medium-risk-count">
                    {{ $farmHealthAnalysis['medium_risk'] }}</div>
                <div class="text-xs text-gray-600">Risiko Sedang</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600" id="low-risk-count">{{ $farmHealthAnalysis['low_risk'] }}
                </div>
                <div class="text-xs text-gray-600">Sehat</div>
            </div>
        </div>
    </div>

    <!-- High Risk Animals -->
    <div id="high-risk-animals">
        @if($highRiskAnimals->count() > 0)
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    Ternak Berisiko (<span id="risk-count">{{ $highRiskAnimals->count() }}</span>)
                </h4>

                <div class="space-y-3" id="risk-animals-list">
                    @foreach($highRiskAnimals as $animal)
                            <a href="{{ route('ternak.show', $animal->id) }}" class="block p-3 rounded-lg border transition hover:shadow-md
                                                    {{ $animal->risk_score >= 80 ? 'border-red-200 bg-red-50 hover:border-red-300' :
                        'border-orange-200 bg-orange-50 hover:border-orange-300' }}">

                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl">
                                            @if($animal->risk_score >= 80) 🔴
                                            @else 🟠
                                            @endif
                                        </span>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $animal->kode_hewan }}</div>
                                            <div class="text-xs text-gray-600">{{ $animal->nama }}</div>
                                        </div>
                                    </div>

                                    <!-- Risk Score Badge -->
                                    <div class="flex items-center gap-2">
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">Risk Score</div>
                                            <div
                                                class="font-bold text-lg 
                                                                {{ $animal->risk_score >= 80 ? 'text-red-600' : 'text-orange-600' }}">
                                                {{ $animal->risk_score }}
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                            </path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Top Recommendation -->
                                @if(count($animal->recommendations) > 0)
                                    <div class="text-xs text-gray-700 flex items-start gap-1.5">
                                        <svg class="w-3 h-3 text-gray-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                        <span class="line-clamp-2">{{ $animal->recommendations[0] }}</span>
                                    </div>
                                @endif
                            </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="mb-6 text-center py-8">
                <div class="text-4xl mb-2">✅</div>
                <div class="text-sm font-medium text-gray-700">Semua Ternak Sehat</div>
                <div class="text-xs text-gray-500">Tidak ada ternak berisiko tinggi</div>
            </div>
        @endif
    </div>

    <!-- Smart Recommendations -->
    <div class="pt-4 border-t border-gray-100">
        <h4 class="text-sm font-semibold text-gray-700 mb-3">💡 Rekomendasi Aksi</h4>

        <div class="space-y-2" id="recommendations-list">
            @foreach($farmHealthAnalysis['actions'] as $action)
                <div class="flex items-start gap-2 text-sm text-gray-700">
                    <span class="text-emerald-500 mt-0.5">•</span>
                    <span>{{ $action }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- AI Badge -->
    <div class="mt-4 pt-4 border-t border-gray-100">
        <div class="flex items-center justify-between text-xs text-gray-500">
            <div class="flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z">
                    </path>
                </svg>
                <span>Powered by Gemini AI</span>
            </div>
            <span id="update-time">Updated {{ now()->diffForHumans() }}</span>
        </div>
    </div>
</div>

<script>
    async function refreshAIWidget() {
        const btn = document.getElementById('refresh-btn');
        const widget = document.getElementById('ai-health-widget');

        // Add loading state
        btn.classList.add('animate-spin');
        btn.disabled = true;
        widget.style.opacity = '0.6';

        try {
            const response = await fetch('/api/ai-health-insights', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to fetch');

            const data = await response.json();

            // Update summary counts
            document.getElementById('high-risk-count').textContent = data.farmHealth.high_risk;
            document.getElementById('medium-risk-count').textContent = data.farmHealth.medium_risk;
            document.getElementById('low-risk-count').textContent = data.farmHealth.low_risk;

            // Update high-risk animals list
            const listContainer = document.getElementById('high-risk-animals');
            if (data.animals.length > 0) {
                listContainer.innerHTML = `
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                        Ternak Berisiko (<span>${data.animals.length}</span>)
                    </h4>
                    <div class="space-y-3">
                        ${data.animals.map(animal => `
                            <a href="/ternak/${animal.id}" class="block p-3 rounded-lg border transition hover:shadow-md ${animal.risk_score >= 80 ? 'border-red-200 bg-red-50 hover:border-red-300' : 'border-orange-200 bg-orange-50 hover:border-orange-300'}">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl">${animal.risk_score >= 80 ? '🔴' : '🟠'}</span>
                                        <div>
                                            <div class="font-semibold text-gray-900">${animal.kode_hewan}</div>
                                            <div class="text-xs text-gray-600">${animal.nama}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">Risk Score</div>
                                            <div class="font-bold text-lg ${animal.risk_score >= 80 ? 'text-red-600' : 'text-orange-600'}">${animal.risk_score}</div>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                ${animal.recommendations.length > 0 ? `
                                    <div class="text-xs text-gray-700 flex items-start gap-1.5">
                                        <svg class="w-3 h-3 text-gray-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <span class="line-clamp-2">${animal.recommendations[0]}</span>
                                    </div>
                                ` : ''}
                            </a>
                        `).join('')}
                    </div>
                </div>
            `;
            } else {
                listContainer.innerHTML = `
                <div class="mb-6 text-center py-8">
                    <div class="text-4xl mb-2">✅</div>
                    <div class="text-sm font-medium text-gray-700">Semua Ternak Sehat</div>
                    <div class="text-xs text-gray-500">Tidak ada ternak berisiko tinggi</div>
                </div>
            `;
            }

            // Update recommendations
            const recList = document.getElementById('recommendations-list');
            recList.innerHTML = data.farmHealth.actions.map(action => `
            <div class="flex items-start gap-2 text-sm text-gray-700">
                <span class="text-emerald-500 mt-0.5">•</span>
                <span>${action}</span>
            </div>
        `).join('');

            // Update timestamp
            document.getElementById('update-time').textContent = 'Updated just now';

        } catch (error) {
            console.error('Failed to refresh AI widget:', error);
            alert('Gagal refresh data. Silakan coba lagi.');
        } finally {
            // Remove loading state
            btn.classList.remove('animate-spin');
            btn.disabled = false;
            widget.style.opacity = '1';
        }
    }
</script>