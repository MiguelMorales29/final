<x-student-layout>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Modules</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Access your course materials and resources</p>
        </div>
    </div>

    @if($courses->count() > 0)
        <div class="space-y-8" x-data>
            @foreach($courses as $course)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <!-- Course Header -->
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
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
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $course->title }}</h2>
                                <p class="text-gray-600 dark:text-gray-400">{{ $course->teacher->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $course->terms->sum(function($term) { 
                                        return $term->subTerms->sum(function($subTerm) { 
                                            return $subTerm->weeks->sum(function($week) { 
                                                return $week->materials->count(); 
                                            }); 
                                        }); 
                                    }) }} material(s) across {{ $course->terms->sum(function($term) { 
                                        return $term->subTerms->sum(function($subTerm) { 
                                            return $subTerm->weeks->count(); 
                                        }); 
                                    }) }} weeks
                                </p>
                                <!-- Progress Bar -->
                                <div class="mt-3 w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700" data-course-id="{{ $course->id }}">
                                    <div class="course-progress bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 course-progress-text">Loading progress...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Course Content -->
                    <div class="p-6">
                        @if($course->terms->count() > 0)
                            <div class="space-y-6" x-data="{ openTerms: {}, openSections: {}, openWeeks: {} }">
                                @foreach($course->terms as $term)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600">
                                        <div class="p-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200" @click="openTerms[{{ $term->id }}] = !openTerms[{{ $term->id }}]">
                                            <div class="flex items-center justify-between">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $term->name }}</h3>
                                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openTerms[{{ $term->id }}] }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>
                                        <div x-show="openTerms[{{ $term->id }}]" style="display: none;" class="p-4 pt-0">
                                        
                                        @if($term->subTerms->count() > 0)
                                            <div class="space-y-4">
                                                @foreach($term->subTerms as $subTerm)
                                                    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600">
                                        <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200" @click="openSections[{{ $subTerm->id }}] = !openSections[{{ $subTerm->id }}]">
                                                            <div class="flex items-center justify-between">
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 dark:text-white">{{ $subTerm->title }}</h4>
                                                                        @if($subTerm->description)
                                                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $subTerm->description }}</p>
                                                                        @endif
                                                                    </div>
                                                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openSections[{{ $subTerm->id }}] }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                            </div>
                                                        </div>
                                                        <div x-show="openSections[{{ $subTerm->id }}]" style="display: none;" class="p-4">
                                                        
                                                        @if($subTerm->weeks->count() > 0)
                                                            <div class="space-y-3" x-data="{ openWeeks: {} }">
                                                                @foreach($subTerm->weeks as $week)
                                                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden">
                                                                        <!-- Week Header (match teacher style) -->
                                                                        <div class="p-3 border-b border-gray-200 dark:border-gray-600">
                                                                            <div class="flex items-center justify-between cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-2 -m-2 transition-colors duration-200" @click="openWeeks['{{ $week->id }}'] = !openWeeks['{{ $week->id }}']">
                                                                                <div class="flex items-center gap-3">
                                                                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                                    </svg>
                                                                                    <div>
                                                                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">{{ $week->title }}</h5>
                                                                                        @if($week->notes)
                                                                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $week->notes }}</p>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openWeeks['{{ $week->id }}'] }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                                </svg>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Week Content -->
                                                                        <div x-show="openWeeks['{{ $week->id }}']" style="display: none;"
                                                                             x-transition:enter="transition ease-out duration-200"
                                                                             x-transition:enter-start="opacity-0 transform scale-95"
                                                                             x-transition:enter-end="opacity-100 transform scale-100"
                                                                             x-transition:leave="transition ease-in duration-150"
                                                                             x-transition:leave-start="opacity-100 transform scale-100"
                                                                             x-transition:leave-end="opacity-0 transform scale-95"
                                                                             class="p-4">
                                                                            <!-- Materials -->
                                                                            @if($week->materials->count() > 0)
                                                                                <div class="mb-4">
                                                                                    <h6 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Materials</h6>
                                                                                    <div class="space-y-2">
                                                                                        @foreach($week->materials as $material)
                                                                                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:shadow-sm transition-shadow">
                                                                                                <div class="flex items-center gap-3">
                                                                                                    @if($material->type === 'video')
                                                                                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                                                                            <path d="M8 5v14l11-7z"/>
                                                                                                        </svg>
                                                                                                    @elseif($material->type === 'file')
                                                                                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                                        </svg>
                                                                                                    @elseif($material->type === 'link')
                                                                                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                                                                        </svg>
                                                                                                    @else
                                                                                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                                        </svg>
                                                                                                    @endif
                                                                                                    <div>
                                                                                                        <h6 class="font-medium text-gray-900 dark:text-white">{{ $material->title }}</h6>
                                                                                                        @if($material->description)
                                                                                                            <div class="text-sm text-gray-600 dark:text-gray-400 prose prose-sm max-w-none">
                                                                                                                {!! Str::limit(strip_tags($material->description), 100) !!}
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="flex items-center gap-2">
                                                                                                    @if($material->is_required)
                                                                                                        <span class="text-xs bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-2 py-1 rounded font-medium">
                                                                                                            Required
                                                                                                        </span>
                                                                                                    @endif
                                                                                            <a href="{{ route('student.materials.show', $material) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors duration-150">View</a>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            <!-- Assignments -->
                                                                            @if($week->assignments->count() > 0)
                                                                                <div>
                                                                                    <h6 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Assignments</h6>
                                                                                    <div class="space-y-2">
                                                                                        @foreach($week->assignments as $assignment)
                                                                                            @php
                                                                                                $submission = $assignment->submissions()->where('student_id', auth()->id())->first();
                                                                                            @endphp
                                                                                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:shadow-sm transition-shadow">
                                                                                                <div class="flex items-center gap-3">
                                                                                                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                                    </svg>
                                                                                                    <div>
                                                                                                        <h6 class="font-medium text-gray-900 dark:text-white">{{ $assignment->title }}</h6>
                                                                                                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                                                                            <span>{{ $assignment->points }} points</span>
                                                                                                            <span>•</span>
                                                                                                            <span>Due {{ $assignment->due_date->format('M j, Y') }}</span>
                                                                                                        </div>
                                                                                                    </div>
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
                                                                                                    <a href="{{ route('student.assignments.show', $assignment) }}" 
                                                                                                       class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                                                                        View
                                                                                                    </a>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if($week->materials->count() === 0 && $week->assignments->count() === 0)
                                                                                <div class="text-center py-4 text-gray-500 dark:text-gray-400 text-sm">
                                                                                    No materials or assignments available for this week.
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">No weeks available for this section.</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">No sections available for this term.</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Course Structure</h3>
                                <p class="text-gray-600 dark:text-gray-400">This course doesn't have any materials organized yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const bars = document.querySelectorAll('[data-course-id]');
            bars.forEach(bar => {
                const id = bar.getAttribute('data-course-id');
                fetch(`/student/courses/${id}/progress`)
                  .then(r => r.json())
                  .then(d => {
                    const fill = bar.querySelector('.course-progress');
                    const text = bar.nextElementSibling;
                    if (fill && text) {
                        fill.style.width = (d.progress_percentage || 0) + '%';
                        text.textContent = `${d.completed_items}/${d.total_items} completed (${d.progress_percentage || 0}%)`;
                    }
                  })
                  .catch(() => {});
            });
        });
        </script>
    @else
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Modules Available</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">You're not enrolled in any courses yet. Browse courses to get started!</p>
            <a href="{{ route('student.courses.browse') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out">
                Browse Courses
            </a>
        </div>
    @endif
</x-student-layout>