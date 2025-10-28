<x-student-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Announcements</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Stay updated with the latest announcements from your courses</p>
    </div>

    @if($announcements->count() > 0)
        <!-- Group announcements by course -->
        @php
            $groupedAnnouncements = $announcements->groupBy('course_id');
        @endphp

        <div class="space-y-8">
            @foreach($groupedAnnouncements as $courseId => $courseAnnouncements)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                    <!-- Course Header -->
                    <div class="bg-blue-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">{{ $courseAnnouncements->first()->course->title }}</h2>
                    </div>

                    <!-- Announcements -->
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($courseAnnouncements as $announcement)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2 gap-3">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ 
                                                $announcement->event_type === 'quiz' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                                ($announcement->event_type === 'exam' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                                ($announcement->event_type === 'assignment' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'))
                                            }}">
                                                {{ $announcement->event_type_display }}
                                            </span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                by {{ $announcement->teacher->name }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                            {{ $announcement->title }}
                                        </h3>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-300 whitespace-pre-wrap mb-4">{{ $announcement->content }}</p>
                                
                                @if($announcement->event_date)
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-3">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7 a2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2"></path>
                                        </svg>
                                        Event Date: {{ $announcement->event_date->format('F j, Y g:i A') }}
                                    </div>
                                @endif
                                
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Posted {{ $announcement->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $announcements->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke boilerplatecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No announcements</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">There are no announcements available at this time.</p>
        </div>
    @endif
</x-student-layout>

