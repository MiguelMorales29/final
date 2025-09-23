<x-teacher-layout>
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ route('teacher.assignments.index') }}" 
               class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $assignment->title }}</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $assignment->course->title }}</p>
                @if($assignment->week)
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $assignment->week->subTerm->term->name }} - {{ $assignment->week->subTerm->title }} - {{ $assignment->week->title }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('teacher.assignments.edit', $assignment) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                Edit Assignment
            </a>
            <form action="{{ route('teacher.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                    Delete Assignment
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Assignment Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Assignment Info -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Assignment Details</h2>
                    <div class="flex items-center gap-2">
                        @if($assignment->is_published)
                            <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm px-3 py-1 rounded-full">Published</span>
                        @else
                            <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-sm px-3 py-1 rounded-full">Draft</span>
                        @endif
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Description</h3>
                        <p class="text-gray-700 dark:text-gray-300">{{ $assignment->description }}</p>
                    </div>

                    @if($assignment->instructions)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Instructions</h3>
                            <p class="text-gray-700 dark:text-gray-300">{{ $assignment->instructions }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">Submission Type</h4>
                            <p class="text-gray-600 dark:text-gray-400 capitalize">{{ $assignment->submission_type }}</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">Points</h4>
                            <p class="text-gray-600 dark:text-gray-400">{{ $assignment->points }} points</p>
                        </div>
                        @if($assignment->due_date)
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white mb-1">Due Date</h4>
                                <p class="text-gray-600 dark:text-gray-400">{{ $assignment->due_date->format('M j, Y g:i A') }}</p>
                            </div>
                        @endif
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">Submissions</h4>
                            <p class="text-gray-600 dark:text-gray-400">{{ $assignment->submissions->count() }} students</p>
                        </div>
                    </div>

                    @if($assignment->submission_type !== 'text' && $assignment->allowed_file_types)
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">File Requirements</h4>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm">
                                    Max {{ $assignment->max_file_size }}MB per file
                                </span>
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm">
                                    Max {{ $assignment->max_files }} file(s)
                                </span>
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm">
                                    {{ implode(', ', array_map('strtoupper', $assignment->allowed_file_types)) }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submissions -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Student Submissions</h2>

                @if($assignment->submissions->count() > 0)
                    <div class="space-y-4">
                        @foreach($assignment->submissions as $submission)
                            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h3 class="font-medium text-gray-900 dark:text-white">{{ $submission->student->name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $submission->student->email }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($submission->status === 'graded')
                                            <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs px-2 py-1 rounded-full">Graded</span>
                                        @elseif($submission->status === 'submitted')
                                            <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded-full">Submitted</span>
                                        @else
                                            <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-xs px-2 py-1 rounded-full">Draft</span>
                                        @endif
                                        @if($submission->submitted_at)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $submission->submitted_at->format('M j, Y g:i A') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if($submission->text_submission)
                                    <div class="mb-3">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-1">Text Submission</h4>
                                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $submission->text_submission }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($submission->file_submissions && count($submission->file_submissions) > 0)
                                    <div class="mb-3">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">File Submissions</h4>
                                        <div class="space-y-2">
                                            @foreach($submission->file_submissions as $file)
                                                <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                                    <div class="flex items-center gap-3">
                                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $file['file_name'] }}</p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($file['file_size'] / 1024, 1) }} KB</p>
                                                        </div>
                                                    </div>
                                                    <a href="{{ Storage::url($file['file_path']) }}" 
                                                       download="{{ $file['file_name'] }}"
                                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($submission->status === 'graded')
                                    <div class="border-t border-gray-200 dark:border-gray-600 pt-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium text-gray-900 dark:text-white">Grade</h4>
                                            <span class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ $submission->points_earned }} / {{ $assignment->points }}
                                            </span>
                                        </div>
                                        @if($submission->feedback)
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $submission->feedback }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <!-- Grading Form -->
                                    <div class="border-t border-gray-200 dark:border-gray-600 pt-3">
                                        <form action="{{ route('teacher.assignments.grade', [$assignment, $submission]) }}" method="POST">
                                            @csrf
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="points_earned_{{ $submission->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Points Earned
                                                    </label>
                                                    <input type="number" 
                                                           id="points_earned_{{ $submission->id }}" 
                                                           name="points_earned" 
                                                           min="0" 
                                                           max="{{ $assignment->points }}"
                                                           class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label for="feedback_{{ $submission->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Feedback (Optional)
                                                    </label>
                                                    <textarea id="feedback_{{ $submission->id }}" 
                                                              name="feedback" 
                                                              rows="2"
                                                              class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                              placeholder="Add feedback..."></textarea>
                                                </div>
                                            </div>
                                            <button type="submit" 
                                                    class="mt-3 bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg transition duration-150 ease-in-out">
                                                Grade Submission
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Submissions Yet</h3>
                        <p class="text-gray-600 dark:text-gray-400">Students haven't submitted their work for this assignment yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Assignment Stats -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assignment Statistics</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Total Submissions</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $assignment->submissions->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Graded</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $assignment->submissions->where('status', 'graded')->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Pending</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $assignment->submissions->where('status', 'submitted')->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Draft</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $assignment->submissions->where('status', 'draft')->count() }}</span>
                    </div>

                    @if($assignment->submissions->where('status', 'graded')->count() > 0)
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Average Grade</span>
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ round($assignment->submissions->where('status', 'graded')->avg('points_earned'), 1) }} / {{ $assignment->points }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-teacher-layout>






