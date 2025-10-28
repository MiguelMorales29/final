<x-student-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Calendar</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">View your upcoming events and deadlines</p>
    </div>

    {{-- Calendar Component --}}
    @php
        $today = now();
        $start = \Carbon\Carbon::create($year ?? now()->year, $month ?? now()->month, 1);
        $currentDate = $start->copy();
        $daysInMonth = $start->daysInMonth;
        $startDow = $start->dayOfWeek; // 0=Sun
        
        // Calculate previous and next month URLs
        $prevMonth = $start->copy()->subMonth();
        $nextMonth = $start->copy()->addMonth();
        
        // Use real calendar marks from controller
        $realCalendarMarks = $calendarMarks ?? [];
        $markDot = [
            'exam'=>'bg-blue-500',
            'assignment'=>'bg-orange-500',
            'deadline'=>'bg-red-500',
            'quiz'=>'bg-red-500',
            'custom'=>'bg-purple-500'
        ];
    @endphp

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $start->format('F Y') }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Your monthly schedule overview</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('student.calendar', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}" 
                   class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <a href="{{ route('student.calendar', ['year' => $today->year, 'month' => $today->month]) }}" 
                   class="px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-sm font-medium text-gray-700 dark:text-gray-300">
                    Today
                </a>
                <a href="{{ route('student.calendar', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}" 
                   class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-7 text-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
            </div>

            <div class="grid grid-cols-7 gap-2 text-sm">
                {{-- leading blanks --}}
                @for($i=0;$i<$startDow;$i++)
                    <div class="h-16"></div>
                @endfor

                {{-- days --}}
                @for($d=1;$d<=$daysInMonth;$d++)
                    @php
                        $isToday = ($today->day==$d && $today->month==$start->month && $today->year==$start->year);
                        $dayKey = $start->copy()->day($d)->format('Y-m-d');
                        $dayEvents = $calendarEventsByDate[$dayKey] ?? [];
                        
                        // Get unique badge colors from events (max 5)
                        $uniqueBadges = [];
                        foreach($dayEvents as $event) {
                            if (!in_array($event['badge_color'], $uniqueBadges) && count($uniqueBadges) < 5) {
                                $uniqueBadges[] = $event['badge_color'];
                            }
                        }
                        $totalEvents = count($dayEvents);
                        $showMoreIndicator = $totalEvents > count($uniqueBadges);
                    @endphp
                    <div 
                        @if(count($dayEvents) > 0)
                        x-data="{ showAbove: true }"
                        @mouseenter="
                            const rect = $event.currentTarget.getBoundingClientRect();
                            const tooltipHeight = 180;
                            const spaceAbove = rect.top;
                            const spaceBelow = window.innerHeight - rect.bottom;
                            showAbove = spaceAbove > spaceBelow || spaceBelow < tooltipHeight;
                        "
                        @endif
                        class="relative h-16 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800/60 transition group {{ count($dayEvents) > 0 ? 'cursor-pointer' : '' }}"
                        @if(count($dayEvents) > 0)
                        onclick="openEventModal('{{ $dayKey }}', @js($dayEvents))"
                        @endif>
                        <span class="text-gray-900 dark:text-gray-100 {{ $isToday ? 'inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-600 text-white font-bold' : '' }}">
                            {{ $d }}
                        </span>
                        
                        @if(count($uniqueBadges) > 0)
                            <div class="absolute bottom-1 left-1/2 transform -translate-x-1/2 flex items-center gap-0.5 px-1">
                                @foreach($uniqueBadges as $badgeColor)
                                    <span class="h-1.5 w-1.5 rounded-full {{ $badgeColor === 'blue' ? 'bg-blue-500' : ($badgeColor === 'orange' ? 'bg-orange-500' : ($badgeColor === 'red' ? 'bg-red-500' : 'bg-purple-500')) }}"></span>
                                @endforeach
                                @if($showMoreIndicator)
                                    <span class="text-[8px] text-gray-500 dark:text-gray-400 font-medium ml-0.5">+{{ $totalEvents - count($uniqueBadges) }}</span>
                                @endif
                            </div>
                        @endif
                        
                        {{-- Hover Tooltip --}}
                        @if(count($dayEvents) > 0)
                            <div 
                                :class="showAbove ? 'bottom-full mb-3' : 'top-full mt-3'"
                                class="absolute left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-all duration-200 ease-out pointer-events-none z-50 w-64">
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs rounded-xl shadow-2xl overflow-hidden">
                                    <div class="bg-gradient-to-r from-indigo-500 to-blue-500 px-4 py-3 border-b border-indigo-400">
                                        <div class="font-bold text-white text-sm">{{ $start->copy()->day($d)->format('M d, Y') }}</div>
                                        <div class="text-white/80 text-xs mt-0.5">{{ count($dayEvents) }} event{{ count($dayEvents) > 1 ? 's' : '' }}</div>
                                    </div>
                                    <div class="py-2 px-3 max-h-60 overflow-y-auto">
                                        @foreach(array_slice($dayEvents, 0, 3) as $event)
                                            <div class="flex items-start gap-3 py-2 px-3 rounded-lg first:pt-0 last:pb-0">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-semibold uppercase tracking-wide
                                                        {{ $event['badge_color'] === 'blue' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                                        {{ $event['badge_color'] === 'orange' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300' : '' }}
                                                        {{ $event['badge_color'] === 'red' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' : '' }}
                                                        {{ $event['badge_color'] === 'purple' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : '' }}">
                                                        {{ $event['type_display'] }}
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-gray-900 dark:text-white font-medium leading-snug break-words">{{ $event['title'] }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if(count($dayEvents) > 3)
                                            <div class="text-center text-xs text-gray-500 dark:text-gray-400 pt-2">
                                                Click to see all {{ count($dayEvents) }} events
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>

        {{-- Legend --}}
        <div class="p-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
            <div class="flex items-center justify-center gap-8 text-sm text-gray-600 dark:text-gray-300">
                <span class="inline-flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span> Exam
                </span>
                <span class="inline-flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-orange-500"></span> Assignment
                </span>
                <span class="inline-flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-red-500"></span> Quiz
                </span>
                <span class="inline-flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-purple-500"></span> Other Events
                </span>
            </div>
        </div>
    </div>

    {{-- Event Modal --}}
    <div id="eventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white" id="modalDate"></h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" id="modalEventCount"></p>
                </div>
                <button onclick="closeEventModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]" id="modalEventsContent">
                <!-- Events will be populated here -->
            </div>
        </div>
    </div>

    <script>
        function openEventModal(dateKey, events) {
            const modal = document.getElementById('eventModal');
            const modalDate = document.getElementById('modalDate');
            const modalEventCount = document.getElementById('modalEventCount');
            const modalEventsContent = document.getElementById('modalEventsContent');

            // Parse date
            const [year, month, day] = dateKey.split('-');
            const date = new Date(year, month - 1, day);
            modalDate.textContent = date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            modalEventCount.textContent = events.length + ' event' + (events.length !== 1 ? 's' : '');

            // Populate events
            modalEventsContent.innerHTML = events.map(event => `
                <a href="${event.url}" class="block mb-4 last:mb-0">
                    <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wide
                                    ${event.badge_color === 'blue' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : ''}
                                    ${event.badge_color === 'orange' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300' : ''}
                                    ${event.badge_color === 'red' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' : ''}
                                    ${event.badge_color === 'purple' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : ''}">
                                    ${event.type_display}
                                </span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">${event.title}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Click to view details</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            `).join('');

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEventModal() {
            const modal = document.getElementById('eventModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close modal on outside click
        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEventModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEventModal();
            }
        });
    </script>
</x-student-layout>

