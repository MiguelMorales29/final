<x-teacher-layout>
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ route('teacher.courses.index') }}" 
               class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $course->title }}</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $course->section ? 'Section ' . $course->section : 'No section specified' }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('teacher.upload-materials', $course) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                Upload Materials
            </a>
            <a href="{{ route('teacher.courses.edit', $course) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                Edit Course
            </a>
        </div>
    </div>

    <!-- Course Info Card -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb-8">
        <div class="flex items-start gap-6">
            @if($course->image)
                <img src="{{ Storage::url($course->image) }}" 
                     alt="{{ $course->title }}" 
                     class="w-32 h-32 object-cover rounded-lg">
            @else
                <div class="w-32 h-32 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            @endif
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $course->description }}</p>
                <div class="flex gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <span>Capacity: {{ $course->student_capacity }} students</span>
                    <span>•</span>
                    <span>{{ $course->terms->count() }} Terms</span>
                    <span>•</span>
                    <span>{{ $course->weeks->count() }} Weeks</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Structure -->
    <div class="space-y-6" x-data="courseStructure()">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Course Structure & Materials</h3>
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <span>Total Materials: <span class="font-semibold text-gray-900 dark:text-white" x-text="totalMaterials"></span></span>
                <span>•</span>
                <span>Total Weeks: <span class="font-semibold text-gray-900 dark:text-white" x-text="totalWeeks"></span></span>
            </div>
        </div>
        
        @forelse($course->terms as $index => $term)
            @php
                $totalWeeks = $term->subTerms->sum(function($subTerm) {
                    return $subTerm->weeks->count();
                });
                $totalMaterials = $term->subTerms->sum(function($subTerm) {
                    return $subTerm->weeks->sum(function($week) {
                        return $week->materials->count();
                    });
                });
            @endphp
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Period Header (Collapsible) -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
                     @click="togglePeriod({{ $term->id }})">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $term->name }}</h4>
                            </div>
                            @if($term->description)
                                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $term->description }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-4">
                            <!-- Summary Row -->
                            <div class="text-right">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $totalWeeks }} weeks, {{ $totalMaterials }} materials
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $term->total_weeks }} planned
                            </span>
                                    <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $term->weeks->count() }} created
                            </span>
                                </div>
                            </div>
                            <!-- Collapse/Expand Icon -->
                            <svg class="w-6 h-6 text-gray-400 transition-transform duration-200" 
                                 :class="{ 'rotate-180': openPeriods[{{ $term->id }}] }" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Period Content (Collapsible) -->
                <div x-show="openPeriods[{{ $term->id }}]" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="p-6">
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
                                                    $weekMaterialsCount = $week->materials->count();
                                                @endphp
                                                <div class="p-4">
                                                    <!-- Week Header (Collapsible) -->
                                                    <div class="flex items-center justify-between cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-2 -m-2 transition-colors duration-200"
                                                         :class="{ 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800': isCurrentWeek({{ $week->id }}) }"
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
                                                                {{ $weekMaterialsCount }} material{{ $weekMaterialsCount !== 1 ? 's' : '' }}
                                                            </span>
                                                            <span x-show="isCurrentWeek({{ $week->id }})" 
                                                                  class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full text-xs font-medium">
                                                                Current Week
                                                            </span>
                                                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" 
                                                                 :class="{ 'rotate-180': openWeeks[{{ $week->id }}] }" 
                                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Week Content (Collapsible) -->
                                                    <div x-show="openWeeks[{{ $week->id }}]" 
                                                         x-transition:enter="transition ease-out duration-200"
                                                         x-transition:enter-start="opacity-0 transform scale-95"
                                                         x-transition:enter-end="opacity-100 transform scale-100"
                                                         x-transition:leave="transition ease-in duration-150"
                                                         x-transition:leave-start="opacity-100 transform scale-100"
                                                         x-transition:leave-end="opacity-0 transform scale-95"
                                                         class="mt-4">
                                                        <div class="flex items-center justify-between mb-4">
                                                            <h6 class="text-sm font-medium text-gray-700 dark:text-gray-300">Materials</h6>
                                                        <a href="{{ route('teacher.upload-materials', $course) }}?week={{ $week->id }}" 
                                                               class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                </svg>
                                                            Add Material
                                                        </a>
                                                    </div>
                                                    
                                                    @if($week->materials->count() > 0)
                                                            <div class="space-y-3">
                                                            @foreach($week->materials as $material)
                                                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-200">
                                                                    <div class="flex items-start justify-between gap-3 min-w-0">
                                                                        <div class="flex-1 min-w-0">
                                                                                <div class="flex items-center gap-2 mb-2 min-w-0">
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
                                                                                    <span class="font-medium text-gray-900 dark:text-white truncate max-w-[55%]">{{ $material->title }}</span>
                                                                                    <span class="text-xs text-gray-500 dark:text-gray-400 capitalize bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded flex-shrink-0">
                                                                                        {{ $material->type }}
                                                                                    </span>
                                                                                    @if($material->is_required)
                                                                                        <span class="text-xs bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-2 py-1 rounded font-medium flex-shrink-0">
                                                                                            Required
                                                                                        </span>
                                                                                    @endif
                                                                                </div>
                                                                                @if($material->description)
                                                                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2 prose prose-sm max-w-none">
                                                                                        {!! Str::limit(strip_tags($material->description), 100) !!}
                                                                                    </div>
                                                                                @endif
                                                                                <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                                                                    <span>Uploaded {{ $material->created_at->format('M j, Y') }}</span>
                                                                                    @if($material->created_at->diffInDays() < 7)
                                                                                        <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded">
                                                                                            New
                                                                                        </span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="flex items-center gap-2 ml-4">
                                                                            <a href="{{ route('teacher.materials.edit', [$course, $material]) }}" 
                                                                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-2 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20 transition duration-150 ease-in-out"
                                                                                   title="Edit Material">
                                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                                    </svg>
                                                                            </a>
                                                                            <form action="{{ route('teacher.materials.delete', [$course, $material]) }}" 
                                                                                  method="POST" 
                                                                                  onsubmit="return confirm('Are you sure you want to delete this material?');" 
                                                                                  class="inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" 
                                                                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 p-2 rounded hover:bg-red-50 dark:hover:bg-red-900/20 transition duration-150 ease-in-out"
                                                                                            title="Delete Material">
                                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                                        </svg>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                </svg>
                                                                <p class="text-sm">No materials uploaded for this week.</p>
                                                            </div>
                                                    @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="p-4 text-center text-gray-500 dark:text-gray-400 text-sm">
                                            No weeks created for this section.
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm">No sections created for this term.</p>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center">
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
    </div>

    <!-- Alpine.js Script for Collapsible Functionality -->
    <script>
        function courseStructure() {
            return {
                openPeriods: {},
                openWeeks: {},
                totalMaterials: 0,
                totalWeeks: 0,

                init() {
                    // Load saved states from sessionStorage
                    this.loadSavedStates();
                    
                    // Calculate totals
                    this.calculateTotals();
                    
                    // Set default state: first period expanded, others collapsed
                    this.setDefaultStates();
                },

                togglePeriod(periodId) {
                    this.openPeriods[periodId] = !this.openPeriods[periodId];
                    this.saveStates();
                },

                toggleWeek(weekId) {
                    this.openWeeks[weekId] = !this.openWeeks[weekId];
                    this.saveStates();
                },

                setDefaultStates() {
                    // Get all period IDs
                    const periodElements = document.querySelectorAll('[x-data] [x-show*="openPeriods"]');
                    const periodIds = [];
                    
                    // Extract period IDs from the DOM
                    const periodHeaders = document.querySelectorAll('[x-data] [x-click*="togglePeriod"]');
                    periodHeaders.forEach(header => {
                        const onclick = header.getAttribute('@click');
                        const match = onclick.match(/togglePeriod\((\d+)\)/);
                        if (match) {
                            periodIds.push(parseInt(match[1]));
                        }
                    });

                    // Set first period as expanded by default if no saved state
                    if (periodIds.length > 0 && !this.hasSavedStates()) {
                        this.openPeriods[periodIds[0]] = true;
                    }
                },

                calculateTotals() {
                    // Calculate total materials and weeks
                    let materials = 0;
                    let weeks = 0;

                    // Count materials and weeks from the DOM
                    const materialElements = document.querySelectorAll('[x-data] .space-y-3 > div');
                    materials = materialElements.length;

                    const weekElements = document.querySelectorAll('[x-data] [x-click*="toggleWeek"]');
                    weeks = weekElements.length;

                    this.totalMaterials = materials;
                    this.totalWeeks = weeks;
                },

                saveStates() {
                    // Save current states to sessionStorage
                    sessionStorage.setItem('courseStructure_periods', JSON.stringify(this.openPeriods));
                    sessionStorage.setItem('courseStructure_weeks', JSON.stringify(this.openWeeks));
                },

                loadSavedStates() {
                    // Load saved states from sessionStorage
                    const savedPeriods = sessionStorage.getItem('courseStructure_periods');
                    const savedWeeks = sessionStorage.getItem('courseStructure_weeks');
                    
                    if (savedPeriods) {
                        this.openPeriods = JSON.parse(savedPeriods);
                    }
                    if (savedWeeks) {
                        this.openWeeks = JSON.parse(savedWeeks);
                    }
                },

                hasSavedStates() {
                    return sessionStorage.getItem('courseStructure_periods') !== null;
                },

                isCurrentWeek(weekElement) {
                    // Optional: Add logic to highlight current week
                    // This could be based on current date vs week dates
                    return false;
                }
            }
        }
    </script>
</x-teacher-layout>

