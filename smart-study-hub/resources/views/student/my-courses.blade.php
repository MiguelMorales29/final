<x-student-layout>
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Courses</h1>
            <p class="text-gray-600 dark:text-gray-300">Continue your learning journey with your enrolled courses</p>
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

        @php
            // Combine traditional enrollments and approved applications
            $allCourses = collect();
            
            // Add traditional enrollments
            foreach($enrollments as $enrollment) {
                $allCourses->push([
                    'id' => $enrollment->course->id,
                    'title' => $enrollment->course->title,
                    'teacher' => $enrollment->course->teacher->name,
                    'desc' => $enrollment->course->description,
                    'cover' => $enrollment->course->image ? asset('storage/' . $enrollment->course->image) : asset('images/default-course.png'),
                    'weeks' => '12', // Default weeks
                    'progress' => 0, // Default progress
                    'next_due' => null,
                    'status' => 'enrolled',
                    'locked' => false,
                    'enrolled_at' => $enrollment->enrolled_at
                ]);
            }
            
            // Add approved applications
            foreach($approvedApplications as $application) {
                $allCourses->push([
                    'id' => $application->course->id,
                    'title' => $application->course->title,
                    'teacher' => $application->course->teacher->name,
                    'desc' => $application->course->description,
                    'cover' => $application->course->image ? asset('storage/' . $application->course->image) : asset('images/default-course.png'),
                    'weeks' => '12', // Default weeks
                    'progress' => 0, // Default progress
                    'next_due' => null,
                    'status' => 'enrolled',
                    'locked' => false,
                    'enrolled_at' => $application->reviewed_at ?? $application->created_at
                ]);
            }
            
            // Add pending applications
            foreach($pendingApplications as $application) {
                $allCourses->push([
                    'id' => $application->course->id,
                    'title' => $application->course->title,
                    'teacher' => $application->course->teacher->name,
                    'desc' => $application->course->description,
                    'cover' => $application->course->image ? asset('storage/' . $application->course->image) : asset('images/default-course.png'),
                    'weeks' => '12', // Default weeks
                    'progress' => 0, // Default progress
                    'next_due' => null,
                    'status' => 'pending',
                    'locked' => false,
                    'enrolled_at' => $application->created_at
                ]);
            }
            
            // Add rejected applications (treated as locked courses)
            foreach($rejectedApplications as $application) {
                $allCourses->push([
                    'id' => $application->course->id,
                    'title' => $application->course->title,
                    'teacher' => $application->course->teacher->name,
                    'desc' => $application->course->description,
                    'cover' => $application->course->image ? asset('storage/' . $application->course->image) : asset('images/default-course.png'),
                    'weeks' => '12', // Default weeks
                    'progress' => 0, // Default progress
                    'next_due' => null,
                    'status' => 'rejected',
                    'locked' => true,
                    'enrolled_at' => $application->reviewed_at ?? $application->created_at,
                    'application_id' => $application->id
                ]);
            }
            
            // Add dropped applications (treated as locked courses)
            foreach($droppedApplications as $application) {
                $allCourses->push([
                    'id' => $application->course->id,
                    'title' => $application->course->title,
                    'teacher' => $application->course->teacher->name,
                    'desc' => $application->course->description,
                    'cover' => $application->course->image ? asset('storage/' . $application->course->image) : asset('images/default-course.png'),
                    'weeks' => '12', // Default weeks
                    'progress' => 0, // Default progress
                    'next_due' => null,
                    'status' => 'dropped',
                    'locked' => true,
                    'enrolled_at' => $application->reviewed_at ?? $application->created_at,
                    'application_id' => $application->id
                ]);
            }
        @endphp

        @if($allCourses->count() > 0)
            <!-- Course Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 items-stretch">
                @foreach($allCourses as $c)
                    @if($c['status'] === 'pending')
                        {{-- Pending Course Card --}}
                        <div class="group relative rounded-2xl overflow-hidden bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 ease-out">
                            <div class="relative">
                                <img src="{{ $c['cover'] }}" alt="{{ $c['title'] }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-yellow-500/20 group-hover:bg-yellow-500/30 transition-colors duration-300"></div>
                                <div class="absolute inset-0 flex items-center justify-center z-10">
                                    <div class="text-center">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-yellow-500/30 backdrop-blur-sm mb-2">
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-yellow-700 font-medium text-sm">Pending Approval</p>
                                    </div>
                                </div>
                                <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200">
                                        Pending
                                    </span>
                                </div>
                            </div>
                            <div class="p-5 flex flex-col h-64">
                                <div class="flex-1 min-h-0">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">{{ $c['title'] }}</h3>
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 10-6 0 3 3 0 006 0z"/></svg>
                                            {{ $c['teacher'] }}
                                        </span>
                                        • {{ $c['weeks'] }} weeks
                                    </div>
                                    <div class="mt-3 relative group/desc">
                                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">{{ Str::limit($c['desc'], 80) }}</p>
                                        @if(strlen($c['desc']) > 80)
                                            <div class="absolute top-0 right-0 opacity-0 group-hover/desc:opacity-100 transition-opacity duration-200">
                                                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium bg-white dark:bg-gray-900 px-1 rounded">View More</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 flex-shrink-0">
                                    <div class="h-2 bg-yellow-200 dark:bg-yellow-800 rounded"></div>
                                    <div class="text-xs mt-1 text-gray-500 dark:text-gray-400">Waiting for teacher approval</div>
                                    <button disabled class="mt-3 w-full bg-yellow-500 text-white py-2 px-4 rounded-lg font-medium flex items-center justify-center gap-2 opacity-75 cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pending Approval
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif($c['status'] === 'rejected')
                        {{-- Rejected Course Card --}}
                        <div class="group relative rounded-2xl overflow-hidden bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 ease-out">
                            <div class="relative">
                                <img src="{{ $c['cover'] }}" alt="{{ $c['title'] }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-red-500/20 group-hover:bg-red-500/30 transition-colors duration-300"></div>
                                <div class="absolute inset-0 flex items-center justify-center z-10">
                                    <div class="text-center">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-500/30 backdrop-blur-sm mb-2">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        <p class="text-red-700 font-medium text-sm">Rejected</p>
                                    </div>
                                </div>
                                <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200">
                                        Rejected
                                    </span>
                                </div>
                            </div>
                            <div class="p-5 flex flex-col h-64">
                                <div class="flex-1 min-h-0">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">{{ $c['title'] }}</h3>
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 10-6 0 3 3 0 006 0z"/></svg>
                                            {{ $c['teacher'] }}
                                        </span>
                                        • {{ $c['weeks'] }} weeks
                                    </div>
                                    <div class="mt-3 relative group/desc">
                                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">{{ Str::limit($c['desc'], 80) }}</p>
                                        @if(strlen($c['desc']) > 80)
                                            <div class="absolute top-0 right-0 opacity-0 group-hover/desc:opacity-100 transition-opacity duration-200">
                                                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium bg-white dark:bg-gray-900 px-1 rounded">View More</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 flex-shrink-0">
                                    <div class="h-2 bg-red-200 dark:bg-red-800 rounded"></div>
                                    <div class="text-xs mt-1 text-gray-500 dark:text-gray-400">Application denied by teacher</div>
                                    <div class="mt-3 flex gap-2">
                                        <form method="POST" action="{{ route('courses.apply', $c['id']) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 text-white py-2 px-4 rounded-lg font-medium flex items-center justify-center gap-2 hover:from-blue-600 hover:to-blue-700 dark:hover:from-blue-700 dark:hover:to-blue-800 transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                </svg>
                                                Apply Again
                                            </button>
                                        </form>
                                        @if(isset($c['application_id']))
                                            <form method="POST" action="{{ route('applications.remove', $c['application_id']) }}" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full bg-gradient-to-r from-gray-500 to-gray-600 dark:from-gray-600 dark:to-gray-700 text-white py-2 px-4 rounded-lg font-medium flex items-center justify-center gap-2 hover:from-gray-600 hover:to-gray-700 dark:hover:from-gray-700 dark:hover:to-gray-800 transition-all duration-200" onclick="return confirm('Remove this course from your view? You can reapply after 24 hours.')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Remove
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(isset($c['locked']) && $c['locked'])
                        {{-- Dropped Course Card --}}
                        <div class="group relative rounded-2xl overflow-hidden bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 ease-out">
                            <div class="relative">
                                <img src="{{ $c['cover'] }}" alt="{{ $c['title'] }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-black/60 group-hover:bg-black/50 transition-colors duration-300"></div>
                                <div class="absolute inset-0 flex items-center justify-center z-10">
                                    <div class="text-center">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm mb-2">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </div>
                                        <p class="text-white font-medium text-sm">Dropped</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5 flex flex-col h-64">
                                <div class="flex-1 min-h-0">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-1">{{ $c['title'] }}</h3>
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 10-6 0 3 3 0 006 0z"/></svg>
                                            {{ $c['teacher'] }}
                                        </span>
                                        • {{ $c['weeks'] }} weeks
                                    </div>
                                    <div class="mt-3 relative group/desc">
                                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">{{ Str::limit($c['desc'], 80) }}</p>
                                        @if(strlen($c['desc']) > 80)
                                            <div class="absolute top-0 right-0 opacity-0 group-hover/desc:opacity-100 transition-opacity duration-200">
                                                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium bg-white dark:bg-gray-900 px-1 rounded">View More</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 flex-shrink-0">
                                    <div class="h-2 bg-gradient-to-r from-orange-400 to-orange-500 dark:from-orange-500 dark:to-orange-600 rounded"></div>
                                    <div class="text-xs mt-1 text-gray-500 dark:text-gray-400">Dropped • Removed by Teacher</div>
                                    <div class="mt-3 flex gap-2">
                                        <button disabled class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 dark:from-orange-600 dark:to-orange-700 text-white py-2 px-4 rounded-lg font-medium flex items-center justify-center gap-2 opacity-75 cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Dropped
                                        </button>
                                        @if(isset($c['application_id']))
                                            <form method="POST" action="{{ route('applications.remove', $c['application_id']) }}" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full bg-gradient-to-r from-gray-500 to-gray-600 dark:from-gray-600 dark:to-gray-700 text-white py-2 px-4 rounded-lg font-medium flex items-center justify-center gap-2 hover:from-gray-600 hover:to-gray-700 dark:hover:from-gray-700 dark:hover:to-gray-800 transition-all duration-200" onclick="return confirm('Remove this course from your view? You can reapply after 24 hours.')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Remove
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Enrolled Course Card --}}
                        <div class="group relative rounded-2xl overflow-hidden bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 ease-out">
                            <div class="relative">
                                <img src="{{ $c['cover'] }}" alt="{{ $c['title'] }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200">
                                        Enrolled
                                    </span>
                                </div>
                            </div>
                            <div class="p-5 flex flex-col h-64">
                                <div class="flex-1 min-h-0">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">{{ $c['title'] }}</h3>
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 10-6 0 3 3 0 006 0z"/></svg>
                                            {{ $c['teacher'] }}
                                        </span>
                                        • {{ $c['weeks'] }} weeks
                                    </div>
                                    <div class="mt-3 relative group/desc">
                                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">{{ Str::limit($c['desc'], 80) }}</p>
                                        @if(strlen($c['desc']) > 80)
                                            <div class="absolute top-0 right-0 opacity-0 group-hover/desc:opacity-100 transition-opacity duration-200">
                                                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium bg-white dark:bg-gray-900 px-1 rounded">View More</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 flex-shrink-0">
                                    <div class="h-2 bg-gray-200 dark:bg-gray-800 rounded">
                                        <div class="h-2 bg-indigo-600 rounded" style="width: {{ $c['progress'] }}%"></div>
                                    </div>
                                    <div class="text-xs mt-1 text-gray-500 dark:text-gray-400">{{ $c['progress'] }}% complete @if($c['next_due']) • Next due: {{ $c['next_due'] }} @endif</div>
                                    <a href="{{ route('student.course.show', $c['id']) }}" class="mt-3 inline-flex justify-center items-center w-full rounded-lg py-2 font-medium bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors duration-200">
                                        Continue Learning
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Collapsible Course Structure View -->
            @php
                $enrolledCourses = $allCourses->where('status', 'enrolled')->where('locked', false);
            @endphp
            
            @if($enrolledCourses->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Course Structure & Materials</h2>
                    <div class="space-y-6" x-data="{ openCourses: {} }">
                        @foreach($enrolledCourses as $courseData)
                            @php
                                $course = \App\Models\Course::with(['terms.subTerms.weeks.materials'])->find($courseData['id']);
                            @endphp
                            @if($course)
                                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center justify-between">
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
                                                <div>
                                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $course->title }}</h3>
                                                    <p class="text-gray-600 dark:text-gray-400">{{ $course->teacher->name }}</p>
                                                </div>
                                            </div>
                                            <button @click="openCourses['{{ $course->id }}'] = !openCourses['{{ $course->id }}']" 
                                                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                                <svg class="w-6 h-6 transition-transform duration-200" 
                                                     :class="{ 'rotate-180': openCourses['{{ $course->id }}'] }" 
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div x-show="openCourses['{{ $course->id }}']" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 transform scale-100"
                                         x-transition:leave-end="opacity-0 transform scale-95"
                                         class="p-6">
                                        @forelse($course->terms as $term)
                                            <div class="mb-6" x-data="{ openTerms: {} }">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $term->name }}</h4>
                                                    <button @click="openTerms['{{ $term->id }}'] = !openTerms['{{ $term->id }}']" 
                                                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                                        <svg class="w-5 h-5 transition-transform duration-200" 
                                                             :class="{ 'rotate-180': openTerms['{{ $term->id }}'] }" 
                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div x-show="openTerms['{{ $term->id }}']" 
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0 transform scale-95"
                                                     x-transition:enter-end="opacity-100 transform scale-100"
                                                     x-transition:leave="transition ease-in duration-150"
                                                     x-transition:leave-start="opacity-100 transform scale-100"
                                                     x-transition:leave-end="opacity-0 transform scale-95"
                                                     class="space-y-4">
                                                    @foreach($term->subTerms as $subTerm)
                                                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4" x-data="{ openWeeks: {} }">
                                                            <div class="flex items-center justify-between mb-3">
                                                                <h5 class="font-medium text-gray-900 dark:text-white">{{ $subTerm->title }}</h5>
                                                                <button @click="openWeeks['{{ $subTerm->id }}'] = !openWeeks['{{ $subTerm->id }}']" 
                                                                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                                                    <svg class="w-4 h-4 transition-transform duration-200" 
                                                                         :class="{ 'rotate-180': openWeeks['{{ $subTerm->id }}'] }" 
                                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>

                                                            <div x-show="openWeeks['{{ $subTerm->id }}']" 
                                                                 x-transition:enter="transition ease-out duration-200"
                                                                 x-transition:enter-start="opacity-0 transform scale-95"
                                                                 x-transition:enter-end="opacity-100 transform scale-100"
                                                                 x-transition:leave="transition ease-in duration-150"
                                                                 x-transition:leave-start="opacity-100 transform scale-100"
                                                                 x-transition:leave-end="opacity-0 transform scale-95"
                                                                 class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                                @foreach($subTerm->weeks as $week)
                                                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                                                        <h6 class="font-medium text-gray-900 dark:text-white mb-2">{{ $week->title }}</h6>
                                                                        @if($week->description)
                                                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $week->description }}</p>
                                                                        @endif
                                                                        
                                                                        @if($week->materials->count() > 0)
                                                                            <div class="space-y-2">
                                                                                @foreach($week->materials as $material)
                                                                                    <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded p-2">
                                                                                        <div class="flex items-center gap-2 flex-1">
                                                                                            @if($material->type === 'video')
                                                                                                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                                                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                                                                </svg>
                                                                                            @elseif($material->type === 'file' || $material->type === 'pdf' || $material->type === 'ppt' || $material->type === 'document')
                                                                                                <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                                </svg>
                                                                                            @else
                                                                                                <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                                                                </svg>
                                                                                            @endif
                                                                                            <span class="text-sm text-gray-900 dark:text-white truncate">{{ $material->title }}</span>
                                                                                            @if($material->is_required)
                                                                                                <span class="text-xs bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-1 py-0.5 rounded">Required</span>
                                                                                            @endif
                                                                                        </div>
                                                                                        <div class="flex gap-1">
                                                                                            @if($material->type === 'video' && $material->youtube_url)
                                                                                                <button onclick="openVideoModal('{{ $material->youtube_url }}', '{{ $material->title }}')" 
                                                                                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 p-1">
                                                                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                                                                        <path d="M8 5v14l11-7z"/>
                                                                                                    </svg>
                                                                                                </button>
                                                                                            @elseif($material->file_path)
                                                                                                <a href="{{ Storage::url($material->file_path) }}" 
                                                                                                   download="{{ $material->file_name }}"
                                                                                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-1">
                                                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                                    </svg>
                                                                                                </a>
                                                                                            @elseif($material->external_url)
                                                                                                <a href="{{ $material->external_url }}" 
                                                                                                   target="_blank"
                                                                                                   class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 p-1">
                                                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                                                                    </svg>
                                                                                                </a>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        @else
                                                                            <p class="text-gray-500 dark:text-gray-400 text-sm">No materials available yet.</p>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-gray-500 dark:text-gray-400">No course structure available yet.</p>
                                        @endforelse
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No courses yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Start your learning journey by enrolling in courses.</p>
                <a href="{{ url('/courses') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50 transition-colors duration-200">
                    Browse Courses
                </a>
            </div>
        @endif
    </div>

    <!-- Video Modal -->
    <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 id="videoModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Video</h3>
                <button onclick="closeVideoModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <div class="aspect-w-16 aspect-h-9">
                    <iframe id="videoIframe" 
                            src="" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen
                            class="w-full h-96">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openVideoModal(url, title) {
            // Convert YouTube URL to embed format
            let embedUrl = url;
            if (url.includes('youtube.com/watch')) {
                const videoId = url.split('v=')[1].split('&')[0];
                embedUrl = `https://www.youtube.com/embed/${videoId}`;
            } else if (url.includes('youtu.be/')) {
                const videoId = url.split('youtu.be/')[1].split('?')[0];
                embedUrl = `https://www.youtube.com/embed/${videoId}`;
            }
            
            document.getElementById('videoModalTitle').textContent = title;
            document.getElementById('videoIframe').src = embedUrl;
            document.getElementById('videoModal').classList.remove('hidden');
        }

        function closeVideoModal() {
            document.getElementById('videoModal').classList.add('hidden');
            document.getElementById('videoIframe').src = '';
        }

        // Close modal when clicking outside
        document.getElementById('videoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeVideoModal();
            }
        });
    </script>
</x-student-layout>