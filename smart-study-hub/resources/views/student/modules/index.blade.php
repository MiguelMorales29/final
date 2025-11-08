<x-student-layout>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Modules</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Access your course materials and resources</p>
        </div>
    </div>

    @if($courses->count() > 0)
        <div class="space-y-8">
            @foreach($courses as $course)
                @php
                    $courseTotalWeeks = $course->terms->sum(function($term) {
                        return $term->subTerms->sum(function($subTerm) {
                            return $subTerm->weeks->count();
                        });
                    });
                    $courseTotalMaterials = $course->terms->sum(function($term) {
                                        return $term->subTerms->sum(function($subTerm) { 
                                            return $subTerm->weeks->sum(function($week) { 
                                                return $week->materials->count(); 
                                            }); 
                                        }); 
                    });
                @endphp
                
                <!-- ============================================ -->
                <!-- START COURSE CARD: {{ $course->title }} -->
                <!-- ============================================ -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Course Header Section -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <!-- Course Image - Full Width Rectangle -->
                        @if($course->image)
                            <div class="w-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden" style="max-height: 300px; min-height: 200px;">
                                <img src="{{ Storage::url($course->image) }}" 
                                     alt="{{ $course->title }}" 
                                     class="w-full h-full object-contain">
                            </div>
                        @else
                            <div class="w-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center" style="height: 200px;">
                                <svg class="w-24 h-24 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Course Info -->
                        <div class="px-6 py-5">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 break-all">{{ $course->title }}</h2>
                            <p class="text-base font-medium text-blue-600 dark:text-blue-400 mb-2 truncate" title="{{ $course->teacher->name }}">{{ $course->teacher->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Capacity: <span class="font-semibold">{{ $course->student_capacity }}</span> students
                            </p>
                            
                            <!-- Stats -->
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                <span class="inline-flex items-center gap-2 bg-white dark:bg-gray-700 px-3 py-1.5 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 text-sm">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $courseTotalMaterials }}</span>
                                    <span class="text-gray-600 dark:text-gray-400">Materials</span>
                                </span>
                                <span class="inline-flex items-center gap-2 bg-white dark:bg-gray-700 px-3 py-1.5 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 text-sm">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $courseTotalWeeks }}</span>
                                    <span class="text-gray-600 dark:text-gray-400">Weeks</span>
                                </span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 shadow-inner" data-course-id="{{ $course->id }}">
                                    <div class="course-progress bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-300 shadow-sm" style="width: 0%"></div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 course-progress-text">Loading progress...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Course Structure Section -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Course Structure & Materials</h3>
                    </div>

                    <!-- Terms Section for Course: {{ $course->id }} -->
                    <div class="p-6">
                        @if($course->terms->count() > 0)
                            <div class="space-y-6" x-data="{ openTerms: {}, openSections: {}, openWeeks: {} }">
                                @foreach($course->terms as $term)
                                    @php
                                        $termWeeks = $term->subTerms->sum(function($subTerm) {
                                            return $subTerm->weeks->count();
                                        });
                                        $termMaterials = $term->subTerms->sum(function($subTerm) {
                                            return $subTerm->weeks->sum(function($week) {
                                                return $week->materials->count();
                                            });
                                        });
                                    @endphp
                                    <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-600 shadow-lg">
                                        <div class="p-6 border-b border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200" @click="openTerms[{{ $term->id }}] = !openTerms[{{ $term->id }}]">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-4">
                                                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    </div>
                                                    <div>
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white break-all line-clamp-1">{{ $term->name }}</h3>
                                                        @if($term->description)
                                                            <p class="text-sm text-gray-600 dark:text-gray-400 break-all line-clamp-2">{{ $term->description }}</p>
                                                        @endif
                                                        <p class="text-xs text-gray-500 dark:text-gray-300 mt-1">{{ $termWeeks }} weeks • {{ $termMaterials }} materials</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    @if(isset($term->total_weeks))
                                                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-xs font-medium">{{ $term->total_weeks }} planned</span>
                                                    @endif
                                                    <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full text-xs font-medium">{{ $termWeeks }} created</span>
                                                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openTerms[{{ $term->id }}] }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div x-show="openTerms[{{ $term->id }}]" style="display: none;" class="p-6 space-y-4">
                                        
                                        @if($term->subTerms->count() > 0)
                                            <div class="space-y-4">
                                                @foreach($term->subTerms as $subTerm)
                                                    @php
                                                        $subTermWeeks = $subTerm->weeks->count();
                                                        $subTermMaterials = $subTerm->weeks->sum(function($week) {
                                                            return $week->materials->count();
                                                        });
                                                    @endphp
                                                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                                        <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200" @click="openSections[{{ $subTerm->id }}] = !openSections[{{ $subTerm->id }}]">
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex items-center gap-3">
                                                                    <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                                                                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                                                    </div>
                                                                    <div>
                                                                        <h4 class="text-md font-medium text-gray-900 dark:text-white break-all line-clamp-2">{{ $subTerm->title }}</h4>
                                                                        <p class="text-xs text-gray-500 dark:text-gray-300 mt-1 line-clamp-1">{{ $subTermWeeks }} weeks • {{ $subTermMaterials }} materials</p>
                                                                        @if($subTerm->description)
                                                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 break-all line-clamp-2">{{ $subTerm->description }}</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openSections[{{ $subTerm->id }}] }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                            </div>
                                                        </div>
                                                        <div x-show="openSections[{{ $subTerm->id }}]" style="display: none;" class="p-4 space-y-3">
                                                        
                                                        @if($subTerm->weeks->count() > 0)
                                                            <div class="divide-y divide-gray-200 dark:divide-gray-600">
                                                                @foreach($subTerm->weeks as $week)
                                                                    @php
                                                                        $weekMaterialsCount = $week->materials->count();
                                                                    @endphp
                                                                    <div class="p-4">
                                                                        <div class="flex items-center justify-between cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-2 -m-2 transition-colors duration-200" @click="openWeeks['{{ $week->id }}'] = !openWeeks['{{ $week->id }}']">
                                                                            <div class="flex items-center gap-3">
                                                                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                                <div>
                                                                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white break-all line-clamp-1">{{ $week->title }}</h5>
                                                                                    @if($week->notes)
                                                                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 break-all line-clamp-2">{{ $week->notes }}</p>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="flex items-center gap-3">
                                                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                                                    {{ $weekMaterialsCount }} material{{ $weekMaterialsCount !== 1 ? 's' : '' }}
                                                                                </span>
                                                                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openWeeks['{{ $week->id }}'] }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                                            </div>
                                                                        </div>

                                                                        <div x-show="openWeeks['{{ $week->id }}']" style="display: none;"
                                                                             x-transition:enter="transition ease-out duration-200"
                                                                             x-transition:enter-start="opacity-0 transform scale-95"
                                                                             x-transition:enter-end="opacity-100 transform scale-100"
                                                                             x-transition:leave="transition ease-in duration-150"
                                                                             x-transition:leave-start="opacity-100 transform scale-100"
                                                                             x-transition:leave-end="opacity-0 transform scale-95"
                                                                             class="mt-4 space-y-4">
                                                                            <!-- Materials -->
                                                                            @if($week->materials->count() > 0)
                                                                                <div class="mb-4">
                                                                                    <h6 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Materials</h6>
                                                                                    <div class="space-y-2">
                                                                                        @foreach($week->materials as $material)
                                                                                            @php
                                                                                                $isCompleted = \App\Models\MaterialCompletion::where('student_id', auth()->id())
                                                                                                    ->where('material_id', $material->id)
                                                                                                    ->exists();
                                                                                            @endphp
                                                                                            <div class="flex items-center justify-between gap-4 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:shadow-sm transition-shadow material-item" data-material-id="{{ $material->id }}">
                                                                                                <div class="flex items-center gap-3 flex-1 min-w-0">
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
                                                                                                    <div class="min-w-0 space-y-1">
                                                                                                        <p class="font-medium text-gray-900 dark:text-white break-all line-clamp-2" title="{{ $material->title }}">{{ $material->title }}</p>
                                                                                                        @php
                                                                                                            $previewSource = $material->type === 'text' && $material->content
                                                                                                                ? strip_tags($material->content)
                                                                                                                : strip_tags($material->description);
                                                                                                        @endphp
                                                                                                        @if(!empty($previewSource))
                                                                                                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 break-all">{{ Str::limit($previewSource, 160) }}</p>
                                                                                                        @endif
                                                                                                     </div>
                                                                                                 </div>
                                                                                                <div class="flex items-center gap-2 flex-shrink-0">
                                                                                                     <span class="material-status-badge-{{ $material->id }}">
                                                                                                         @if($isCompleted)
                                                                                                             <span class="text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded font-medium">
                                                                                                                 Marked as Done
                                                                                                             </span>
                                                                                                        @elseif($material->is_required)
                                                                                                        <span class="text-xs bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-2 py-1 rounded font-medium">
                                                                                                            Required
                                                                                                        </span>
                                                                                                    @endif
                                                                                                    </span>
                                                                                            <a href="{{ route('student.materials.show', $material) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors duration-150 whitespace-nowrap">View</a>
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
                                                                                            <div class="flex items-center justify-between gap-4 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:shadow-sm transition-shadow">
                                                                                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                                                                                     <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                                     </svg>
                                                                                                    <div class="min-w-0 space-y-1">
                                                                                                        <p class="font-medium text-gray-900 dark:text-white break-all line-clamp-2" title="{{ $assignment->title }}">{{ $assignment->title }}</p>
                                                                                                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 flex-wrap">
                                                                                                            <span class="whitespace-nowrap">{{ $assignment->points }} points</span>
                                                                                                            <span class="hidden sm:inline">•</span>
                                                                                                            <span class="whitespace-nowrap">Due {{ $assignment->due_date->format('M j, Y') }}</span>
                                                                                                        </div>
                                                                                                        @if($assignment->description)
                                                                                                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 break-all mt-2">{{ Str::limit(strip_tags($assignment->description), 160) }}</p>
                                                                                                        @endif
                                                                                                     </div>
                                                                                                </div>
                                                                                                <div class="flex items-center gap-2 flex-shrink-0">
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
                                                                                                       class="text-xs text-blue-600 dark:text-blue-400 hover:underline whitespace-nowrap">
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
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">No sections available for this term.</p>
                                        @endif
                                    </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-8 text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Course Structure</h3>
                                <p class="text-gray-600 dark:text-gray-400">This course doesn't have any materials organized yet.</p>
                            </div>
                        @endif
                    </div>
                    <!-- End Terms Section for Course: {{ $course->id }} -->
                </div>
                <!-- END COURSE CARD: {{ $course->title }} -->
            @endforeach
            <!-- End All Courses Loop -->
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

            // Listen for material completion updates
            window.addEventListener('materialCompletionUpdated', function(e) {
                const materialId = e.detail.materialId;
                const isCompleted = e.detail.isCompleted;
                const badge = document.querySelector(`.material-status-badge-${materialId}`);
                if (badge) {
                    if (isCompleted) {
                        badge.innerHTML = '<span class="text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded font-medium">Marked as Done</span>';
                    } else {
                        const materialItem = document.querySelector(`.material-item[data-material-id="${materialId}"]`);
                        const isRequired = materialItem?.dataset?.required === 'true';
                        if (isRequired) {
                            badge.innerHTML = '<span class="text-xs bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-2 py-1 rounded font-medium">Required</span>';
                        } else {
                            badge.innerHTML = '';
                        }
                    }
                }
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