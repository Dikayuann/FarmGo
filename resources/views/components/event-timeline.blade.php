<div class="bg-white rounded-xl shadow-md p-6" x-data="eventTimeline(@js($upcomingEvents))">
    <!-- Timeline Header -->
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Event Mendatang</h3>
        <span class="text-sm text-gray-500" x-text="visibleEvents.length + ' event'"></span>
    </div>

    <!-- Empty State -->
    <div x-show="visibleEvents.length === 0" class="text-center py-8">
        <div class="text-gray-400 mb-2">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <p class="text-gray-600">Tidak ada event mendatang</p>
        <p class="text-sm text-gray-400 mt-1">Event akan muncul otomatis dari data ternak Anda</p>
    </div>

    <!-- Event List -->
    <div x-show="visibleEvents.length > 0" class="space-y-3 max-h-96 overflow-y-auto">
        <template x-for="event in visibleEvents" :key="event.id">
            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition cursor-pointer border border-gray-100"
                @click="viewEventDetail(event)">

                <!-- Event Icon & Color Bar -->
                <div class="flex-shrink-0">
                    <div :class="{
                        'bg-red-100 text-red-600': event.type_color === 'red',
                        'bg-green-100 text-green-600': event.type_color === 'green',
                        'bg-blue-100 text-blue-600': event.type_color === 'blue',
                        'bg-pink-100 text-pink-600': event.type_color === 'pink',
                        'bg-yellow-100 text-yellow-600': event.type_color === 'yellow'
                    }" class="w-10 h-10 rounded-lg flex items-center justify-center text-xl">
                        <span x-text="event.type_icon"></span>
                    </div>
                </div>

                <!-- Event Details -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 truncate" x-text="event.title"></h4>
                            <p class="text-sm text-gray-600 mt-0.5"
                                x-text="event.animal ? event.animal.nama : 'General'"></p>
                        </div>

                        <!-- Countdown Badge -->
                        <span :class="{
                            'bg-red-100 text-red-700': event.days_until <= 3,
                            'bg-orange-100 text-orange-700': event.days_until > 3 && event.days_until <= 7,
                            'bg-blue-100 text-blue-700': event.days_until > 7
                        }" class="flex-shrink-0 px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap"
                            x-text="event.countdown_text">
                        </span>
                    </div>

                    <!-- Event Date -->
                    <div class="flex items-center gap-2 mt-2 text-xs text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span x-text="formatDate(event.event_date)"></span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex-shrink-0 flex items-center gap-1">
                    <button @click.stop="markComplete(event.id)"
                        class="p-1.5 hover:bg-green-100 rounded-lg transition text-gray-400 hover:text-green-600"
                        title="Tandai selesai">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Filter Info -->
    <div x-show="filterDate" class="mt-4 pt-4 border-t">
        <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600">Filter: <span x-text="formatDate(filterDate)"></span></span>
            <button @click="clearFilter()" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                Tampilkan Semua
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('eventTimeline', (events) => ({
            allEvents: events,
            visibleEvents: events,
            filterDate: null,

            init() {
                // Listen for date filter from calendar
                window.addEventListener('filter-timeline', (e) => {
                    this.filterByDate(e.detail.date);
                });

                // Listen for event updates from calendar month changes
                window.addEventListener('update-timeline-events', (e) => {
                    this.allEvents = e.detail.events;
                    this.visibleEvents = this.allEvents;
                });
            },

            filterByDate(dateStr) {
                this.filterDate = dateStr;

                console.log('Filtering by date:', dateStr);
                console.log('All events:', this.allEvents);

                // Normalize date format for comparison (YYYY-MM-DD)
                this.visibleEvents = this.allEvents.filter(event => {
                    // event.event_date might be "2026-02-21" or "2026-02-21 00:00:00" or Date object
                    let eventDate;
                    if (typeof event.event_date === 'string') {
                        eventDate = event.event_date.split(' ')[0]; // Get just the date part
                    } else if (event.event_date instanceof Date) {
                        eventDate = event.event_date.toISOString().split('T')[0];
                    } else {
                        eventDate = String(event.event_date).split(' ')[0];
                    }

                    console.log('Comparing:', eventDate, '===', dateStr, '?', eventDate === dateStr);
                    return eventDate === dateStr;
                });

                console.log('Filtered events:', this.visibleEvents);
            },

            clearFilter() {
                this.filterDate = null;
                this.visibleEvents = this.allEvents;
            },

            formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('id-ID', {
                    weekday: 'short',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            },

            viewEventDetail(event) {
                // TODO: Show modal with event details
                console.log('Event detail:', event);
            },

            async markComplete(eventId) {
                if (!confirm('Tandai event ini sebagai selesai?')) return;

                try {
                    const response = await fetch(`/api/calendar-events/${eventId}/complete`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        // Remove from list
                        this.allEvents = this.allEvents.filter(e => e.id !== eventId);
                        this.visibleEvents = this.visibleEvents.filter(e => e.id !== eventId);

                        // Show success toast
                        if (window.Toast) {
                            window.Toast.fire({
                                icon: 'success',
                                title: 'Event ditandai selesai'
                            });
                        }
                    } else {
                        throw new Error('Gagal menandai event');
                    }
                } catch (error) {
                    alert('Gagal menandai event sebagai selesai');
                    console.error(error);
                }
            }
        }));
    });
</script>