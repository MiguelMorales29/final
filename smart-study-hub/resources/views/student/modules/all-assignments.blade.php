<x-student-layout>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">All Assignments</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $course->title }}</p>
        </div>
        <a href="{{ route('student.modules.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Modules
        </a>
    </div>

    @if($assignments->count() > 0)
        <div class="space-y-8">
            @foreach($assignments as $status => $statusAssignments)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            @if($status === 'graded')
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @elseif($status === 'submitted')
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @elseif($status === 'draft')
                                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white capitalize">{{ $status === 'not-started' ? 'Not Started' : $status }} Assignments</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $statusAssignments->count() }} assignment(s)</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach($statusAssignments as $assignment)
                                @php
                                    $submission = $assignment->submissions->first();
                                    $isOverdue = $assignment->due_date && $assignment->due_date->isPast() && (!$submission || $submission->status !== 'submitted');
                                @endphp
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600 {{ $isOverdue ? 'border-l-4 border-red-500' : '' }}">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $assignment->title }}</h3>
                                            <p class="text-gray-700 dark:text-gray-300 mb-3">{{ $assignment->description }}</p>
                                            @if($assignment->instructions)
                                                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 mb-3">
                                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Instructions:</h4>
                                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $assignment->instructions }}</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 ml-4">
                                            @if($submission)
                                                @if($submission->status === 'graded')
                                                    <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm px-3 py-1 rounded-full">Graded</span>
                                                @elseif($submission->status === 'submitted')
                                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm px-3 py-1 rounded-full">Submitted</span>
                                                @else
                                                    <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-sm px-3 py-1 rounded-full">Draft</span>
                                                @endif
                                            @else
                                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm px-3 py-1 rounded-full">Not Started</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">Points</div>
                                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $assignment->points }}</div>
                                        </div>
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">Submission Type</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $assignment->submission_type }}</div>
                                        </div>
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">Due Date</div>
                                            <div class="text-sm {{ $isOverdue ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400' }}">
                                                @if($assignment->due_date)
                                                    {{ $assignment->due_date->format('M j, Y') }}
                                                @else
                                                    No due date
                                                @endif
                                            </div>
                                        </div>
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">Week</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                @if($assignment->week && $assignment->week->subTerm && $assignment->week->subTerm->term)
                                                    {{ $assignment->week->subTerm->term->name }} - {{ $assignment->week->subTerm->title }} - {{ $assignment->week->title }}
                                                @else
                                                    {{ $assignment->course->title }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if($submission && $submission->status === 'graded')
                                        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-4 mb-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="text-sm font-medium text-green-900 dark:text-green-200">Grade</h4>
                                                <span class="text-lg font-semibold text-green-900 dark:text-green-200">
                                                    {{ $submission->points_earned }} / {{ $assignment->points }}
                                                </span>
                                            </div>
                                            @if($submission->feedback)
                                                <p class="text-sm text-green-800 dark:text-green-300">{{ $submission->feedback }}</p>
                                            @endif
                                        </div>
                                    @endif

                                    @if($submission)
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-4">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Your Submission</h4>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                <p><strong>Status:</strong> {{ ucfirst($submission->status) }}</p>
                                                <p><strong>Submitted:</strong> {{ $submission->submitted_at ? $submission->submitted_at->format('M j, Y \a\t g:i A') : 'Not submitted' }}</p>
                                                @if($submission->text_submission)
                                                    <p><strong>Text Submission:</strong></p>
                                                    <div class="bg-gray-50 dark:bg-gray-700 rounded p-2 mt-1">
                                                        <p class="text-sm">{{ Str::limit($submission->text_submission, 200) }}</p>
                                                    </div>
                                                @endif
                                                @if($submission->file_submissions && count($submission->file_submissions) > 0)
                                                    <p class="mt-2"><strong>Files:</strong></p>
                                                    <div class="space-y-1 mt-1">
                                                        @foreach($submission->file_submissions as $file)
                                                            <div class="flex items-center gap-2 text-sm">
                                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                </svg>
                                                                <span>{{ $file['file_name'] }}</span>
                                                                <span class="text-gray-500">({{ number_format($file['file_size'] / 1024, 1) }} KB)</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('student.assignments.show', $assignment) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                            @if($submission)
                                                {{ $submission->status === 'graded' ? 'View Grade' : 'View/Edit Submission' }}
                                            @else
                                                Start Assignment
                                            @endif
                                        </a>
                                        @if($submission && $submission->status !== 'graded')
                                            <form action="{{ route('student.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your submission?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-100 dark:bg-red-900 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                                    Delete Submission
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
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
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Assignments Available</h3>
            <p class="text-gray-600 dark:text-gray-400">No assignments have been created for this course yet.</p>
        </div>
    @endif
</x-student-layout>

