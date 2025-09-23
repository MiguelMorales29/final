<x-student-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Course Header -->
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
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $course->teacher->name }}</p>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $course->description }}</p>
                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                        <span>{{ $course->terms->count() }} Terms</span>
                        <span>{{ $course->weeks->count() }} Weeks</span>
                        <span>{{ $course->materials->count() }} Materials</span>
                        @if($isEnrolled)
                            <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full text-sm font-medium">
                                Enrolled
                            </span>
                        @else
                            <form method="POST" action="{{ route('enroll', $course->id) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                                    Enroll Now
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($isEnrolled)
            <!-- Course Structure -->
            <div class="space-y-6" x-data="{ openTerms: {} }">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Course Structure</h2>
                
                @forelse($course->terms as $term)
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $term->name }}</h3>
                                    @if($term->description)
                                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $term->description }}</p>
                                    @endif
                                </div>
                                <button @click="openTerms['{{ $term->id }}'] = !openTerms['{{ $term->id }}']" 
                                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <svg class="w-6 h-6 transition-transform duration-200" 
                                         :class="{ 'rotate-180': openTerms['{{ $term->id }}'] }" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div x-show="openTerms['{{ $term->id }}']" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="p-6">
                            @if($term->subTerms->count() > 0)
                                <div class="space-y-6">
                                    @foreach($term->subTerms as $subTerm)
                                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4" x-data="{ openSections: {} }">
                                            <div class="flex items-center justify-between mb-4">
                                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $subTerm->title }}</h4>
                                                <button @click="openSections['{{ $subTerm->id }}'] = !openSections['{{ $subTerm->id }}']" 
                                                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                                    <svg class="w-5 h-5 transition-transform duration-200" 
                                                         :class="{ 'rotate-180': openSections['{{ $subTerm->id }}'] }" 
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                            </div>

                                            <div x-show="openSections['{{ $subTerm->id }}']" 
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 transform scale-95"
                                                 x-transition:enter-end="opacity-100 transform scale-100"
                                                 x-transition:leave="transition ease-in duration-150"
                                                 x-transition:leave-start="opacity-100 transform scale-100"
                                                 x-transition:leave-end="opacity-0 transform scale-95"
                                                 class="space-y-4">
                                                @if($subTerm->weeks->count() > 0)
                                                    <div class="course-weeks">
                                                        @foreach($subTerm->weeks as $week)
                                                            <div class="week bg-gray-50 dark:bg-gray-700 rounded-lg mb-4" x-data="{ openWeek: false }">
                                                                <button @click="openWeek = !openWeek" 
                                                                        class="week-toggle w-full p-4 text-left flex items-center justify-between hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                                                                    <div class="flex items-center gap-3">
                                                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" 
                                                                             :class="{ 'rotate-90': openWeek }" 
                                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                                        </svg>
                                                                        <span class="font-medium text-gray-900 dark:text-white">{{ $week->title }}</span>
                                                                        @if($week->description)
                                                                            <span class="text-sm text-gray-500 dark:text-gray-400">- {{ $week->description }}</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded">
                                                                            {{ $week->materials->count() }} materials
                                                                        </span>
                                                                    </div>
                                                                </button>
                                                                
                                                                <div x-show="openWeek" 
                                                                     x-transition:enter="transition ease-out duration-200"
                                                                     x-transition:enter-start="opacity-0 transform scale-95"
                                                                     x-transition:enter-end="opacity-100 transform scale-100"
                                                                     x-transition:leave="transition ease-in duration-150"
                                                                     x-transition:leave-start="opacity-100 transform scale-100"
                                                                     x-transition:leave-end="opacity-0 transform scale-95"
                                                                     class="week-content p-4 border-t border-gray-200 dark:border-gray-600">
                                                                    @if($week->materials->count() > 0)
                                                                        <div class="space-y-3">
                                                                            @foreach($week->materials as $material)
                                                                                <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                                                                    <div class="flex items-center gap-3">
                                                                                        @if($material->type === 'video')
                                                                                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                                                            </svg>
                                                                                        @elseif($material->type === 'file' || $material->type === 'pdf' || $material->type === 'ppt' || $material->type === 'document')
                                                                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                            </svg>
                                                                                        @else
                                                                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                                                            </svg>
                                                                                        @endif
                                                                                        <div>
                                                                                            <span class="font-medium text-gray-900 dark:text-white">{{ $material->title }}</span>
                                                                                            @if($material->description)
                                                                                                <div class="text-sm text-gray-600 dark:text-gray-400 prose prose-sm max-w-none">
                                                                                                    {!! $material->description !!}
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex gap-2">
                                                                                        <a href="{{ route('student.materials.show', $material) }}" 
                                                                                           class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 px-3 py-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition duration-150 ease-in-out">
                                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                                            </svg>
                                                                                            <span class="text-sm font-medium">View Material</span>
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <p class="text-gray-500 dark:text-gray-400 text-sm">No materials available for this week.</p>
                                                                    @endif

                                                                    <!-- Sample Activities, Assignments, Quizzes -->
                                                                    <div class="mt-4 space-y-3">
                                                                        <!-- Activity -->
                                                                        <div class="activity bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-3">
                                                                            <div class="flex items-center justify-between">
                                                                                <div class="flex items-center gap-2">
                                                                                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                    </svg>
                                                                                    <span class="font-medium text-yellow-800 dark:text-yellow-200">Submit Reflection</span>
                                                                                </div>
                                                                                <button class="bg-yellow-600 hover:bg-yellow-700 text-white text-sm px-3 py-1 rounded-lg transition duration-150 ease-in-out">
                                                                                    Submit Work
                                                                                </button>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Assignment -->
                                                                        <div class="assignment bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-3">
                                                                            <div class="flex items-center gap-2">
                                                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                </svg>
                                                                                <span class="font-medium text-blue-800 dark:text-blue-200">Assignment 1 - Due: Dec 15, 2024</span>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Quiz -->
                                                                        <div class="quiz bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-3">
                                                                            <div class="flex items-center gap-2">
                                                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                </svg>
                                                                                <span class="font-medium text-green-800 dark:text-green-200">Quiz 1 - 10 Questions</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-gray-500 dark:text-gray-400">No weeks created for this section.</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400">No sections created for this term.</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Course Structure</h3>
                        <p class="text-gray-600 dark:text-gray-400">This course doesn't have any terms or weeks defined yet.</p>
                    </div>
                @endforelse
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Enrollment Required</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">You need to enroll in this course to view its content.</p>
                <form method="POST" action="{{ route('enroll', $course->id) }}">
                    @csrf
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                        Enroll Now
                    </button>
                </form>
            </div>
        @endif
    </div>

</x-student-layout>
