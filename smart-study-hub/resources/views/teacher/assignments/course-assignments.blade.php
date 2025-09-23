<x-teacher-layout>
    <!-- Page Header -->
    <div class="flex items-center mb-8">
        <a href="{{ route('teacher.assignments.index') }}" 
           class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $course->title }}</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Assignments for {{ $course->section }}</p>
        </div>
        <a href="{{ route('teacher.assignments.create', $course) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
            + Upload Assignment
        </a>
    </div>

    <!-- Course Image and Info -->
    <div class="mb-8 bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        @if($course->image)
            <img src="{{ Storage::url($course->image) }}" 
                 alt="{{ $course->title }}" 
                 class="w-full h-64 object-cover">
        @else
            <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        @endif
        
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $course->section }}</p>
            @if($course->description)
                <p class="text-gray-700 dark:text-gray-300">{{ Str::limit($course->description, 200) }}</p>
            @endif
        </div>
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

    <!-- Course Stats -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $assignments->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total Assignments</div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $assignments->where('is_published', true)->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Published</div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $assignments->where('is_published', false)->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Draft</div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $assignments->sum(function($assignment) { return $assignment->submissions->count(); }) }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total Submissions</div>
            </div>
        </div>
    </div>

    <!-- Assignments List Grouped by Term and Week -->
    @if($assignments->count() > 0)
        @php
            $groupedAssignments = $assignments->groupBy(function($assignment) {
                if ($assignment->week) {
                    return $assignment->week->term->name . ' - ' . $assignment->week->title;
                }
                return 'Unassigned';
            });
        @endphp

        @foreach($groupedAssignments as $groupName => $groupAssignments)
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ $groupName }}
                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm px-2 py-1 rounded-full">
                        {{ $groupAssignments->count() }} Assignment{{ $groupAssignments->count() > 1 ? 's' : '' }}
                    </span>
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($groupAssignments as $assignment)
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                            <!-- Assignment Header -->
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">{{ $assignment->title }}</h4>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($assignment->is_published)
                                            <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs px-2 py-1 rounded-full">Published</span>
                                        @else
                                            <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-xs px-2 py-1 rounded-full">Draft</span>
                                        @endif
                                    </div>
                                </div>

                                @if($assignment->description)
                                    <div class="text-gray-700 dark:text-gray-300 text-sm mb-4 line-clamp-2 prose prose-sm max-w-none">
                                        {!! $assignment->description !!}
                                    </div>
                                @endif

                                <!-- Assignment Details -->
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Points:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $assignment->points }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Submissions:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $assignment->submissions->count() }}</span>
                                    </div>
                                    @if($assignment->due_date)
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Due Date:</span>
                                            <span class="font-semibold {{ $assignment->due_date->isPast() ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                                {{ $assignment->due_date->format('M j, Y g:i A') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="p-6">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('teacher.assignments.show', $assignment) }}" 
                                       class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-center py-2 px-3 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                        View
                                    </a>
                                    <a href="{{ route('teacher.assignments.edit', $assignment) }}" 
                                       class="flex-1 bg-blue-100 dark:bg-blue-900 hover:bg-blue-200 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-300 text-center py-2 px-3 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                        Edit
                                    </a>
                                    <form action="{{ route('teacher.assignments.destroy', $assignment) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-100 dark:bg-red-900 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 py-2 px-3 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Assignments Yet</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Create your first assignment for this course.</p>
            <a href="{{ route('teacher.assignments.create', $course) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                Upload Assignment
            </a>
        </div>
    @endif
</x-teacher-layout>
