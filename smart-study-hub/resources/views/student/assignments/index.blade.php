<x-student-layout>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Assignments</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">View and submit your course assignments</p>
        </div>
    </div>

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

    <!-- Filter Options -->
    <div class="mb-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4">
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter by:</span>
            <div class="flex items-center gap-2">
                <button id="filter-all" 
                        class="px-3 py-1 rounded-full text-sm font-medium transition duration-150 ease-in-out bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    All
                </button>
                <button id="filter-assignments" 
                        class="px-3 py-1 rounded-full text-sm font-medium transition duration-150 ease-in-out bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                    Assignments Only
                </button>
                <button id="filter-graded" 
                        class="px-3 py-1 rounded-full text-sm font-medium transition duration-150 ease-in-out bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                    Graded
                </button>
                <button id="filter-pending" 
                        class="px-3 py-1 rounded-full text-sm font-medium transition duration-150 ease-in-out bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                    Pending
                </button>
            </div>
        </div>
    </div>

    @if($assignments->count() > 0)
        <!-- Group assignments by course -->
        @php
            $groupedAssignments = $assignments->groupBy('course_id');
        @endphp

        <div class="space-y-6" x-data="{ openCourses: {} }">
            @foreach($groupedAssignments as $courseId => $courseAssignments)
                @php
                    $course = $courseAssignments->first()->course;
                @endphp
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                @if($course->image)
                                    <img src="{{ Storage::url($course->image) }}" 
                                         alt="{{ $course->title }}" 
                                         class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $course->title }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $course->teacher->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $courseAssignments->count() }} assignment(s)</p>
                                </div>
                            </div>
                            <button @click="openCourses['{{ $courseId }}'] = !openCourses['{{ $courseId }}']" 
                                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <svg class="w-6 h-6 transition-transform duration-200" 
                                     :class="{ 'rotate-180': openCourses['{{ $courseId }}'] }" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div x-show="openCourses['{{ $courseId }}']" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($courseAssignments as $assignment)
                                @php
                                    $submission = $assignment->submissions->first();
                                    $isOverdue = $assignment->due_date && $assignment->due_date->isPast() && (!$submission || $submission->status !== 'submitted');
                                @endphp
                                <div class="assignment-card bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600 {{ $isOverdue ? 'border-l-4 border-red-500' : '' }}" 
                                     data-status="{{ $submission ? $submission->status : 'not-started' }}">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $assignment->title }}</h4>
                                            @if($assignment->week)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $assignment->week->subTerm->term->name }} - {{ $assignment->week->subTerm->title }} - {{ $assignment->week->title }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($submission)
                                                @if($submission->status === 'graded')
                                                    <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs px-2 py-1 rounded-full">Graded</span>
                                                @elseif($submission->status === 'submitted')
                                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded-full">Submitted</span>
                                                @else
                                                    <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-xs px-2 py-1 rounded-full">Draft</span>
                                                @endif
                                            @else
                                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs px-2 py-1 rounded-full">Not Started</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($assignment->description)
                                        <div class="text-gray-700 dark:text-gray-300 text-sm mb-3 line-clamp-2 prose prose-sm max-w-none">
                                            {!! $assignment->description !!}
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-3">
                                        <div class="flex items-center gap-3">
                                            <span>{{ $assignment->points }} pts</span>
                                            <span class="capitalize">{{ $assignment->submission_type }}</span>
                                        </div>
                                        @if($assignment->due_date)
                                            <span class="{{ $isOverdue ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}">
                                                {{ $assignment->due_date->format('M j') }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($submission && $submission->status === 'graded')
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 mb-3">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">Grade</span>
                                                <span class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ $submission->points_earned }} / {{ $assignment->points }}
                                                </span>
                                            </div>
                                            @if($submission->feedback)
                                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ Str::limit($submission->feedback, 50) }}</p>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('student.assignments.show', $assignment) }}" 
                                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                            @if($submission)
                                                {{ $submission->status === 'graded' ? 'View Grade' : 'View/Edit' }}
                                            @else
                                                Start
                                            @endif
                                        </a>
                                        @if($submission && $submission->status !== 'graded')
                                            <form action="{{ route('student.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your submission?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-100 dark:bg-red-900 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 py-2 px-3 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
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
            <p class="text-gray-600 dark:text-gray-400 mb-4">You don't have any assignments yet. Check back later or contact your teachers.</p>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = {
                'filter-all': document.getElementById('filter-all'),
                'filter-assignments': document.getElementById('filter-assignments'),
                'filter-graded': document.getElementById('filter-graded'),
                'filter-pending': document.getElementById('filter-pending')
            };

            const assignmentCards = document.querySelectorAll('.assignment-card');

            function updateFilter(activeFilter) {
                // Update button styles
                Object.values(filterButtons).forEach(btn => {
                    btn.className = btn.className.replace('bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200', 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600');
                });
                
                activeFilter.className = activeFilter.className.replace('bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600', 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200');

                // Filter assignment cards
                assignmentCards.forEach(card => {
                    const status = card.getAttribute('data-status');
                    let show = true;

                    switch(activeFilter.id) {
                        case 'filter-assignments':
                            show = true; // Show all assignments
                            break;
                        case 'filter-graded':
                            show = status === 'graded';
                            break;
                        case 'filter-pending':
                            show = status === 'submitted' || status === 'draft' || status === 'not-started';
                            break;
                        case 'filter-all':
                        default:
                            show = true;
                            break;
                    }

                    card.style.display = show ? 'block' : 'none';
                });
            }

            // Add click event listeners
            Object.values(filterButtons).forEach(btn => {
                btn.addEventListener('click', () => updateFilter(btn));
            });

            // Initialize with all assignments shown
            updateFilter(filterButtons['filter-all']);
        });
    </script>
</x-student-layout>
