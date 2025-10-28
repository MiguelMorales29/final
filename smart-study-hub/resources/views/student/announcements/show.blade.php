<x-student-layout>
    <div class="mb-8">
        <a href="{{ route('student.announcements.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Announcements
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $announcement->title }}</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $announcement->course->title }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white text-blue-800">
                    {{ ucfirst($announcement->event_type === 'other' ? ($announcement->custom_event_type ?? 'Announcement') : $announcement->event_type) }}
                </span>
                <span class="text-white">Posted by {{ $announcement->teacher->name }}</span>
                @if($announcement->event_date)
                    <span class="text-white">• {{ $announcement->event_date->format('M j, Y g:i A') }}</span>
                @endif
            </div>
        </div>
        <div class="p-8">
            <div class="prose prose-gray dark:prose-invert max-w-none">{!! $announcement->content !!}</div>
        </div>
    </div>
</x-student-layout>


