<x-teacher-layout>
    <div class="max-w-3xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create Announcement</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Share important information with your students</p>
        </div>

        <!-- Form -->
        <form action="{{ route('teacher.announcements.store') }}" method="POST" class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            @csrf

            <!-- Course Selection -->
            <div class="mb-6">
                <label for="course_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Course <span class="text-red-500">*</span>
                </label>
                <select id="course_id" name="course_id" required
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select a course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Title <span class="text-red-500">*</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Enter announcement title">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Content <span class="text-red-500">*</span>
                </label>
                <textarea id="content" name="content" rows="6" required
                          class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Enter announcement content">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Event Type -->
            <div class="mb-6">
                <label for="event_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Event Type <span class="text-red-500">*</span>
                </label>
                <select id="event_type" name="event_type" required
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>Other</option>
                    <option value="quiz" {{ old('event_type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                    <option value="exam" {{ old('event_type') == 'exam' ? 'selected' : '' }}>Exam</option>
                    <option value="assignment" {{ old('event_type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                </select>
                @error('event_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Custom Event Type (shown only when "other" is selected) -->
            <div class="mb-6" id="custom_event_type_container" style="display: none;">
                <label for="custom_event_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Custom Event Type <span class="text-red-500">*</span>
                </label>
                <input type="text" id="custom_event_type" name="custom_event_type" value="{{ old('custom_event_type') }}"
                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       placeholder="e.g., Lab Session, Project Deadline">
                @error('custom_event_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Event Date -->
            <div class="mb-6">
                <label for="event_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Event Date (Optional)
                </label>
                <input type="datetime-local" id="event_date" name="event_date" value="{{ old('event_date') }}"
                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('event_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">This will appear on students' calendar</p>
            </div>

            <!-- Show on Calendar -->
            <div class="mb-6">
                <div class="flex items-center">
                    <input type="checkbox" id="show_on_calendar" name="show_on_calendar" value="1" {{ old('show_on_calendar') ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="show_on_calendar" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        Show this event on student calendar
                    </label>
                </div>
 @error('show_on_calendar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Enable this to make the event visible on the calendar</p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('teacher.announcements.index') }}" 
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Create Announcement
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('event_type').addEventListener('change', function() {
            const customContainer = document.getElementById('custom_event_type_container');
            const customInput = document.getElementById('custom_event_type');
            
            if (this.value === 'other') {
                customContainer.style.display = 'block';
                customInput.required = true;
            } else {
                customContainer.style.display = 'none';
                customInput.required = false;
                customInput.value = '';
            }
        });

        // Check if "other" is selected on page load
        if (document.getElementById('event_type').value === 'other') {
            document.getElementById('custom_event_type_container').style.display = 'block';
        }
    </script>
</x-teacher-layout>

