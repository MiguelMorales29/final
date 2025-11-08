<x-teacher-layout>
    <div class="flex items-center mb-8">
        <a href="{{ route('teacher.assignments.show', $assignment) }}" 
           class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Assignment</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Update assignment details</p>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <form method="POST" action="{{ route('teacher.assignments.update', $assignment) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Course Selection -->
            <div>
                <label for="course_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Course *
                </label>
                <select name="course_id" 
                        id="course_id"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Select a course...</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $assignment->course_id) == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Week Selection -->
            <div>
                <label for="course_week_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Week (Optional)
                </label>
                <select name="course_week_id" 
                        id="course_week_id"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select a week...</option>
                </select>
            </div>

            <!-- Assignment Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Assignment Title *
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $assignment->title) }}"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Enter assignment title"
                       required>
            </div>

            <!-- Assignment Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Description *
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Describe the assignment..."
                          required>{{ old('description', strip_tags($assignment->description)) }}</textarea>
            </div>

            <!-- Instructions -->
            <div>
                <label for="instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Instructions (Optional)
                </label>
                <textarea id="instructions" 
                          name="instructions" 
                          rows="3"
                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Provide specific instructions for students...">{{ old('instructions', strip_tags($assignment->instructions)) }}</textarea>
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
                    <option value="both" {{ old('submission_type', $assignment->submission_type) == 'both' ? 'selected' : '' }}>Text and File Upload</option>
                    <option value="text" {{ old('submission_type', $assignment->submission_type) == 'text' ? 'selected' : '' }}>Text Only</option>
                    <option value="file" {{ old('submission_type', $assignment->submission_type) == 'file' ? 'selected' : '' }}>File Upload Only</option>
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
                                       {{ in_array($type, old('allowed_file_types', $assignment->allowed_file_types ?? [])) ? 'checked' : '' }}
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
                               value="{{ old('max_file_size', $assignment->max_file_size) }}"
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
                               value="{{ old('max_files', $assignment->max_files) }}"
                               min="1" 
                               max="10"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Due Date -->
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Due Date (Optional)
                </label>
                <input type="datetime-local" 
                       id="due_date" 
                       name="due_date" 
                       value="{{ old('due_date', $assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : '') }}"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Max Attempts -->
            <div>
                <label for="max_attempts" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Max Attempts *
                </label>
                <input type="number" 
                       id="max_attempts" 
                       name="max_attempts" 
                       value="{{ old('max_attempts', $assignment->max_attempts) }}"
                       min="1" 
                       max="10"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <!-- Points -->
            <div>
                <label for="points" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Points *
                </label>
                <input type="number" 
                       id="points" 
                       name="points" 
                       value="{{ old('points', $assignment->points) }}"
                       min="1" 
                       max="1000"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <!-- Publish Status -->
            <div class="flex items-center">
                <input type="checkbox" 
                       id="is_published" 
                       name="is_published" 
                       value="1"
                       {{ old('is_published', $assignment->is_published) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                <label for="is_published" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                    Publish assignment immediately
                </label>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-4">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-150 ease-in-out">
                    Update Assignment
                </button>
                <a href="{{ route('teacher.assignments.show', $assignment) }}" 
                   class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 font-medium py-3 px-6 rounded-lg transition duration-150 ease-in-out">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const courseSelect = document.getElementById('course_id');
            const weekSelect = document.getElementById('course_week_id');
            const submissionTypeSelect = document.getElementById('submission_type');
            const fileSettings = document.getElementById('file-settings');

            // Load weeks when course changes
            courseSelect.addEventListener('change', function() {
                const courseId = this.value;
                weekSelect.innerHTML = '<option value="">Select a week...</option>';
                
                if (courseId) {
                    fetch(`/teacher/courses/${courseId}/weeks`)
                        .then(response => response.json())
                        .then(weeks => {
                            weeks.forEach(week => {
                                const option = document.createElement('option');
                                option.value = week.id;
                                option.textContent = `${week.term} - ${week.sub_term} - ${week.title}`;
                                if (week.id == {{ $assignment->course_week_id ?? 'null' }}) {
                                    option.selected = true;
                                }
                                weekSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error loading weeks:', error));
                }
            });

            // Show/hide file settings based on submission type
            function toggleFileSettings() {
                const submissionType = submissionTypeSelect.value;
                if (submissionType === 'file' || submissionType === 'both') {
                    fileSettings.style.display = 'block';
                } else {
                    fileSettings.style.display = 'none';
                }
            }

            submissionTypeSelect.addEventListener('change', toggleFileSettings);
            
            // Initialize on page load
            toggleFileSettings();
            
            // Load weeks for current course
            if (courseSelect.value) {
                courseSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-teacher-layout>










