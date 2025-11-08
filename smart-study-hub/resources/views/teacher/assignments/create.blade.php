<x-teacher-layout>
    @php $courseEnrollmentCount = $courseEnrollmentCount ?? 0; @endphp
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('teacher.assignments.select-course') }}" 
           class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Upload Assignment</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Create a new assignment for {{ $course->title }}</p>
        </div>
                </div>
                <a href="{{ route('teacher.course.assignments', $course) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                    View Assignments
                </a>
    </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                        @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Upload Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 sticky top-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Upload New Assignment</h3>
                        </div>
                        
                        <form method="POST" action="{{ route('teacher.assignments.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

                            <!-- Hidden course field -->
                            <input type="hidden" name="course_id" value="{{ $course->id }}">

                            <!-- Term Selection -->
            <div>
                                <label for="term_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Term *
                </label>
                                <select name="term_id" 
                                        id="term_id"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                                    <option value="">Choose a term...</option>
                                    @foreach($course->terms as $term)
                                        <option value="{{ $term->id }}">{{ $term->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Week Selection -->
            <div>
                <label for="course_week_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Week *
                </label>
                <select name="course_week_id" 
                        id="course_week_id"
                                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    <option value="">Choose a week...</option>
                </select>
            </div>

            <!-- Assignment Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Assignment Title *
                                    <span class="text-xs text-gray-500 dark:text-gray-400">(max 500 characters)</span>
                </label>
                                <div class="relative">
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Enter assignment title"
                                           maxlength="500"
                       required>
                                    <div class="absolute bottom-2 right-3">
                                        <span id="title-counter" class="text-xs text-gray-400 dark:text-gray-500">0/500</span>
                                    </div>
                                </div>
            </div>

            <!-- Assignment Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Description
                                    <span class="text-xs text-gray-500 dark:text-gray-400">(max 5,000 characters)</span>
                </label>
                                <div class="relative">
                                    <!-- Quill Editor Container -->
                                    <div id="description-editor" 
                                         class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition duration-150 ease-in-out"
                                         style="height: 200px;">
                                    </div>
                                    <!-- Hidden input to store HTML content -->
                                    <input type="hidden" id="description" name="description">
                                    <div class="absolute bottom-2 right-3">
                                        <span id="description-counter" class="text-xs text-gray-400 dark:text-gray-500">0/5,000</span>
                                    </div>
                                </div>
            </div>

            <!-- Instructions -->
            <div>
                <label for="instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Instructions
                                    <span class="text-xs text-gray-500 dark:text-gray-400">(max 5,000 characters)</span>
                                </label>
                                <div class="relative">
                                    <!-- Quill Editor Container for Instructions -->
                                    <div id="instructions-editor" 
                                         class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition duration-150 ease-in-out"
                                         style="height: 200px;">
                                    </div>
                                    <!-- Hidden input to store HTML content -->
                                    <input type="hidden" id="instructions" name="instructions">
                                    <div class="absolute bottom-2 right-3">
                                        <span id="instructions-counter" class="text-xs text-gray-400 dark:text-gray-500">0/5,000</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Points -->
                            <div>
                                <label for="points" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Points *
                                </label>
                                <input type="number" 
                                       id="points" 
                                       name="points" 
                                       value="{{ old('points', 100) }}"
                                       min="1" 
                                       max="1000"
                                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Due Date *
                                </label>
                                <input type="datetime-local" 
                                       id="due_date" 
                                       name="due_date" 
                                       value="{{ old('due_date') }}"
                                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Max Attempts -->
                            <div>
                                <label for="max_attempts" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Max Attempts *
                </label>
                                <input type="number" 
                                       id="max_attempts" 
                                       name="max_attempts" 
                                       value="{{ old('max_attempts', 1) }}"
                                       min="1" 
                                       max="10"
                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
            </div>

            <!-- Submission Type -->
            <div>
                <label for="submission_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Submission Type *
                </label>
                <select name="submission_type" 
                        id="submission_type"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="both" {{ old('submission_type') == 'both' ? 'selected' : '' }}>Text and File Upload</option>
                    <option value="text" {{ old('submission_type') == 'text' ? 'selected' : '' }}>Text Only</option>
                    <option value="file" {{ old('submission_type') == 'file' ? 'selected' : '' }}>File Upload Only</option>
                </select>
            </div>

            <!-- File Upload Settings (shown when file upload is allowed) -->
            <div id="file-settings" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Allowed File Types
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach(['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png', 'gif'] as $type)
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="allowed_file_types[]" 
                                       value="{{ $type }}"
                                       {{ in_array($type, old('allowed_file_types', ['pdf', 'doc', 'docx', 'txt'])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ strtoupper($type) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="max_file_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Max File Size (MB)
                        </label>
                        <input type="number" 
                               id="max_file_size" 
                               name="max_file_size" 
                               value="{{ old('max_file_size', 10) }}"
                               min="1" 
                               max="100"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="max_files" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Max Files
                        </label>
                        <input type="number" 
                               id="max_files" 
                               name="max_files" 
                               value="{{ old('max_files', 1) }}"
                               min="1" 
                               max="10"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Publish Status -->
            <div class="flex items-center">
                <input type="checkbox" 
                       id="is_published" 
                       name="is_published" 
                       value="1"
                       {{ old('is_published') ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                <label for="is_published" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    Publish assignment immediately
                </label>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-4">
                <button type="submit" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-150 ease-in-out">
                    Create Assignment
                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Course Info & Preview -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <!-- Course Information -->
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Course Information
                            </h3>
                            
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h4>
                                <p class="text-gray-600 dark:text-gray-400 mb-3">{{ $course->section }}</p>
                                @if($course->description)
                                    <p class="text-gray-700 dark:text-gray-300 text-sm">{{ Str::limit($course->description, 150) }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Assignment Stats -->
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Assignment Statistics
                            </h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $assignmentStats['total'] }}</div>
                                    <div class="text-sm text-blue-600 dark:text-blue-400">Total Assignments</div>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $assignmentStats['published'] }}</div>
                                    <div class="text-sm text-green-600 dark:text-green-400">Published</div>
                                </div>
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $assignmentStats['drafts'] }}</div>
                                    <div class="text-sm text-yellow-600 dark:text-yellow-400">Drafts</div>
                                </div>
                                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $assignmentStats['submissions'] }}</div>
                                    <div class="text-sm text-purple-600 dark:text-purple-400">Total Submissions</div>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Preview -->
                        <div class="space-y-6" x-data="collapsibleManager()">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Course Structure & Assignments</h3>
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
                                                                        <div class="flex items-center justify-between">
                                                                            <h6 class="text-sm font-medium text-gray-700 dark:text-gray-300">Assignments</h6>
                                                                            <button type="button"
                                                                                class="add-assignment-btn inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium"
                                                                                data-term-id="{{ $term->id }}"
                                                                                data-week-id="{{ $week->id }}">
                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                                </svg>
                                                                                Add Assignment
                                                                            </button>
                                                                        </div>

                                                                        @if($assignmentCount > 0)
                                                                            <div class="space-y-3">
                                                                                @foreach($weekAssignments as $assignment)
                                                                                    <div class="item-card bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:shadow-md transition-all duration-200">
                                                                                        <div class="flex items-start justify-between gap-4 min-w-0">
                                                                                            <div class="flex-1 min-w-0">
                                                                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white truncate">{{ $assignment->title }}</h4>
                                                                                                @if($assignment->description)
                                                                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                                                                                        {{ Str::limit(strip_tags($assignment->description), 120) }}
                                                                                                    </p>
                                                                                                @endif
                                                                                            </div>
                                                                                            <div class="flex flex-col items-end gap-2">
                                                                                                <span class="{{ $assignment->is_published ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200' }} text-xs font-semibold px-2 py-1 rounded-full">
                                                                                                    {{ $assignment->is_published ? 'Published' : 'Draft' }}
                                                                                                </span>
                                                                                                <div class="flex items-center gap-2">
                                                                                                    <a href="{{ route('teacher.assignments.show', $assignment) }}"
                                                                                                       class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                                                                                       title="View Assignment">
                                                                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                                                        </svg>
                                                                                                    </a>
                                                                                                    <a href="{{ route('teacher.assignments.edit', $assignment) }}"
                                                                                                       class="p-2 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-200 transition-colors"
                                                                                                       title="Edit Assignment">
                                                                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                                                        </svg>
                                                                                                    </a>
                                                                                                    <form action="{{ route('teacher.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                                                                                        @csrf
                                                                                                        @method('DELETE')
                                                                                                        <button type="submit"
                                                                                                                class="p-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200 transition-colors"
                                                                                                                title="Delete Assignment">
                                                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                                            </svg>
                                                                                                        </button>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
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
                                                                                                @php
                                                                                                    $submissionsTotal = $assignment->submissions_count ?? 0;
                                                                                                    $totalPossible = max($courseEnrollmentCount, $submissionsTotal);
                                                                                                @endphp
                                                                                                <span>{{ $submissionsTotal }}/{{ $totalPossible }} submissions</span>
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
                                        <div class="item-card bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:shadow-md transition duration-200">
                                            <div class="flex items-start justify-between gap-4 min-w-0">
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white truncate">{{ $assignment->title }}</h4>
                                                    @if($assignment->description)
                                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                                            {{ Str::limit(strip_tags($assignment->description), 120) }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="flex flex-col items-end gap-2">
                                                    <span class="{{ $assignment->is_published ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200' }} text-xs font-semibold px-2 py-1 rounded-full">
                                                        {{ $assignment->is_published ? 'Published' : 'Draft' }}
                                                    </span>
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('teacher.assignments.show', $assignment) }}"
                                                           class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                                           title="View Assignment">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </a>
                                                        <a href="{{ route('teacher.assignments.edit', $assignment) }}"
                                                           class="p-2 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-200 transition-colors"
                                                           title="Edit Assignment">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                        <form action="{{ route('teacher.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="p-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200 transition-colors"
                                                                    title="Delete Assignment">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            @php
                                                $unassignedSubmissions = $assignment->submissions_count ?? 0;
                                                $unassignedTotal = max($courseEnrollmentCount, $unassignedSubmissions);
                                            @endphp
                                            <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-gray-600 dark:text-gray-400">
                                                <div><span class="font-semibold text-gray-900 dark:text-white">{{ $assignment->points }}</span> pts</div>
                                                <div>{{ $unassignedSubmissions }}/{{ $unassignedTotal }} submissions</div>
                                                <div class="{{ $assignment->due_date && $assignment->due_date->isPast() ? 'text-red-600 dark:text-red-400 font-semibold' : '' }}">
                                                    {{ $assignment->due_date ? $assignment->due_date->format('M j, Y g:i A') : 'No due date' }}
                                                </div>
                                                <div>Max Attempts: {{ $assignment->max_attempts }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($assignmentStats['total'] === 0)
                                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center border border-dashed border-gray-300 dark:border-gray-600">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Assignments Yet</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">Create your first assignment to populate this course structure.</p>
                                    <button type="button"
                                            class="add-assignment-btn bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out"
                                            @if(isset($course->terms[0]) && isset($course->terms[0]->subTerms[0]) && isset($course->terms[0]->subTerms[0]->weeks[0]))
                                                data-term-id="{{ $course->terms[0]->id }}"
                                                data-week-id="{{ $course->terms[0]->subTerms[0]->weeks[0]->id }}"
                                            @else
                                                disabled
                                            @endif>
                                        Add Assignment
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Assignment Modal -->
    <div id="editAssignmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full md:w-[700px] shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Assignment</h3>
                    <button type="button" 
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            onclick="closeEditModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="editAssignmentForm" class="mt-6 space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Course (disabled) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Course
                        </label>
                        <input type="text" 
                               id="edit_course_title" 
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 bg-gray-100 dark:bg-gray-600 cursor-not-allowed"
                               disabled>
                    </div>

                    <!-- Term -->
                    <div>
                        <label for="edit_term_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Term *
                        </label>
                        <select name="term_id" 
                                id="edit_term_id"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Choose a term...</option>
                            @foreach($course->terms as $term)
                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Week -->
                    <div>
                        <label for="edit_course_week_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Week *
                        </label>
                        <select name="course_week_id" 
                                id="edit_course_week_id"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Choose a week...</option>
                        </select>
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="edit_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Assignment Title *
                            <span class="text-xs text-gray-500 dark:text-gray-400">(max 500 characters)</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="edit_title" 
                                   name="title"
                                   class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   maxlength="500"
                                   required>
                            <div class="absolute bottom-2 right-3">
                                <span id="edit-title-counter" class="text-xs text-gray-400 dark:text-gray-500">0/500</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description
                            <span class="text-xs text-gray-500 dark:text-gray-400">(max 5,000 characters)</span>
                        </label>
                        <div class="relative">
                            <!-- Quill Editor Container for Edit Modal -->
                            <div id="edit-description-editor" 
                                 class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition duration-150 ease-in-out"
                                 style="height: 150px;">
                            </div>
                            <!-- Hidden input to store HTML content -->
                            <input type="hidden" id="edit_description" name="description">
                            <div class="absolute bottom-2 right-3">
                                <span id="edit-description-counter" class="text-xs text-gray-400 dark:text-gray-500">0/5,000</span>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div>
                        <label for="edit_instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Instructions
                            <span class="text-xs text-gray-500 dark:text-gray-400">(max 5,000 characters)</span>
                        </label>
                        <div class="relative">
                            <!-- Quill Editor Container for Instructions -->
                            <div id="edit-instructions-editor" 
                                 class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition duration-150 ease-in-out"
                                 style="height: 150px;">
                            </div>
                            <!-- Hidden input to store HTML content -->
                            <input type="hidden" id="edit_instructions" name="instructions">
                            <div class="absolute bottom-2 right-3">
                                <span id="edit-instructions-counter" class="text-xs text-gray-400 dark:text-gray-500">0/5,000</span>
                            </div>
                        </div>
                    </div>

                    <!-- Points -->
                    <div>
                        <label for="edit_points" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Points *
                        </label>
                        <input type="number" 
                               id="edit_points" 
                               name="points"
                               min="1" 
                               max="1000"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="edit_due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Due Date *
                        </label>
                        <input type="datetime-local" 
                               id="edit_due_date" 
                               name="due_date"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <!-- Max Attempts -->
                    <div>
                        <label for="edit_max_attempts" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Max Attempts *
                        </label>
                        <input type="number" 
                               id="edit_max_attempts" 
                               name="max_attempts"
                               min="1" 
                               max="10"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <!-- Publish Status -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="edit_is_published" 
                               name="is_published" 
                               value="1"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="edit_is_published" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Publish assignment
                        </label>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out"
                                onclick="closeEditModal()">
                    Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Quill.js -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <!-- Include collapsible script -->
    <x-collapsible-script />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const termSelect = document.getElementById('term_id');
            const weekSelect = document.getElementById('course_week_id');
            const submissionTypeSelect = document.getElementById('submission_type');
            const fileSettings = document.getElementById('file-settings');
            const titleInput = document.getElementById('title');
            const titleCounter = document.getElementById('title-counter');

            const storageTermKey = 'assignment_form_selected_term';
            const storageWeekKey = 'assignment_form_selected_week';

            const courseStructure = @json($courseStructure);

            const descriptionQuill = new Quill('#description-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['link'],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                },
                placeholder: 'Enter assignment description...'
            });

            const instructionsQuill = new Quill('#instructions-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['link'],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                },
                placeholder: 'Enter detailed instructions...'
            });

            descriptionQuill.on('text-change', function() {
                const html = descriptionQuill.root.innerHTML;
                document.getElementById('description').value = html;
                updateCharacterCounter('description-counter', html, 5000);
            });

            instructionsQuill.on('text-change', function() {
                const html = instructionsQuill.root.innerHTML;
                document.getElementById('instructions').value = html;
                updateCharacterCounter('instructions-counter', html, 5000);
            });

            if (titleInput) {
            titleInput.addEventListener('input', function() {
                updateCharacterCounter('title-counter', this.value, 500);
            });
            }

            function updateCharacterCounter(counterId, content, maxLength) {
                const counter = document.getElementById(counterId);
                if (!counter) return;
                const textLength = content.replace(/<[^>]*>/g, '').length;
                counter.textContent = `${textLength}/${maxLength}`;
                
                if (textLength > maxLength) {
                    counter.classList.add('text-red-500');
                } else {
                    counter.classList.remove('text-red-500');
                }
            }

            termSelect.addEventListener('change', function() {
                updateWeeks();
                sessionStorage.setItem(storageTermKey, this.value || '');
                sessionStorage.removeItem(storageWeekKey);
            });

            weekSelect.addEventListener('change', function() {
                if (this.value) {
                    sessionStorage.setItem(storageWeekKey, this.value);
                } else {
                    sessionStorage.removeItem(storageWeekKey);
                }
            });

            submissionTypeSelect.addEventListener('change', function() {
                if (this.value === 'file' || this.value === 'both') {
                    fileSettings.style.display = 'block';
                } else {
                    fileSettings.style.display = 'none';
                }
            });

            function updateWeeks(preselectWeekId = null) {
                weekSelect.innerHTML = '<option value="">Choose a week...</option>';
                const termId = termSelect.value;
                
                if (termId) {
                    const selectedTerm = courseStructure.find(term => String(term.id) === String(termId));
                    if (selectedTerm && selectedTerm.weeks) {
                        selectedTerm.weeks.forEach(week => {
                            const option = document.createElement('option');
                            option.value = week.id;
                            option.textContent = `${week.sub_term || 'Week'} - ${week.title}`;
                            if (preselectWeekId && String(week.id) === String(preselectWeekId)) {
                                option.selected = true;
                            }
                            weekSelect.appendChild(option);
                        });

                        if (preselectWeekId && !selectedTerm.weeks.some(week => String(week.id) === String(preselectWeekId))) {
                            weekSelect.selectedIndex = 0;
                        }
                    }
                }
            }

            function findTermByWeek(weekId) {
                for (const term of courseStructure) {
                    const week = term.weeks.find(week => String(week.id) === String(weekId));
                    if (week) {
                        return { termId: term.id, weekId: week.id };
                    }
                }
                return { termId: null, weekId: null };
            }

            function selectTermAndWeek(termId, weekId) {
                if (!termId) return;

                termSelect.value = termId;
                updateWeeks(weekId);

                if (weekId) {
                    weekSelect.value = weekId;
                    sessionStorage.setItem(storageWeekKey, weekId);
                }

                sessionStorage.setItem(storageTermKey, termId);

                const savedTerms = JSON.parse(sessionStorage.getItem('collapsible_terms') || '{}');
                savedTerms[termId] = true;
                sessionStorage.setItem('collapsible_terms', JSON.stringify(savedTerms));

                if (weekId) {
                    const savedWeeks = JSON.parse(sessionStorage.getItem('collapsible_weeks') || '{}');
                    savedWeeks[weekId] = true;
                    sessionStorage.setItem('collapsible_weeks', JSON.stringify(savedWeeks));
                }

                if (titleInput) {
                    titleInput.focus();
                }
            }

            document.querySelectorAll('.add-assignment-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const termId = button.dataset.termId;
                    const weekId = button.dataset.weekId || null;
                    if (termId) {
                        selectTermAndWeek(termId, weekId);
                    }
                });
            });

            const urlParams = new URL(window.location.href).searchParams;
            const urlWeekId = urlParams.get('week');
            const storedTermId = sessionStorage.getItem(storageTermKey) || null;
            const storedWeekId = sessionStorage.getItem(storageWeekKey) || null;

            let initialWeekId = urlWeekId || storedWeekId || null;
            let initialTermId = storedTermId || null;

            if (initialWeekId && !initialTermId) {
                const { termId } = findTermByWeek(initialWeekId);
                initialTermId = termId;
            }

            if (initialTermId) {
                selectTermAndWeek(initialTermId, initialWeekId);
            } else {
                updateWeeks();
            }

            submissionTypeSelect.dispatchEvent(new Event('change'));
            updateCharacterCounter('title-counter', titleInput ? titleInput.value : '', 500);
            updateCharacterCounter('description-counter', descriptionQuill.root.innerHTML, 5000);
            updateCharacterCounter('instructions-counter', instructionsQuill.root.innerHTML, 5000);

            if (urlWeekId) {
                urlParams.delete('week');
                const newUrl = `${window.location.pathname}${urlParams.toString() ? '?' + urlParams.toString() : ''}`;
                window.history.replaceState({}, document.title, newUrl);
            }
        });
    </script>
</x-teacher-layout>