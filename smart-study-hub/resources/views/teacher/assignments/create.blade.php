<x-teacher-layout>
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
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="total-assignments">0</div>
                                    <div class="text-sm text-blue-600 dark:text-blue-400">Total Assignments</div>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="published-assignments">0</div>
                                    <div class="text-sm text-green-600 dark:text-green-400">Published</div>
                                </div>
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400" id="draft-assignments">0</div>
                                    <div class="text-sm text-yellow-600 dark:text-yellow-400">Drafts</div>
                                </div>
                                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400" id="total-submissions">0</div>
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
                            
                            <div id="assignments-preview" class="space-y-6">
                                <!-- Assignments will be loaded here via JavaScript with collapsible structure -->
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p>No assignments yet. Create your first assignment to see it here.</p>
                                </div>
                            </div>
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

            // Course structure data (flatten sub-terms' weeks and include sub_term label)
            const courseTerms = @json($course->terms);
            const courseStructure = courseTerms.map(term => ({
                id: term.id,
                name: term.name,
                weeks: ((term.sub_terms || term.subTerms) || []).flatMap(subTerm => {
                    const weeks = subTerm.weeks || [];
                    return weeks.map(week => ({
                        id: week.id,
                        title: week.title,
                        week_number: week.week_number,
                        sub_term: subTerm.title
                    }));
                })
            }));

            // Initialize Quill editor for description
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

            // Initialize Quill editor for instructions
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

            // Update hidden inputs with HTML content
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

            // Character counter for title
            const titleInput = document.getElementById('title');
            titleInput.addEventListener('input', function() {
                updateCharacterCounter('title-counter', this.value, 500);
            });

            // Function to update character counters
            function updateCharacterCounter(counterId, content, maxLength) {
                const counter = document.getElementById(counterId);
                const textLength = content.replace(/<[^>]*>/g, '').length; // Strip HTML tags for count
                counter.textContent = `${textLength}/${maxLength}`;
                
                if (textLength > maxLength) {
                    counter.classList.add('text-red-500');
                } else {
                    counter.classList.remove('text-red-500');
                }
            }

            // Handle term change
            termSelect.addEventListener('change', function() {
                updateWeeks(this.value);
            });

            // Handle submission type change
            submissionTypeSelect.addEventListener('change', function() {
                if (this.value === 'file' || this.value === 'both') {
                    fileSettings.style.display = 'block';
                } else {
                    fileSettings.style.display = 'none';
                }
            });

            // Update weeks dropdown
            function updateWeeks(termId) {
                weekSelect.innerHTML = '<option value="">Choose a week...</option>';
                
                if (termId) {
                    const selectedTerm = courseStructure.find(term => term.id == termId);
                    if (selectedTerm && selectedTerm.weeks) {
                        selectedTerm.weeks.forEach(week => {
                            const option = document.createElement('option');
                            option.value = week.id;
                            option.textContent = `${week.sub_term || 'Week'} - ${week.title}`;
                            weekSelect.appendChild(option);
                        });
                    }
                }
            }

            // Load assignments preview
            loadAssignmentsPreview();

            // Function to load assignments preview
            function loadAssignmentsPreview() {
                fetch(`/teacher/courses/{{ $course->id }}/assignments/preview`)
                    .then(response => response.json())
                    .then(data => {
                        updateStats(data.stats);
                        renderAssignments(data.assignments);
                    })
                    .catch(error => {
                        console.error('Error loading assignments:', error);
                    });
            }

            // Function to update stats
            function updateStats(stats) {
                document.getElementById('total-assignments').textContent = stats.total || 0;
                document.getElementById('published-assignments').textContent = stats.published || 0;
                document.getElementById('draft-assignments').textContent = stats.drafts || 0;
                document.getElementById('total-submissions').textContent = stats.submissions || 0;
            }

            // Function to render assignments
            function renderAssignments(assignments) {
                const container = document.getElementById('assignments-preview');
                
                if (assignments.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>No assignments yet. Create your first assignment to see it here.</p>
                        </div>
                    `;
                    return;
                }

                // Group assignments by term first, then by week
                const termGroups = {};
                assignments.forEach(assignment => {
                    const termName = assignment.term_name || 'Unassigned';
                    if (!termGroups[termName]) {
                        termGroups[termName] = {};
                    }
                    const weekTitle = assignment.week_title || 'Unassigned';
                    if (!termGroups[termName][weekTitle]) {
                        termGroups[termName][weekTitle] = [];
                    }
                    termGroups[termName][weekTitle].push(assignment);
                });

                let html = '';
                Object.keys(termGroups).forEach(termName => {
                    const termAssignments = termGroups[termName];
                    const totalAssignments = Object.values(termAssignments).flat().length;
                    const totalWeeks = Object.keys(termAssignments).length;
                    
                    html += `
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                            <!-- Term Header (Collapsible) -->
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
                                 onclick="toggleTerm('${termName.replace(/'/g, "\\'")}')">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">${termName}</h4>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <!-- Summary Row -->
                                        <div class="text-right">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                ${totalWeeks} weeks, ${totalAssignments} assignments
                                            </div>
                                        </div>
                                        <!-- Collapse/Expand Icon -->
                                        <svg class="w-6 h-6 text-gray-400 transition-transform duration-200" 
                                             id="term-icon-${termName.replace(/[^a-zA-Z0-9]/g, '')}" 
                                             style="transform: rotate(180deg);"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Term Content (Collapsible) -->
                            <div id="term-content-${termName.replace(/[^a-zA-Z0-9]/g, '')}" class="p-6" style="display: block;">
                                <div class="space-y-4">
                    `;
                    
                    Object.keys(termAssignments).forEach(weekTitle => {
                        const weekAssignments = termAssignments[weekTitle];
                        const weekId = `week-${termName.replace(/[^a-zA-Z0-9]/g, '')}-${weekTitle.replace(/[^a-zA-Z0-9]/g, '')}`;
                        
                        html += `
                            <div class="border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center justify-between cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg p-2 -m-2 transition-colors duration-200"
                                         onclick="toggleWeek('${weekId}')">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <div>
                                                <h6 class="font-medium text-gray-900 dark:text-white">${weekTitle}</h6>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                ${weekAssignments.length} assignment${weekAssignments.length !== 1 ? 's' : ''}
                                            </span>
                                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" 
                                                 id="week-icon-${weekId}" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="week-content-${weekId}" class="p-4" style="display: none;">
                                    <div class="space-y-3">
                        `;
                        
                        weekAssignments.forEach(assignment => {
                            html += `
                                <div class="item-card bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:shadow-md hover:scale-[1.02] transition-all duration-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <span class="font-medium text-gray-900 dark:text-white">${assignment.title}</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400 capitalize bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                                    Assignment
                                                </span>
                                                <span class="px-2 py-1 text-xs rounded-full ${assignment.is_published ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'}">
                                                    ${assignment.is_published ? 'Published' : 'Draft'}
                                                </span>
                                            </div>
                                            ${assignment.description ? `<div class="text-sm text-gray-600 dark:text-gray-400 mb-2 prose prose-sm max-w-none">${assignment.description}</div>` : ''}
                                            <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                                <span>${assignment.points} points</span>
                                                <span>Due: ${new Date(assignment.due_date).toLocaleDateString()}</span>
                                                <span>Max Attempts: ${assignment.max_attempts}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 ml-4">
                                            <button onclick="editAssignment(${assignment.id})" 
                                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-2 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20 transition duration-150 ease-in-out"
                                                    title="Edit Assignment">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <button onclick="deleteAssignment(${assignment.id})" 
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 p-2 rounded hover:bg-red-50 dark:hover:bg-red-900/20 transition duration-150 ease-in-out"
                                                    title="Delete Assignment">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        
                        html += `
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += `
                                </div>
                            </div>
                        </div>
                    `;
                });

                container.innerHTML = html;
            }

            // Toggle functions for collapsible sections (make them global)
            window.toggleTerm = function(termName) {
                const content = document.getElementById(`term-content-${termName.replace(/[^a-zA-Z0-9]/g, '')}`);
                const icon = document.getElementById(`term-icon-${termName.replace(/[^a-zA-Z0-9]/g, '')}`);
                
                if (content && icon) {
                    if (content.style.display === 'none' || content.style.display === '') {
                        content.style.display = 'block';
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        content.style.display = 'none';
                        icon.style.transform = 'rotate(0deg)';
                    }
                }
            }

            window.toggleWeek = function(weekId) {
                const content = document.getElementById(`week-content-${weekId}`);
                const icon = document.getElementById(`week-icon-${weekId}`);
                
                if (content && icon) {
                    if (content.style.display === 'none' || content.style.display === '') {
                        content.style.display = 'block';
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        content.style.display = 'none';
                        icon.style.transform = 'rotate(0deg)';
                    }
                }
            }

            // Global variables for edit modal
            let editDescriptionQuill = null;
            let editInstructionsQuill = null;
            let currentAssignmentId = null;

            // Initialize edit modal Quill editors
            function initializeEditQuill() {
                if (editDescriptionQuill) {
                    editDescriptionQuill.destroy();
                }
                
                editDescriptionQuill = new Quill('#edit-description-editor', {
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

                if (editInstructionsQuill) {
                    editInstructionsQuill.destroy();
                }
                
                editInstructionsQuill = new Quill('#edit-instructions-editor', {
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

                // Update hidden inputs with HTML content
                editDescriptionQuill.on('text-change', function() {
                    const html = editDescriptionQuill.root.innerHTML;
                    document.getElementById('edit_description').value = html;
                    updateCharacterCounter('edit-description-counter', html, 5000);
                });

                editInstructionsQuill.on('text-change', function() {
                    const html = editInstructionsQuill.root.innerHTML;
                    document.getElementById('edit_instructions').value = html;
                    updateCharacterCounter('edit-instructions-counter', html, 5000);
                });
            }

            // Global functions for assignment actions
            window.editAssignment = function(assignmentId) {
                console.log('Edit assignment:', assignmentId);
                currentAssignmentId = assignmentId;
                
                // Show modal
                document.getElementById('editAssignmentModal').classList.remove('hidden');
                
                // Initialize Quill editors
                setTimeout(() => {
                    initializeEditQuill();
                }, 100);
                
                // Fetch assignment data
                fetch(`/teacher/assignments/${assignmentId}/edit-data`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            throw new Error(data.error);
                        }
                        
                        // Populate form fields
                        document.getElementById('edit_course_title').value = data.course_title || '';
                        document.getElementById('edit_term_id').value = data.term_id || '';
                        document.getElementById('edit_title').value = data.title || '';
                        document.getElementById('edit_points').value = data.points || '';
                        document.getElementById('edit_due_date').value = data.due_date || '';
                        document.getElementById('edit_max_attempts').value = data.max_attempts || '';
                        document.getElementById('edit_is_published').checked = data.is_published || false;
                        
                        // Set description in Quill editor
                        if (data.description) {
                            editDescriptionQuill.root.innerHTML = data.description;
                            document.getElementById('edit_description').value = data.description;
                        }
                        
                        // Set instructions in Quill editor
                        if (data.instructions) {
                            editInstructionsQuill.root.innerHTML = data.instructions;
                            document.getElementById('edit_instructions').value = data.instructions;
                        }
                        
                        // Update weeks dropdown
                        if (data.term_id) {
                            updateEditWeeks(data.term_id, data.week_id);
                        }
                        
                        // Update character counters
                        updateCharacterCounter('edit-title-counter', data.title || '', 500);
                        updateCharacterCounter('edit-description-counter', data.description || '', 5000);
                        updateCharacterCounter('edit-instructions-counter', data.instructions || '', 5000);
                    })
                    .catch(error => {
                        console.error('Error fetching assignment data:', error);
                        alert('Error loading assignment data: ' + error.message);
                    });
            };

            window.deleteAssignment = function(assignmentId) {
                if (confirm('Are you sure you want to delete this assignment?')) {
                    fetch(`/teacher/assignments/${assignmentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadAssignmentsPreview(); // Reload the preview
                            alert('Assignment deleted successfully');
                        } else {
                            alert('Error deleting assignment: ' + (data.error || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting assignment:', error);
                        alert('Error deleting assignment: ' + error.message);
                    });
                }
            };

            // Close edit modal
            window.closeEditModal = function() {
                document.getElementById('editAssignmentModal').classList.add('hidden');
                currentAssignmentId = null;
                
                // Reset form
                document.getElementById('editAssignmentForm').reset();
                if (editDescriptionQuill) {
                    editDescriptionQuill.setContents([]);
                }
                if (editInstructionsQuill) {
                    editInstructionsQuill.setContents([]);
                }
            };

            // Handle term change in edit modal
            document.getElementById('edit_term_id').addEventListener('change', function() {
                updateEditWeeks(this.value);
            });

            // Update weeks dropdown in edit modal
            function updateEditWeeks(termId, selectedWeekId = null) {
                const weekSelect = document.getElementById('edit_course_week_id');
                weekSelect.innerHTML = '<option value="">Choose a week...</option>';
                
                if (termId) {
                    const selectedTerm = courseStructure.find(term => term.id == termId);
                    if (selectedTerm && selectedTerm.weeks) {
                        selectedTerm.weeks.forEach(week => {
                            const option = document.createElement('option');
                            option.value = week.id;
                            option.textContent = `${week.sub_term || 'Week'} - ${week.title}`;
                            if (selectedWeekId && week.id == selectedWeekId) {
                                option.selected = true;
                            }
                            weekSelect.appendChild(option);
                        });
                    }
                }
            }

            // Handle edit form submission
            document.getElementById('editAssignmentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!currentAssignmentId) {
                    alert('No assignment selected for editing');
                    return;
                }
                
                const formData = new FormData(this);
                
                fetch(`/teacher/assignments/${currentAssignmentId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeEditModal();
                        loadAssignmentsPreview(); // Reload the preview
                        alert('Assignment updated successfully');
                    } else {
                        alert('Error updating assignment: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error updating assignment:', error);
                    alert('Error updating assignment: ' + error.message);
                });
            });
        });
    </script>
</x-teacher-layout>