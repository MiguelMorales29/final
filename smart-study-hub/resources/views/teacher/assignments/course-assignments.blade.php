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

    @php
        $unassignedAssignments = $assignments->filter(fn($assignment) => !$assignment->course_week_id);
    @endphp

    <div class="space-y-8" x-data="collapsibleManager()">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Assignments by Term & Week</h2>
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <span>Total Assignments: <span class="font-semibold text-gray-900 dark:text-white" x-text="totalItems"></span></span>
                <span>•</span>
                <span>Total Weeks: <span class="font-semibold text-gray-900 dark:text-white" x-text="totalWeeks"></span></span>
            </div>
        </div>

        @forelse($course->terms as $term)
            @php
                $termAssignmentsCount = $term->subTerms->sum(function ($subTerm) {
                    return $subTerm->weeks->sum(function ($week) {
                        return $week->assignments->count();
                    });
                });
                $weeksCreated = $term->subTerms->sum(function ($subTerm) {
                    return $subTerm->weeks->count();
            });
        @endphp

            <x-collapsible-section
                :id="$term->id"
                :title="$term->name"
                :subtitle="$term->description"
                :count="$termAssignmentsCount"
                count-label="assignments"
                :badges="[
                    ['class' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200', 'text' => $term->total_weeks . ' planned'],
                    ['class' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200', 'text' => $weeksCreated . ' created']
                ]"
                level="term"
                :clickable="true">

                @if($term->subTerms->count() > 0)
                    <div class="space-y-4">
                        @foreach($term->subTerms as $subTerm)
                            <div class="border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h5 class="text-lg font-medium text-gray-900 dark:text-white">{{ $subTerm->title }}</h5>
                                    @if($subTerm->description)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ $subTerm->description }}</p>
                                    @endif
                                </div>

                                @if($subTerm->weeks->count() > 0)
                                    <div class="divide-y divide-gray-200 dark:divide-gray-600">
                                        @foreach($subTerm->weeks as $week)
                                            @php
                                                $weekAssignments = $week->assignments;
                                                $assignmentCount = $weekAssignments->count();
                                            @endphp
                                            <div class="p-4">
                                                <div class="flex items-center justify-between cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-2 -m-2 transition-colors duration-200"
                                                     @click="toggleWeek({{ $week->id }})">
                                                    <div class="flex items-center gap-3">
                                                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                                                        <div>
                                                            <h6 class="font-medium text-gray-900 dark:text-white">{{ $week->title }}</h6>
                                                            @if($week->description)
                                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $week->description }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $assignmentCount }} assignment{{ $assignmentCount === 1 ? '' : 's' }}
                    </span>
                                                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200"
                                                             :class="{ 'rotate-180': openWeeks[{{ $week->id }}] }"
                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </div>
                                    </div>

                                                <div x-show="openWeeks[{{ $week->id }}]"
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0 transform scale-95"
                                                     x-transition:enter-end="opacity-100 transform scale-100"
                                                     x-transition:leave="transition ease-in duration-150"
                                                     x-transition:leave-start="opacity-100 transform scale-100"
                                                     x-transition:leave-end="opacity-0 transform scale-95"
                                                     class="mt-4 space-y-4">
                                                    @if($assignmentCount > 0)
                                                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                                                            @foreach($weekAssignments as $assignment)
                                                                <div class="item-card bg-white dark:bg-gray-800 rounded-lg p-5 border border-gray-200 dark:border-gray-600 hover:shadow-md hover:scale-[1.01] transition-all duration-200">
                                                                    <div class="flex items-start justify-between gap-3 mb-3">
                                                                        <h4 class="flex-1 min-w-0 text-lg font-semibold text-gray-900 dark:text-white leading-snug truncate">{{ $assignment->title }}</h4>
                                                                        @if($assignment->is_published)
                                                                            <span class="flex-shrink-0 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 text-xs px-2 py-1 rounded-full">Published</span>
                                                                        @else
                                                                            <span class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200 text-xs px-2 py-1 rounded-full">Draft</span>
                                                                        @endif
                                                                    </div>

                                                                    @if($assignment->description)
                                                                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-3 prose prose-sm max-w-none line-clamp-3">
                                                                            {!! $assignment->description !!}
                                                                        </div>
                                                                    @endif

                                                                    <dl class="grid grid-cols-2 gap-3 text-sm text-gray-600 dark:text-gray-400">
                                                                        <div class="flex items-center gap-2">
                                                                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                            </svg>
                                                                            <span><span class="font-semibold text-gray-900 dark:text-white">{{ $assignment->points }}</span> pts</span>
                                                                        </div>
                                                                        <div class="flex items-center gap-2">
                                                                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                            </svg>
                                                                            <span>{{ $assignment->submissions->count() }} submission{{ $assignment->submissions->count() === 1 ? '' : 's' }}</span>
                                                                        </div>
                                                                        <div class="flex items-center gap-2">
                                                                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                            </svg>
                                                                            <span class="{{ $assignment->due_date && $assignment->due_date->isPast() ? 'text-red-600 dark:text-red-400 font-semibold' : '' }}">
                                                                                {{ $assignment->due_date ? $assignment->due_date->format('M j, Y g:i A') : 'No due date' }}
                                                                            </span>
                                                                        </div>
                                                                        <div class="flex items-center gap-2">
                                                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                            </svg>
                                                                            <span>Max Attempts: {{ $assignment->max_attempts }}</span>
                                                                        </div>
                                                                    </dl>

                                                                    <div class="mt-4 flex items-center gap-2">
                                                                        <a href="{{ route('teacher.assignments.show', $assignment) }}"
                                                                           class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-center py-2 px-3 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                                                            View
                                                                        </a>
                                                                        <a href="{{ route('teacher.assignments.edit', $assignment) }}"
                                                                           class="flex-1 bg-blue-100 dark:bg-blue-900 hover:bg-blue-200 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-300 text-center py-2 px-3 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                                                            Edit
                                                                        </a>
                                                                        <form action="{{ route('teacher.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                    class="bg-red-100 dark:bg-red-900 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 py-2 px-3 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                                                                Delete
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="text-center py-6 text-sm text-gray-500 dark:text-gray-400 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                                                            <p>No assignments created for this week yet.</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-400 text-sm">
                                        No weeks defined for this section.
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 text-sm text-gray-500 dark:text-gray-400 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                        No sections defined for this term.
                    </div>
                @endif
            </x-collapsible-section>
        @empty
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center border border-gray-200 dark:border-gray-700">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Course Structure</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">This course doesn't have any terms or weeks defined yet.</p>
                <a href="{{ route('teacher.courses.edit', $course) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                    Edit Course Structure
                </a>
            </div>
        @endforelse

        @if($unassignedAssignments->count() > 0)
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"></path>
                        </svg>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Unassigned Assignments</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Assignments not yet linked to a specific week</p>
                        </div>
                    </div>
                    <span class="bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-200 text-sm px-2 py-1 rounded-full">
                        {{ $unassignedAssignments->count() }} item{{ $unassignedAssignments->count() === 1 ? '' : 's' }}
                                            </span>
                </div>
                <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($unassignedAssignments as $assignment)
                        <div class="item-card bg-white dark:bg-gray-800 rounded-lg p-5 border border-gray-200 dark:border-gray-600 hover:shadow-md transition duration-200">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <h4 class="flex-1 min-w-0 text-lg font-semibold text-gray-900 dark:text-white truncate">{{ $assignment->title }}</h4>
                                @if($assignment->is_published)
                                    <span class="flex-shrink-0 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 text-xs px-2 py-1 rounded-full">Published</span>
                                @else
                                    <span class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200 text-xs px-2 py-1 rounded-full">Draft</span>
                                @endif
                            </div>
                            @if($assignment->description)
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-3 prose prose-sm max-w-none line-clamp-3">
                                    {!! $assignment->description !!}
                                        </div>
                                    @endif
                            <div class="grid grid-cols-2 gap-3 text-sm text-gray-600 dark:text-gray-400">
                                <div><span class="font-semibold text-gray-900 dark:text-white">{{ $assignment->points }}</span> pts</div>
                                <div>{{ $assignment->submissions->count() }} submission{{ $assignment->submissions->count() === 1 ? '' : 's' }}</div>
                                <div class="{{ $assignment->due_date && $assignment->due_date->isPast() ? 'text-red-600 dark:text-red-400 font-semibold' : '' }}">
                                    {{ $assignment->due_date ? $assignment->due_date->format('M j, Y g:i A') : 'No due date' }}
                                </div>
                                <div>Max Attempts: {{ $assignment->max_attempts }}</div>
                            </div>
                            <div class="mt-4 flex items-center gap-2">
                                    <a href="{{ route('teacher.assignments.show', $assignment) }}" 
                                       class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-center py-2 px-3 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                        View
                                    </a>
                                    <a href="{{ route('teacher.assignments.edit', $assignment) }}" 
                                       class="flex-1 bg-blue-100 dark:bg-blue-900 hover:bg-blue-200 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-300 text-center py-2 px-3 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                        Edit
                                    </a>
                                <form action="{{ route('teacher.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-100 dark:bg-red-900 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 py-2 px-3 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                            Delete
                                        </button>
                                    </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @if($assignments->count() === 0)
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center border border-dashed border-gray-300 dark:border-gray-600">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Assignments Yet</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Create your first assignment to populate this course structure.</p>
            <a href="{{ route('teacher.assignments.create', $course) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                Upload Assignment
            </a>
        </div>
    @endif

    <x-collapsible-script />
</x-teacher-layout>
