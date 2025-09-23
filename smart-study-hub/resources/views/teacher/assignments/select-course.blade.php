<x-teacher-layout>
    <!-- Page Header -->
    <div class="flex items-center mb-8">
        <a href="{{ route('teacher.dashboard') }}" 
           class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Upload Assignment</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Select a course to upload assignments to</p>
        </div>
    </div>

    @if($courses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-200">
                    @if($course->image)
                        <img src="{{ Storage::url($course->image) }}" 
                             alt="{{ $course->title }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h3>
                        @if($course->section)
                            <p class="text-gray-600 dark:text-gray-400 mb-2">Section {{ $course->section }}</p>
                        @endif
                        <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">{{ $course->description }}</p>
                        
                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <span>{{ $course->terms->count() }} Terms</span>
                            <span>{{ $course->terms->sum(function($term) { return $term->weeks->count(); }) }} Weeks</span>
                            <span>{{ $course->assignments->count() }} Assignments</span>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('teacher.assignments.create', $course) }}" 
                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out text-center">
                                Upload Assignment
                            </a>
                            <a href="{{ route('teacher.course.assignments', $course) }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Courses Found</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">You haven't created any courses yet.</p>
            <a href="{{ route('teacher.courses.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                Create Your First Course
            </a>
        </div>
    @endif
</x-teacher-layout>



