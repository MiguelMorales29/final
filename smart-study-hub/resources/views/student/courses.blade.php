<x-student-layout>
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Available Courses</h1>
            <p class="text-gray-600 dark:text-gray-300">Browse and apply for courses</p>
        </div>

        <!-- Search and Filter Bar -->
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Search Courses
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="search" 
                               placeholder="Search by course title or teacher..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Teacher Filter -->
                <div>
                    <label for="teacherFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Filter by Teacher
                    </label>
                    <select id="teacherFilter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        <option value="">All Teachers</option>
                        @foreach($courses->pluck('teacher.name')->unique() as $teacherName)
                            <option value="{{ $teacherName }}">{{ $teacherName }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Clear Filters -->
                <div class="flex items-end">
                    <button id="clearFilters" class="w-full px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                        Clear Filters
                    </button>
                </div>
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

            @if($courses->count() > 0 || $removedApplications->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                        <div class="group bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border border-gray-200 dark:border-gray-700 course-card" 
                             data-course-title="{{ strtolower($course->title) }}" 
                             data-teacher-name="{{ strtolower($course->teacher->name) }}">
                            <!-- Course Image -->
                            <div class="relative h-48 w-full overflow-hidden">
                                <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/default-course.png') }}" 
                                     alt="{{ $course->title }}" 
                                     class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute top-4 right-4">
                                    <span class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full text-xs font-medium shadow-lg">
                                        {{ $course->enrollments()->count() }} students
                                    </span>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            
                            <!-- Course Content -->
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">{{ $course->title }}</h3>
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3 text-sm leading-relaxed">
                                    {{ Str::limit($course->description, 120) }}
                                </p>
                                
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium">by {{ $course->teacher->name }}</span>
                                    </div>
                                </div>
                                
                                <!-- Application Status Badge -->
                                @if($applications->has($course->id))
                                    @php
                                        $status = $applications[$course->id];
                                    @endphp
                                    <div class="mb-3">
                                        @if($status === 'pending')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Pending Approval
                                            </span>
                                        @elseif($status === 'rejected')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Rejected
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Apply Button -->
                                <div class="w-full">
                                    @if($applications->has($course->id))
                                        @php
                                            $status = $applications[$course->id];
                                        @endphp
                                        @if($status === 'rejected')
                                            <form method="POST" action="{{ route('courses.apply', $course->id) }}">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                        </svg>
                                                        Apply Again
                                                    </div>
                                                </button>
                                            </form>
                                        @else
                                            <button disabled 
                                                    class="w-full bg-gray-400 dark:bg-gray-600 text-white px-4 py-3 rounded-lg font-medium cursor-not-allowed">
                                                <div class="flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Application Pending
                                                </div>
                                            </button>
                                        @endif
                                    @else
                                        <form method="POST" action="{{ route('courses.apply', $course->id) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                                <div class="flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                    </svg>
                                                    Apply for Course
                                                </div>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    {{-- Removed Applications (with cooldown) --}}
                    @foreach($removedApplications as $application)
                        <div class="group bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border border-gray-200 dark:border-gray-700 course-card" 
                             data-course-title="{{ strtolower($application->course->title) }}" 
                             data-teacher-name="{{ strtolower($application->course->teacher->name) }}">
                            <!-- Course Image -->
                            <div class="relative h-48 w-full overflow-hidden">
                                <img src="{{ $application->course->image ? asset('storage/' . $application->course->image) : asset('images/default-course.png') }}" 
                                     alt="{{ $application->course->title }}" 
                                     class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute top-4 right-4">
                                    <span class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full text-xs font-medium shadow-lg">
                                        {{ $application->course->enrollments()->count() }} students
                                    </span>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            
                            <!-- Course Content -->
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">{{ $application->course->title }}</h3>
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3 text-sm leading-relaxed">
                                    {{ Str::limit($application->course->description, 120) }}
                                </p>
                                
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium">by {{ $application->course->teacher->name }}</span>
                                    </div>
                                </div>
                                
                                <!-- Status Badge -->
                                <div class="mb-3">
                                    @if($application->status === 'rejected')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Previously Rejected
                                        </span>
                                    @elseif($application->status === 'dropped')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Previously Dropped
                                        </span>
                                    @endif
                                </div>

                                <!-- Apply Button -->
                                <div class="w-full">
                                    @if($application->cooldown_until && $application->cooldown_until->isFuture())
                                        <button disabled 
                                                class="w-full bg-gray-400 dark:bg-gray-600 text-white px-4 py-3 rounded-lg font-medium cursor-not-allowed">
                                            <div class="flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Wait 24 hours to apply again
                                            </div>
                                        </button>
                                        <div class="text-xs mt-2 text-center text-gray-500 dark:text-gray-400">
                                            Cooldown: {{ $application->cooldown_until->diffForHumans() }}
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('courses.apply', $application->course->id) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white px-4 py-3 rounded-lg font-medium transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                                <div class="flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                    </svg>
                                                    Apply Again
                                                </div>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No courses available</h3>
                    <p class="text-gray-600 dark:text-gray-400">There are no courses available for enrollment at the moment.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const teacherFilter = document.getElementById('teacherFilter');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const courseCards = document.querySelectorAll('.course-card');

            function filterCourses() {
                const searchTerm = searchInput.value.toLowerCase();
                const teacherValue = teacherFilter.value.toLowerCase();

                courseCards.forEach(card => {
                    const courseTitle = card.getAttribute('data-course-title');
                    const teacherName = card.getAttribute('data-teacher-name');

                    const matchesSearch = courseTitle.includes(searchTerm) || teacherName.includes(searchTerm);
                    const matchesTeacher = !teacherValue || teacherName.includes(teacherValue);

                    if (matchesSearch && matchesTeacher) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Event listeners
            searchInput.addEventListener('input', filterCourses);
            teacherFilter.addEventListener('change', filterCourses);
            
            clearFiltersBtn.addEventListener('click', function() {
                searchInput.value = '';
                teacherFilter.value = '';
                filterCourses();
            });

            // Initialize
            filterCourses();
        });
    </script>
</x-student-layout>
