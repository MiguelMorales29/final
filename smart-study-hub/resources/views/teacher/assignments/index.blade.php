<x-teacher-layout>
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Upload Assignment</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Create and manage assignments for your courses</p>
        </div>
        <a href="{{ route('teacher.assignments.select-course') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
            + Create Assignment
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Course Cards -->
    @if($courses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                    <!-- Course Image -->
                    @if($course->image)
                        <img src="{{ Storage::url($course->image) }}" 
                             alt="{{ $course->title }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Course Header -->
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $course->section }}</p>
                            </div>
                            <div class="flex items-center">
                                @if($course->assignments_count > 0)
                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded-full">
                                        {{ $course->assignments_count }} Assignment{{ $course->assignments_count > 1 ? 's' : '' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Course Stats -->
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $course->terms->count() }}</div>
                                <div class="text-gray-600 dark:text-gray-400">Terms</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $course->terms->sum(function($term) { return $term->weeks->count(); }) }}</div>
                                <div class="text-gray-600 dark:text-gray-400">Weeks</div>
                            </div>
                        </div>

                        <!-- Assignment Stats -->
                        @if($course->assignments_count > 0)
                            <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                                <div class="flex items-center justify-between p-2 bg-green-50 dark:bg-green-900/20 rounded">
                                    <span class="text-green-700 dark:text-green-300">Published</span>
                                    <span class="font-semibold text-green-800 dark:text-green-200">{{ $course->published_assignments_count }}</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded">
                                    <span class="text-yellow-700 dark:text-yellow-300">Draft</span>
                                    <span class="font-semibold text-yellow-800 dark:text-yellow-200">{{ $course->draft_assignments_count }}</span>
                                </div>
                            </div>
                            <div class="mt-2 text-center p-2 bg-blue-50 dark:bg-blue-900/20 rounded">
                                <span class="text-blue-700 dark:text-blue-300 text-xs">{{ $course->total_submissions }} Total Submissions</span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="p-6">
                        <div class="flex gap-3">
                            <a href="{{ route('teacher.assignments.create', $course) }}" 
                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                Upload Assignment
                            </a>
                            <a href="{{ route('teacher.course.assignments', $course) }}" 
                               class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-center py-2 px-4 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Courses Available</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">You need to create a course first before uploading assignments.</p>
            <a href="{{ route('teacher.courses.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                Create Course
            </a>
        </div>
    @endif
</x-teacher-layout>



