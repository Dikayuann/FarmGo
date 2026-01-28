<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" x-data="cleanCalendar(@js($calendarData))">
    <!-- Calendar Header with Selectors -->
    <div class="flex items-center justify-between mb-6">
        <!-- Month & Year Selectors -->
        <div class="flex items-center gap-2">
            <select @change="changeMonth()" x-model="currentMonth"
                class="text-lg font-semibold text-gray-800 bg-transparent border-none focus:ring-0 cursor-pointer hover:text-emerald-600 transition">
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
            <select @change="changeMonth()" x-model="currentYear"
                class="text-lg font-semibold text-gray-800 bg-transparent border-none focus:ring-0 cursor-pointer hover:text-emerald-600 transition">
                <template x-for="y in yearRange">
                    <option :value="y" x-text="y"></option>
                </template>
            </select>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex items-center gap-2">
            <button @click="prevMonth()"
                class="p-2 hover:bg-gray-50 rounded-lg transition text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button @click="today()"
                class="px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg transition">
                Hari Ini
            </button>
            <button @click="nextMonth()"
                class="p-2 hover:bg-gray-50 rounded-lg transition text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Weekday Headers -->
    <div class="grid grid-cols-7 gap-2 mb-2">
        <template x-for="day in ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']">
            <div class="text-center text-xs font-semibold text-gray-500 py-2" x-text="day"></div>
        </template>
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 gap-2">
        <template x-for="(day, index) in calendarDays" :key="'day-'+index">
            <div>
                <!-- Day Cell -->
                <div @click="selectDay(day)" :class="{
                        'bg-emerald-500 text-white font-semibold': day.isToday,
                        'bg-gray-50 text-gray-300': !day.isCurrentMonth,
                        'hover:bg-gray-50': !day.isToday && day.isCurrentMonth,
                        'cursor-pointer': day.isCurrentMonth,
                        'ring-2 ring-emerald-200': selectedDate === day.dateStr
                    }"
                    class="aspect-square flex flex-col items-center justify-center rounded-lg transition-all relative group">

                    <!-- Day Number -->
                    <span class="text-sm" x-text="day.number"></span>

                    <!-- Event Indicators -->
                    <div x-show="day.events && day.events.length > 0" class="absolute bottom-1 flex gap-0.5">
                        <template x-for="event in day.events.slice(0, 3)">
                            <span :class="{
                                'bg-red-500': event.type_color === 'red',
                                'bg-green-500': event.type_color === 'green',
                                'bg-blue-500': event.type_color === 'blue',
                                'bg-pink-500': event.type_color === 'pink',
                                'bg-yellow-500': event.type_color === 'yellow'
                            }" class="w-1 h-1 rounded-full"></span>
                        </template>
                        <span x-show="day.events.length > 3" class="text-[8px] text-gray-400 ml-0.5">
                            +
                        </span>
                    </div>

                    <!-- Tooltip on hover -->
                    <div x-show="day.events && day.events.length > 0"
                        class="absolute bottom-full mb-2 hidden group-hover:block z-10 w-48">
                        <div class="bg-gray-900 text-white text-xs rounded-lg p-2 shadow-lg">
                            <template x-for="event in day.events.slice(0, 2)">
                                <div class="truncate">
                                    <span x-text="event.type_icon"></span>
                                    <span x-text="event.title"></span>
                                </div>
                            </template>
                            <div x-show="day.events.length > 2" class="text-gray-400 mt-1">
                                +<span x-text="day.events.length - 2"></span> lainnya
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Event Legend -->
    <div class="mt-6 pt-4 border-t border-gray-100">
        <div class="flex items-center justify-between text-xs">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    <span class="text-gray-600">Vaksinasi</span>
                </div>
                <div class="flex items-center gap-1">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    <span class="text-gray-600">Kelahiran</span>
                </div>
                <div class="flex items-center gap-1">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    <span class="text-gray-600">Checkup</span>
                </div>
                <div class="flex items-center gap-1">
                    <span class="w-2 h-2 bg-pink-500 rounded-full"></span>
                    <span class="text-gray-600">Birahi</span>
                </div>
            </div>
            <span class="text-gray-400" x-text="eventCount + ' event'"></span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cleanCalendar', (data) => ({
            currentMonth: {{ $calendarData['month'] }},
            currentYear: {{ $calendarData['year'] }},
            eventsData: data.events_by_date || {},
            selectedDate: null,
            calendarDays: [],

            get yearRange() {
                const currentYear = new Date().getFullYear();
                // Current year + 3 years forward
                return Array.from({ length: 4 }, (_, i) => currentYear + i);
            },

            get eventCount() {
                return Object.keys(this.eventsData).length;
            },

            init() {
                this.generateCalendar();
            },

            async changeMonth() {
                // Fetch new calendar data via AJAX
                await this.loadCalendarData(this.currentMonth, this.currentYear);
            },

            async loadCalendarData(month, year) {
                try {
                    const response = await fetch(`/api/calendar-data?month=${month}&year=${year}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            // Update events data
                            this.eventsData = data.events_by_date || {};

                            // Flatten events for timeline
                            const allMonthEvents = [];
                            Object.values(this.eventsData).forEach(dateEvents => {
                                allMonthEvents.push(...dateEvents);
                            });

                            // Update event timeline with new month's events
                            window.dispatchEvent(new CustomEvent('update-timeline-events', {
                                detail: { events: allMonthEvents }
                            }));

                            // Regenerate calendar with new data
                            this.generateCalendar();
                        }
                    }
                } catch (error) {
                    console.error('Failed to load calendar:', error);
                }
            },

            generateCalendar() {
                this.calendarDays = [];
                const firstDay = new Date(this.currentYear, this.currentMonth - 1, 1);
                const lastDay = new Date(this.currentYear, this.currentMonth, 0);
                const daysInMonth = lastDay.getDate();

                // Start day (0 = Sunday, adjust to Monday = 0)
                let startDay = firstDay.getDay();
                startDay = startDay === 0 ? 6 : startDay - 1;

                // Previous month days
                const prevMonthLastDay = new Date(this.currentYear, this.currentMonth - 1, 0).getDate();
                for (let i = startDay - 1; i >= 0; i--) {
                    const dayNum = prevMonthLastDay - i;
                    const prevMonth = this.currentMonth === 1 ? 12 : this.currentMonth - 1;
                    const prevYear = this.currentMonth === 1 ? this.currentYear - 1 : this.currentYear;
                    const dateStr = `${prevYear}-${String(prevMonth).padStart(2, '0')}-${String(dayNum).padStart(2, '0')}`;

                    this.calendarDays.push({
                        number: dayNum,
                        isCurrentMonth: false,
                        isToday: false,
                        dateStr: dateStr,
                        events: this.eventsData[dateStr] || []
                    });
                }

                // Current month days
                const today = new Date();
                const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

                for (let day = 1; day <= daysInMonth; day++) {
                    const dateStr = `${this.currentYear}-${String(this.currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                    this.calendarDays.push({
                        number: day,
                        isCurrentMonth: true,
                        isToday: dateStr === todayStr,
                        dateStr: dateStr,
                        events: this.eventsData[dateStr] || []
                    });
                }

                // Next month days to fill grid
                const remainingDays = 42 - this.calendarDays.length; // 6 weeks
                const nextMonth = this.currentMonth === 12 ? 1 : this.currentMonth + 1;
                const nextYear = this.currentMonth === 12 ? this.currentYear + 1 : this.currentYear;

                for (let day = 1; day <= remainingDays; day++) {
                    const dateStr = `${nextYear}-${String(nextMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                    this.calendarDays.push({
                        number: day,
                        isCurrentMonth: false,
                        isToday: false,
                        dateStr: dateStr,
                        events: this.eventsData[dateStr] || []
                    });
                }
            },

            selectDay(day) {
                if (!day.isCurrentMonth) return;

                this.selectedDate = day.dateStr;

                // Emit event to filter timeline
                window.dispatchEvent(new CustomEvent('filter-timeline', {
                    detail: { date: day.dateStr }
                }));
            },

            prevMonth() {
                if (this.currentMonth === 1) {
                    this.currentMonth = 12;
                    this.currentYear--;
                } else {
                    this.currentMonth--;
                }
                this.changeMonth();
            },

            nextMonth() {
                if (this.currentMonth === 12) {
                    this.currentMonth = 1;
                    this.currentYear++;
                } else {
                    this.currentMonth++;
                }
                this.changeMonth();
            },

            today() {
                const now = new Date();
                this.currentMonth = now.getMonth() + 1;
                this.currentYear = now.getFullYear();
                this.generateCalendar();
            }
        }));
    });
</script>