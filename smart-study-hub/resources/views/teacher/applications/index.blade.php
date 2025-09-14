<x-teacher-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Course Applications</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Review and manage student applications for your courses</p>
            </div>

            <!-- Search and Filter Bar -->
            <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Search Input -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Search Applications
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="search" 
                                   placeholder="Search by student name or course..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="statusFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Filter by Status
                        </label>
                        <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="dropped">Dropped</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <!-- Course Filter -->
                    <div>
                        <label for="courseFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Filter by Course
                        </label>
                        <select id="courseFilter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Courses</option>
                            @foreach($applications->pluck('course.title')->unique() as $courseTitle)
                                <option value="{{ $courseTitle }}">{{ $courseTitle }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Show Archived Toggle -->
                    <div class="flex items-end">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" 
                                   id="showArchived" 
                                   {{ $showArchived ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Show Archived</span>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap justify-between items-center gap-4">
                    <div class="flex flex-wrap gap-2">
                        <button id="clearFilters" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                            Clear Filters
                        </button>
                        <button id="selectAll" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 rounded-lg transition-colors duration-200">
                            Select All
                        </button>
                        <button id="selectNone" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                            Select None
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('teacher.applications.archived') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                            View Archived
                        </a>
                        <button id="bulkArchiveBtn" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            Archive Selected
                        </button>
                        <button id="archiveAllBtn" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200">
                            Archive All
                        </button>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Applications List -->
            @if($applications->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($applications as $application)
                                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 border border-gray-200 dark:border-gray-600" data-application="{{ $application->id }}">
                                            <!-- Application Header -->
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex items-center space-x-3">
                                                    <!-- Checkbox -->
                                                    <input type="checkbox" 
                                                           class="application-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                                           value="{{ $application->id }}"
                                                           data-status="{{ $application->status }}">
                                                    
                                                    <!-- Student Avatar -->
                                                    <div class="relative">
                                                        <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800" 
                                                             src="{{ $application->student->profile_picture ? asset('storage/' . $application->student->profile_picture) : asset('images/default-avatar.png') }}" 
                                                             alt="{{ $application->student->name }}">
                                                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                                    </div>
                                                    
                                                    <!-- Student Info -->
                                                    <div class="min-w-0 flex-1">
                                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate" data-student-name="{{ $application->student->name }}">
                                                            {{ $application->student->name }}
                                                        </h3>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                            {{ $application->student->email }}
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Status Badge -->
                                                <div class="flex-shrink-0">
                                                    @if($application->status === 'pending')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300" data-status="pending">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Pending
                                                        </span>
                                                    @elseif($application->status === 'approved')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300" data-status="approved">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Approved
                                                        </span>
                                                    @elseif($application->status === 'rejected')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300" data-status="rejected">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                            Rejected
                                                        </span>
                                                    @elseif($application->status === 'dropped')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300" data-status="dropped">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Dropped
                                                        </span>
                                                    @elseif($application->status === 'archived')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300" data-status="archived">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l4 4-4 4m5-4h6"></path>
                                                            </svg>
                                                            Archived
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Course Information -->
                                            <div class="mb-3">
                                                <p class="text-sm text-gray-600 dark:text-gray-300 font-medium mb-1" data-course-name="{{ $application->course->title }}">
                                                    {{ $application->course->title }}
                                                </p>
                                                <div class="flex items-center space-x-3 text-xs text-gray-500 dark:text-gray-400">
                                                    <span>Applied {{ $application->created_at->format('M j, Y') }}</span>
                                                    @if($application->reviewed_at)
                                                        <span>• Reviewed {{ $application->reviewed_at->format('M j, Y') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Teacher's Note -->
                                            @if($application->message)
                                                <div class="mb-3 p-2 bg-blue-50 dark:bg-blue-900/20 rounded border-l-2 border-blue-400">
                                                    <p class="text-xs text-blue-800 dark:text-blue-200">
                                                        <span class="font-medium">Note:</span> {{ $application->message }}
                                                    </p>
                                                </div>
                                            @endif
                                            
                                            <!-- Action Buttons -->
                                            <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                                                @if($application->status === 'pending')
                                                    <div class="space-y-2">
                                                        <div class="flex space-x-2">
                                                            <form method="POST" action="{{ route('applications.approve', $application->id) }}" class="flex-1">
                                                                @csrf
                                                                <input type="text" 
                                                                       name="message" 
                                                                       placeholder="Optional message..." 
                                                                       class="w-full text-xs border border-gray-300 dark:border-gray-600 rounded px-2 py-1 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-1 focus:ring-green-500 focus:border-transparent mb-2">
                                                                <button type="submit" 
                                                                        class="w-full inline-flex items-center justify-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition duration-150 ease-in-out">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                    Approve
                                                                </button>
                                                            </form>
                                                            
                                                            <form method="POST" action="{{ route('applications.reject', $application->id) }}" class="flex-1">
                                                                @csrf
                                                                <input type="text" 
                                                                       name="message" 
                                                                       placeholder="Optional message..." 
                                                                       class="w-full text-xs border border-gray-300 dark:border-gray-600 rounded px-2 py-1 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-1 focus:ring-red-500 focus:border-transparent mb-2">
                                                                <button type="submit" 
                                                                        class="w-full inline-flex items-center justify-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition duration-150 ease-in-out">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                    </svg>
                                                                    Reject
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @elseif($application->status === 'approved')
                                                    <button type="button" 
                                                            onclick="openDropModal('{{ $application->student->name }}', '{{ $application->course->title }}', {{ $application->student->id }}, {{ $application->course->id }})"
                                                            class="w-full inline-flex items-center justify-center px-3 py-1 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded transition duration-150 ease-in-out">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Drop Student
                                                    </button>
                                                @else
                                                    <div class="flex items-center justify-between">
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ ucfirst($application->status) }} on {{ $application->reviewed_at->format('M j, Y') }}
                                                        </div>
                                                        @if($application->status !== 'archived')
                                                            <form method="POST" action="{{ route('applications.archive', $application->id) }}" class="inline">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="inline-flex items-center px-2 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded transition duration-150 ease-in-out"
                                                                        onclick="return confirm('Archive this application?')">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l4 4-4 4m5-4h6"></path>
                                                                    </svg>
                                                                    Archive
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="mx-auto w-32 h-32 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">No Applications Found</h3>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                        Students haven't applied for any of your courses yet, or the current filters don't match any applications.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button onclick="document.getElementById('clearFilters').click()" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Clear Filters
                        </button>
                        <a href="{{ route('teacher.courses.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Manage Courses
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Bulk Archive Form -->
    <form id="bulkArchiveForm" method="POST" action="{{ route('applications.bulk-archive') }}" style="display: none;">
        @csrf
        <div id="bulkArchiveInputs"></div>
    </form>

    <!-- Archive All Form -->
    <form id="archiveAllForm" method="POST" action="{{ route('applications.archive-all') }}" style="display: none;">
        @csrf
    </form>

    <!-- Drop Student Confirmation Modal -->
    <div id="dropStudentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Drop Student</h3>
                    </div>
                    <button onclick="closeDropModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Are you sure you want to drop <span id="studentName" class="font-semibold text-gray-900 dark:text-white"></span> from <span id="courseName" class="font-semibold text-gray-900 dark:text-white"></span>?
                    </p>
                    
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md p-3 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    <strong>Warning:</strong> This action will immediately remove the student from the course and notify them via email. This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form id="dropStudentForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        
                        <div class="mb-4">
                            <label for="dropReason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Reason for dropping (optional)
                            </label>
                            <textarea id="dropReason" 
                                      name="message" 
                                      rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Please provide a reason for dropping this student..."></textarea>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 px-7 py-3 bg-gray-50 dark:bg-gray-700/50 rounded-b-md">
                    <button onclick="closeDropModal()" 
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-500 transition duration-150 ease-in-out">
                        Cancel
                    </button>
                    <button onclick="confirmDropStudent()" 
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Drop Student
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDropModal(studentName, courseName, studentId, courseId) {
            document.getElementById('studentName').textContent = studentName;
            document.getElementById('courseName').textContent = courseName;
            document.getElementById('dropStudentForm').action = `/teacher/courses/${courseId}/students/${studentId}`;
            document.getElementById('dropStudentModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDropModal() {
            document.getElementById('dropStudentModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('dropReason').value = '';
        }

        function confirmDropStudent() {
            document.getElementById('dropStudentForm').submit();
        }

        // Close modal when clicking outside
        document.getElementById('dropStudentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDropModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDropModal();
            }
        });

        // Search and Filter Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const statusFilter = document.getElementById('statusFilter');
            const courseFilter = document.getElementById('courseFilter');
            const showArchivedCheckbox = document.getElementById('showArchived');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const selectAllBtn = document.getElementById('selectAll');
            const selectNoneBtn = document.getElementById('selectNone');
            const bulkArchiveBtn = document.getElementById('bulkArchiveBtn');
            const archiveAllBtn = document.getElementById('archiveAllBtn');
            const applicationCards = document.querySelectorAll('[data-application]');
            const checkboxes = document.querySelectorAll('.application-checkbox');

            function filterApplications() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const courseValue = courseFilter.value;

                applicationCards.forEach(card => {
                    const studentName = card.querySelector('[data-student-name]')?.textContent.toLowerCase() || '';
                    const courseName = card.querySelector('[data-course-name]')?.textContent.toLowerCase() || '';
                    const statusElement = card.querySelector('[data-status]');
                    const status = statusElement?.getAttribute('data-status') || '';

                    const matchesSearch = studentName.includes(searchTerm) || courseName.includes(searchTerm);
                    const matchesStatus = !statusValue || status === statusValue;
                    const matchesCourse = !courseValue || courseName.includes(courseValue.toLowerCase());

                    if (matchesSearch && matchesStatus && matchesCourse) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });

                updateBulkArchiveButton();
            }

            function updateBulkArchiveButton() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                bulkArchiveBtn.disabled = checkedBoxes.length === 0;
            }

            function updateSelectAllButton() {
                const visibleCheckboxes = Array.from(checkboxes).filter(cb => {
                    const card = cb.closest('[data-application]');
                    return card && card.style.display !== 'none';
                });
                const checkedVisibleBoxes = visibleCheckboxes.filter(cb => cb.checked);
                
                if (checkedVisibleBoxes.length === 0) {
                    selectAllBtn.textContent = 'Select All';
                } else if (checkedVisibleBoxes.length === visibleCheckboxes.length) {
                    selectAllBtn.textContent = 'Select None';
                } else {
                    selectAllBtn.textContent = 'Select All';
                }
            }

            // Event listeners
            searchInput.addEventListener('input', filterApplications);
            statusFilter.addEventListener('change', filterApplications);
            courseFilter.addEventListener('change', filterApplications);
            showArchivedCheckbox.addEventListener('change', function() {
                const url = new URL(window.location);
                if (this.checked) {
                    url.searchParams.set('show_archived', '1');
                } else {
                    url.searchParams.delete('show_archived');
                }
                window.location.href = url.toString();
            });

            clearFiltersBtn.addEventListener('click', function() {
                searchInput.value = '';
                statusFilter.value = '';
                courseFilter.value = '';
                filterApplications();
            });

            selectAllBtn.addEventListener('click', function() {
                const visibleCheckboxes = Array.from(checkboxes).filter(cb => {
                    const card = cb.closest('[data-application]');
                    return card && card.style.display !== 'none';
                });
                const allChecked = visibleCheckboxes.every(cb => cb.checked);
                
                visibleCheckboxes.forEach(cb => {
                    cb.checked = !allChecked;
                });
                
                updateBulkArchiveButton();
                updateSelectAllButton();
            });

            selectNoneBtn.addEventListener('click', function() {
                checkboxes.forEach(cb => {
                    cb.checked = false;
                });
                updateBulkArchiveButton();
                updateSelectAllButton();
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateBulkArchiveButton();
                    updateSelectAllButton();
                });
            });

            bulkArchiveBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                if (checkedBoxes.length === 0) return;

                if (confirm(`Archive ${checkedBoxes.length} selected application(s)?`)) {
                    const form = document.getElementById('bulkArchiveForm');
                    const inputsContainer = document.getElementById('bulkArchiveInputs');
                    inputsContainer.innerHTML = '';

                    checkedBoxes.forEach(checkbox => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'application_ids[]';
                        input.value = checkbox.value;
                        inputsContainer.appendChild(input);
                    });

                    form.submit();
                }
            });

            archiveAllBtn.addEventListener('click', function() {
                if (confirm('Archive ALL applications? This action cannot be undone.')) {
                    document.getElementById('archiveAllForm').submit();
                }
            });

            // Initialize
            filterApplications();
            updateBulkArchiveButton();
            updateSelectAllButton();
        });
    </script>
</x-teacher-layout>
