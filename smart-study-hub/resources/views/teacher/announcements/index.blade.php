<x-teacher-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Announcements</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Manage announcements for your courses</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('teacher.announcements.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md transition duration-150 ease-in-out">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Announcement
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-ps-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if($announcements->count() > 0)
        <div class="space-y-4">
            @foreach($announcements as $announcement)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ 
                                        $announcement->event_type === 'quiz' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                        ($announcement->event_type === 'exam' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                        ($announcement->event_type === 'assignment' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'))
                                    }}">
                                        {{ $announcement->event_type_display }}
                                    </span>
                                    <span class="ml-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $announcement->course->title }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    {{ $announcement->title }}
                                </h3>
                            </div>
                            <div class="ml-4 flex space-x-2">
                                <a href="{{ route('teacher.announcements.edit', $announcement) }}" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('teacher.announcements.destroy', $announcement) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <p class="text-gray-600 dark:text-gray-300 whitespace-pre-wrap">{{ $announcement->content }}</p>
                        
                        @if($announcement->event_date)
                            <div class="mt-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 v12a2 2"></path>
                                </svg>
                                Event Date: {{ $announcement->event_date->format('F j, Y g:i A') }}
                            </div>
                        @endif
                        
                        <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                            Created {{ $announcement->created_at->diffForHumans() }}
                        </div>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 5h6V1H4v4zM15 1v6h6V1h-6z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No announcements</h3>
            <p class="mt-可能在 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new announcement.</p>
            <div class="mt-6">
                <a href="{{ route('teacher.announcements.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Announcement
                </a>
            </div>
        </div>
    @endif
</x-teacher-layout>

