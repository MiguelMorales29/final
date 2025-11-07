<x-teacher-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Top Bar -->
        <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-4">
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">All Enrolled Students</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Manage students across all your courses and sections</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Search Bar -->
                        <div class="relative">
                            <input type="text" id="searchStudents" placeholder="Search by name, student number, or course..." 
                                   class="w-80 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Section Filter -->
                        <select id="sectionFilter" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section }}">{{ $section }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Calendar Section -->
            <div class="bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-100 dark:from-slate-800 dark:via-gray-800 dark:to-slate-900 rounded-xl shadow-xl border border-emerald-200 dark:border-slate-700 mb-8 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 px-6 py-4">
                    <h3 class="text-2xl font-black text-gray-900 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Attendance Calendar
                    </h3>
                </div>
                <div class="p-6">
                    <div id="attendanceCalendar" class="grid grid-cols-7 gap-2 text-center">
                        <!-- Calendar will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Students by Section -->
            @if($courses->count() > 0)
                @foreach($courses as $courseData)
                    @if($courseData['students']->count() > 0)
                        @php
                            $bannerImages = [
                                'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Education/Books
                                'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Students studying
                                'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Science lab
                                'https://images.unsplash.com/photo-1556761175-b413da4baf72?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Mathematics
                                'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Technology
                                'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Library
                                'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Art/Creative
                                'https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Online learning
                                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Writing/Research
                                'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // History
                                'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Data analysis
                                'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'  // Language learning
                            ];
                            $bannerGradients = [
                                'bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800',
                                'bg-gradient-to-r from-green-600 via-teal-600 to-green-800',
                                'bg-gradient-to-r from-purple-600 via-pink-600 to-purple-800',
                                'bg-gradient-to-r from-red-600 via-orange-600 to-red-800',
                                'bg-gradient-to-r from-indigo-600 via-blue-600 to-indigo-800',
                                'bg-gradient-to-r from-pink-600 via-rose-600 to-pink-800',
                                'bg-gradient-to-r from-yellow-600 via-orange-600 to-yellow-800',
                                'bg-gradient-to-r from-teal-600 via-cyan-600 to-teal-800',
                                'bg-gradient-to-r from-gray-600 via-slate-600 to-gray-800',
                                'bg-gradient-to-r from-emerald-600 via-green-600 to-emerald-800',
                                'bg-gradient-to-r from-violet-600 via-purple-600 to-violet-800',
                                'bg-gradient-to-r from-amber-600 via-yellow-600 to-amber-800'
                            ];
                            $bannerIndex = ($courseData['course']->id - 1) % 12;
                            $bannerImage = $bannerImages[$bannerIndex];
                            $bannerGradient = $bannerGradients[$bannerIndex];
                        @endphp
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 mb-8 section-container overflow-hidden" data-section="{{ $courseData['section'] }}">
                            <!-- Section Header -->
                            <div class="banner-header relative px-8 py-6 text-white {{ $bannerGradient }}" style="background-image: url('{{ $bannerImage }}');">
                                <div class="banner-content">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="text-2xl font-bold text-white">{{ $courseData['course']->title }}</h3>
                                                    <p class="text-white/80 text-sm">{{ $courseData['students']->count() }} Active Students</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="text-right">
                                                <div class="text-white/80 text-sm font-medium">Section</div>
                                                <div class="text-white font-semibold text-lg">{{ $courseData['section'] }}</div>
                                            </div>
                                            <button onclick="submitRollCall({{ $courseData['course']->id }}, '{{ $courseData['section'] }}')" 
                                                    class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-6 py-3 rounded-xl text-sm font-semibold transition-all duration-200 transform hover:scale-105 hover:shadow-lg border border-white/30">
                                                📋 Submit Roll Call
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Students Table -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student Number</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Attendance Today</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($courseData['students'] as $enrolled)
                                            @php
                                                $student = $enrolled['student'];
                                                $profilePictureUrl = null;

                                                if (!empty($student->profile_picture)) {
                                                    $profilePictureUrl = asset('storage/' . $student->profile_picture);
                                                } elseif (!empty($student->profile_photo_path)) {
                                                    $profilePictureUrl = asset('storage/' . $student->profile_photo_path);
                                                } elseif (!empty($student->profile_photo_url ?? null)) {
                                                    $profilePictureUrl = $student->profile_photo_url;
                                                }

                                                $profileData = [
                                                    'id' => $student->id,
                                                    'name' => $student->name,
                                                    'email' => $student->email,
                                                    'student_number' => $student->student_number,
                                                    'bio' => $student->bio,
                                                    'gender' => $student->gender,
                                                    'birth_date' => optional($student->birth_date)->toDateString(),
                                                    'birth_date_formatted' => optional($student->birth_date)->format('M j, Y'),
                                                    'profile_picture_url' => $profilePictureUrl,
                                                    'courses' => [[
                                                        'id' => $enrolled['course_id'],
                                                        'title' => $enrolled['course_title'],
                                                        'section' => $enrolled['section'],
                                                    ]],
                                                ];
                                            @endphp
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 student-row" data-section="{{ $enrolled['section'] }}" data-course="{{ $enrolled['course_title'] }}" data-course-id="{{ $enrolled['course_id'] }}" data-student-id="{{ $student->id }}" data-name="{{ strtolower($student->name) }}" data-student-number="{{ strtolower($student->student_number ?? '') }}" data-email="{{ strtolower($student->email) }}" data-profile='@json($profileData)'>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            @if($profilePictureUrl)
                                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $profilePictureUrl }}" alt="{{ $student->name }}">
                                                            @else
                                                                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                                                    <span class="text-sm font-medium text-white">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $enrolled['student']->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">
                                                    {{ $enrolled['student']->student_number ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $enrolled['student']->email }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center space-x-2">
                                                        <button onclick="markAttendance({{ $enrolled['student']->id }}, {{ $enrolled['course_id'] }}, 'present')" 
                                                                data-student-id="{{ $enrolled['student']->id }}"
                                                                class="attendance-btn present-btn p-1 rounded-full hover:bg-green-100 dark:hover:bg-green-900/30 {{ $enrolled['attendance_today'] === 'present' ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'text-gray-400 dark:text-gray-500' }}"
                                                                title="Present">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </button>
                                                        <button onclick="markAttendance({{ $enrolled['student']->id }}, {{ $enrolled['course_id'] }}, 'late')" 
                                                                data-student-id="{{ $enrolled['student']->id }}"
                                                                class="attendance-btn late-btn p-1 rounded-full hover:bg-yellow-100 dark:hover:bg-yellow-900/30 {{ $enrolled['attendance_today'] === 'late' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400' : 'text-gray-400 dark:text-gray-500' }}"
                                                                title="Late">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                        </button>
                                                        <button onclick="markAttendance({{ $enrolled['student']->id }}, {{ $enrolled['course_id'] }}, 'absent')" 
                                                                data-student-id="{{ $enrolled['student']->id }}"
                                                                class="attendance-btn absent-btn p-1 rounded-full hover:bg-red-100 dark:hover:bg-red-900/30 {{ $enrolled['attendance_today'] === 'absent' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : 'text-gray-400 dark:text-gray-500' }}"
                                                                title="Absent">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex items-center space-x-2">
                                                        <button onclick="viewProfile({{ $enrolled['student']->id }})" 
                                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                            </svg>
                                                        </button>
                                                        <button onclick="dropStudent({{ $enrolled['student']->id }}, {{ $enrolled['course_id'] }}, '{{ $enrolled['student']->name }}')" 
                                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No enrolled students found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Students will appear here once they are approved for your courses.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Student Profile Modal -->
    <div id="profileModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Student Profile</h3>
                    <button onclick="closeProfileModal()" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div id="profileContent">
                    <!-- Profile content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        const routes = {
            studentProfile: "{{ route('teacher.student.profile', ['student' => '__STUDENT__']) }}",
            attendanceDate: "{{ route('teacher.attendance.date', ['course' => '__COURSE__']) }}",
            attendanceMarkGlobal: "{{ route('teacher.attendance.mark.global') }}",
            dropStudent: "{{ route('teacher.enrolled.drop', ['course' => '__COURSE__']) }}",
            rollCallSubmit: "{{ route('teacher.attendance.roll-call-submit') }}"
        };

        // Get today's date in local timezone
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        let selectedDate = `${year}-${month}-${day}`;
        
        console.log('Initial selected date:', selectedDate);

        // Initialize calendar
        function initCalendar() {
            console.log('Starting calendar initialization...');
            const calendar = document.getElementById('attendanceCalendar');
            console.log('Calendar element found:', calendar);
            
            if (!calendar) {
                console.error('Calendar element not found!');
                return;
            }
            
            try {
                const today = new Date();
                const currentMonth = today.getMonth();
                const currentYear = today.getFullYear();
                
                console.log('Current date:', today);
                console.log('Current month:', currentMonth);
                console.log('Current year:', currentYear);
                
                // Get first day of month and number of days
                const firstDay = new Date(currentYear, currentMonth, 1);
                const lastDay = new Date(currentYear, currentMonth + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startingDayOfWeek = firstDay.getDay();
                
                console.log('Days in month:', daysInMonth);
                console.log('Starting day of week:', startingDayOfWeek);
                
                // Calendar header
                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"];
                
                // Clear calendar first
                calendar.innerHTML = '';
                
                // Add month header
                const monthHeader = document.createElement('div');
                monthHeader.className = 'col-span-7 text-center text-2xl font-bold text-emerald-800 dark:text-white mb-4';
                monthHeader.textContent = `${monthNames[currentMonth]} ${currentYear}`;
                calendar.appendChild(monthHeader);
                
                // Add day headers
                const dayHeaders = document.createElement('div');
                dayHeaders.className = 'col-span-7 grid grid-cols-7 gap-2 mb-4';
                dayHeaders.innerHTML = `
                    <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">Sun</div>
                    <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">Mon</div>
                    <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">Tue</div>
                    <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">Wed</div>
                    <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">Thu</div>
                    <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">Fri</div>
                    <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">Sat</div>
                `;
                calendar.appendChild(dayHeaders);
                
                // Add empty cells for days before month starts
                for (let i = 0; i < startingDayOfWeek; i++) {
                    const emptyDiv = document.createElement('div');
                    emptyDiv.className = 'h-8 w-8 flex items-center justify-center mx-auto';
                    calendar.appendChild(emptyDiv);
                }
                
                // Get today's date once outside the loop
                const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
                console.log('Today string:', todayStr);
                console.log('Selected date:', selectedDate);
                console.log('Are they equal?', todayStr === selectedDate);
                
                // Add days of month
                for (let day = 1; day <= daysInMonth; day++) {
                    const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const isSelected = dateStr === selectedDate;
                    const isCurrentDay = dateStr === todayStr;
                    
                    // Debug logging for day 14 and 15
                    if (day === 14 || day === 15) {
                        console.log(`Day ${day}:`, {
                            dateStr,
                            isSelected,
                            isCurrentDay,
                            selectedDate,
                            todayStr
                        });
                    }
                    
                    const dayButton = document.createElement('button');
                    dayButton.onclick = () => selectDate(dateStr);
                    dayButton.className = 'h-8 w-8 text-sm font-bold transition-all duration-200 transform hover:scale-105 hover:shadow-md flex items-center justify-center mx-auto';
                    
                    // Show circles based on current day and selected day
                    if (isCurrentDay) {
                        // Current day - always show emerald circle (default)
                        dayButton.className += ' rounded-full bg-emerald-500 text-black shadow-lg ring-2 ring-emerald-300';
                    } else if (isSelected) {
                        // Selected day (but not current day) - show blue circle
                        dayButton.className += ' rounded-full bg-blue-500 text-white shadow-lg ring-2 ring-blue-300';
                    } else {
                        // Not current day and not selected - show normal styling
                        dayButton.className += ' text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/20';
                    }
                    
                    dayButton.textContent = day;
                    calendar.appendChild(dayButton);
                }
                
                console.log('Calendar initialization completed successfully!');
            } catch (error) {
                console.error('Error initializing calendar:', error);
            }
        }

        function selectDate(date) {
            selectedDate = date;
            initCalendar();
            loadAttendanceForDate(date);
        }

        function loadAttendanceForDate(date) {
            console.log('Loading attendance for date:', date);
            
            // Reset all attendance buttons to default state
            document.querySelectorAll('.attendance-btn').forEach(btn => {
                btn.classList.remove('bg-green-100', 'dark:bg-green-900/30', 'text-green-600', 'dark:text-green-400');
                btn.classList.remove('bg-yellow-100', 'dark:bg-yellow-900/30', 'text-yellow-600', 'dark:text-yellow-400');
                btn.classList.remove('bg-red-100', 'dark:bg-red-900/30', 'text-red-600', 'dark:text-red-400');
                btn.classList.add('text-gray-400', 'dark:text-gray-500');
            });
            
            // Load attendance for the selected date from server
            // Get all course IDs from the page
            const courseIds = Array.from(new Set(Array.from(document.querySelectorAll('[data-course-id]')).map(row => row.getAttribute('data-course-id'))));
            
            courseIds.forEach(courseId => {
                fetch(routes.attendanceDate.replace('__COURSE__', courseId) + `?date=${encodeURIComponent(date)}`)
                    .then(response => response.json())
                    .then(attendanceData => {
                        console.log('Attendance data for course', courseId, ':', attendanceData);
                        updateAttendanceButtons(attendanceData);
                    })
                    .catch(error => {
                        console.error('Error loading attendance for course', courseId, ':', error);
                    });
            });
        }

        function markAttendance(studentId, courseId, status) {
            console.log('Marking attendance:', { studentId, courseId, status, selectedDate });
            
            // Find the specific row for this student and course
            const studentRow = document.querySelector(`[data-course-id="${courseId}"][data-student-id="${studentId}"]`);
            if (!studentRow) {
                console.error('Student row not found for:', { studentId, courseId });
                return;
            }
            
            // Find buttons within this specific row
            const presentBtn = studentRow.querySelector('.present-btn');
            const lateBtn = studentRow.querySelector('.late-btn');
            const absentBtn = studentRow.querySelector('.absent-btn');
            
            console.log('Found buttons in row:', { presentBtn, lateBtn, absentBtn });
            
            // Reset all buttons first
            [presentBtn, lateBtn, absentBtn].forEach(btn => {
                if (btn) {
                    btn.classList.remove('bg-green-100', 'dark:bg-green-900/30', 'text-green-600', 'dark:text-green-400');
                    btn.classList.remove('bg-yellow-100', 'dark:bg-yellow-900/30', 'text-yellow-600', 'dark:text-yellow-400');
                    btn.classList.remove('bg-red-100', 'dark:bg-red-900/30', 'text-red-600', 'dark:text-red-400');
                    btn.classList.add('text-gray-400', 'dark:text-gray-500');
                }
            });
            
            // Highlight the correct button
            if (status === 'present' && presentBtn) {
                presentBtn.classList.remove('text-gray-400', 'dark:text-gray-500');
                presentBtn.classList.add('bg-green-100', 'dark:bg-green-900/30', 'text-green-600', 'dark:text-green-400');
            } else if (status === 'late' && lateBtn) {
                lateBtn.classList.remove('text-gray-400', 'dark:text-gray-500');
                lateBtn.classList.add('bg-yellow-100', 'dark:bg-yellow-900/30', 'text-yellow-600', 'dark:text-yellow-400');
            } else if (status === 'absent' && absentBtn) {
                absentBtn.classList.remove('text-gray-400', 'dark:text-gray-500');
                absentBtn.classList.add('bg-red-100', 'dark:bg-red-900/30', 'text-red-600', 'dark:text-red-400');
            }
            
            console.log('Sending attendance data:', {
                student_id: studentId,
                course_id: courseId,
                date: selectedDate,
                status: status
            });

            fetch(routes.attendanceMarkGlobal, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    student_id: studentId,
                    course_id: courseId,
                    date: selectedDate,
                    status: status
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Update UI
                    updateAttendanceButtons([data.attendance]);
                } else {
                    console.error('Error from server:', data);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
        }

        function updateAttendanceButtons(attendanceData) {
            console.log('Updating attendance buttons:', attendanceData);
            
            // Handle both single attendance object and array of attendance records
            const attendanceArray = Array.isArray(attendanceData) ? attendanceData : [attendanceData];
            
            // Update buttons based on attendance data
            attendanceArray.forEach(attendance => {
                // Find the specific row for this student AND course (not just student)
                const studentRow = document.querySelector(`[data-course-id="${attendance.course_id}"][data-student-id="${attendance.student_id}"]`);
                if (!studentRow) {
                    console.error('Student row not found for:', { student_id: attendance.student_id, course_id: attendance.course_id });
                    return;
                }
                
                // Find buttons within this specific row
                const presentBtn = studentRow.querySelector('.present-btn');
                const lateBtn = studentRow.querySelector('.late-btn');
                const absentBtn = studentRow.querySelector('.absent-btn');
                
                console.log('Found buttons for student', attendance.student_id, 'in course', attendance.course_id, ':', { presentBtn, lateBtn, absentBtn });
                
                // Reset all buttons first
                [presentBtn, lateBtn, absentBtn].forEach(btn => {
                    if (btn) {
                        btn.classList.remove('bg-green-100', 'dark:bg-green-900/30', 'text-green-600', 'dark:text-green-400');
                        btn.classList.remove('bg-yellow-100', 'dark:bg-yellow-900/30', 'text-yellow-600', 'dark:text-yellow-400');
                        btn.classList.remove('bg-red-100', 'dark:bg-red-900/30', 'text-red-600', 'dark:text-red-400');
                        btn.classList.add('text-gray-400', 'dark:text-gray-500');
                    }
                });
                
                // Highlight the correct button
                if (attendance.status === 'present' && presentBtn) {
                    presentBtn.classList.remove('text-gray-400', 'dark:text-gray-500');
                    presentBtn.classList.add('bg-green-100', 'dark:bg-green-900/30', 'text-green-600', 'dark:text-green-400');
                } else if (attendance.status === 'late' && lateBtn) {
                    lateBtn.classList.remove('text-gray-400', 'dark:text-gray-500');
                    lateBtn.classList.add('bg-yellow-100', 'dark:bg-yellow-900/30', 'text-yellow-600', 'dark:text-yellow-400');
                } else if (attendance.status === 'absent' && absentBtn) {
                    absentBtn.classList.remove('text-gray-400', 'dark:text-gray-500');
                    absentBtn.classList.add('bg-red-100', 'dark:bg-red-900/30', 'text-red-600', 'dark:text-red-400');
                }
            });
        }

        function submitRollCall(courseId, sectionName) {
            if (confirm(`Submit roll call attendance for ${sectionName}? This will notify all students.`)) {
                // Collect attendance data for this course
                const attendanceData = {};
                const courseRows = document.querySelectorAll(`[data-course-id="${courseId}"]`);
                
                courseRows.forEach(row => {
                    const studentId = row.querySelector('[data-student-id]')?.getAttribute('data-student-id');
                    const presentBtn = row.querySelector('.present-btn');
                    const absentBtn = row.querySelector('.absent-btn');
                    
                    if (studentId) {
                        if (presentBtn.classList.contains('bg-green-100') || presentBtn.classList.contains('dark:bg-green-900/30')) {
                            attendanceData[studentId] = 'present';
                        } else if (absentBtn.classList.contains('bg-red-100') || absentBtn.classList.contains('dark:bg-red-900/30')) {
                            attendanceData[studentId] = 'absent';
                        } else {
                            attendanceData[studentId] = 'absent'; // Default to absent if not marked
                        }
                    }
                });

                fetch(routes.rollCallSubmit, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        course_id: courseId,
                        date: selectedDate,
                        attendance_data: attendanceData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Roll call submitted successfully! ${data.notified_students} students have been notified.`);
                    } else {
                        alert('Error submitting roll call. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error submitting roll call. Please try again.');
                });
            }
        }


function escapeHtml(value) {
    if (value === null || value === undefined) {
        return '';
    }

    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function normalizeCourses(rawCourses, fallbackCourse) {
    const array = Array.isArray(rawCourses) ? rawCourses : [];
    const normalized = array
        .map(course => ({
            id: course?.id ?? fallbackCourse?.id ?? null,
            title: course?.title ?? fallbackCourse?.title ?? null,
            section: course?.section ?? fallbackCourse?.section ?? null,
            enrolled_at: course?.enrolled_at ?? fallbackCourse?.enrolled_at ?? null,
        }))
        .filter(course => course.title);

    if (!normalized.length && fallbackCourse?.title) {
        normalized.push({
            id: fallbackCourse.id ?? null,
            title: fallbackCourse.title,
            section: fallbackCourse.section ?? null,
            enrolled_at: fallbackCourse.enrolled_at ?? null,
        });
    }

    return normalized;
}

function buildProfileObject(rawProfile, fallbackCourse) {
    if (!rawProfile || typeof rawProfile !== 'object') {
        return null;
    }

    const profilePictureUrl = rawProfile.profile_picture_url || rawProfile.profile_photo_url || null;

    return {
        id: rawProfile.id ?? null,
        name: rawProfile.name ?? '',
        email: rawProfile.email ?? '',
        student_number: rawProfile.student_number ?? '',
        bio: rawProfile.bio ?? '',
        gender: rawProfile.gender ?? '',
        birth_date: rawProfile.birth_date_formatted ?? rawProfile.birth_date ?? '',
        profile_picture_url: profilePictureUrl,
        courses: normalizeCourses(rawProfile.courses, fallbackCourse),
    };
}

function setProfileContent(html) {
    const contentEl = document.getElementById('profileContent');
    if (contentEl) {
        contentEl.innerHTML = html;
    }
}

function showProfileLoading(message = 'Loading profile...') {
    setProfileContent(`<div class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">${escapeHtml(message)}</div>`);
}

function renderProfile(profile) {
    if (!profile) {
        showProfileLoading('Profile information is not available.');
        return;
    }

    const name = escapeHtml(profile.name || 'Student');
    const email = profile.email ? escapeHtml(profile.email) : '';
    const studentNumber = profile.student_number ? escapeHtml(profile.student_number) : '';
    const bio = profile.bio ? escapeHtml(profile.bio) : 'Not provided';
    const gender = profile.gender ? escapeHtml(profile.gender) : 'Not provided';
    const birthDate = profile.birth_date ? escapeHtml(profile.birth_date) : 'Not provided';
    const profilePictureUrl = profile.profile_picture_url ? escapeHtml(profile.profile_picture_url) : null;
    const courses = Array.isArray(profile.courses) ? profile.courses : [];
    const initial = name ? escapeHtml(name.trim().charAt(0).toUpperCase()) : '?';

    const courseListHtml = courses.length ? `
        <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-4">
            <span class="font-medium text-gray-700 dark:text-gray-300">Enrolled Courses</span>
            <ul class="mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                ${courses.map(course => `
                    <li class="flex items-center justify-between">
                        <span>${escapeHtml(course.title)}</span>
                        ${course.section ? `<span class="text-xs text-gray-500 dark:text-gray-500">Section ${escapeHtml(course.section)}</span>` : ''}
                    </li>
                `).join('')}
            </ul>
        </div>
    ` : '';

    const profileHtml = `
        <div class="text-center mb-4">
            ${profilePictureUrl ?
                `<img class="h-20 w-20 rounded-full mx-auto object-cover shadow" src="${profilePictureUrl}" alt="${name}">` :
                `<div class="h-20 w-20 rounded-full bg-indigo-500 flex items-center justify-center mx-auto">
                    <span class="text-2xl font-medium text-white">${initial}</span>
                </div>`
            }
            <h4 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">${name}</h4>
            ${email ? `<p class="text-sm text-gray-500 dark:text-gray-400">${email}</p>` : ''}
            ${studentNumber ? `<p class="text-sm text-blue-600 dark:text-blue-400 font-mono font-semibold">Student #: ${studentNumber}</p>` : ''}
        </div>
        <div class="space-y-3 text-left">
            <div><span class="font-medium text-gray-700 dark:text-gray-300">Bio:</span> <span class="text-gray-600 dark:text-gray-400">${bio}</span></div>
            <div><span class="font-medium text-gray-700 dark:text-gray-300">Gender:</span> <span class="text-gray-600 dark:text-gray-400">${gender}</span></div>
            <div><span class="font-medium text-gray-700 dark:text-gray-300">Birthdate:</span> <span class="text-gray-600 dark:text-gray-400">${birthDate}</span></div>
            ${courseListHtml}
        </div>
    `;

    setProfileContent(profileHtml);
}

function viewProfile(studentId) {
    const studentRow = document.querySelector(`.student-row[data-student-id="${studentId}"]`);

    if (!studentRow) {
        console.error('Student row not found for profile view', studentId);
        return;
    }

    const fallbackCourse = {
        id: studentRow.getAttribute('data-course-id'),
        title: studentRow.getAttribute('data-course'),
        section: studentRow.getAttribute('data-section'),
    };

    let profileData = {};
    try {
        profileData = studentRow.dataset.profile ? JSON.parse(studentRow.dataset.profile) : {};
    } catch (error) {
        console.error('Error parsing profile data', error);
        profileData = {};
    }

    const fallbackProfile = buildProfileObject(profileData, fallbackCourse);

    showProfileLoading();
    document.getElementById('profileModal').classList.remove('hidden');

    if (fallbackProfile) {
        renderProfile(fallbackProfile);
    }

    fetch(routes.studentProfile.replace('__STUDENT__', studentId))
        .then(response => {
            if (!response.ok) {
                throw new Error(`Request failed with status ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data && data.student) {
                const profileFromServer = buildProfileObject(data.student, fallbackCourse);
                renderProfile(profileFromServer || fallbackProfile);
            }
        })
        .catch(error => {
            console.error('Error loading student profile:', error);
            if (!fallbackProfile) {
                showProfileLoading('Unable to load profile details.');
            }
        });
}

function closeProfileModal() {
    document.getElementById('profileModal').classList.add('hidden');
}
        function dropStudent(studentId, courseId, studentName) {
            if (confirm(`Are you sure you want to drop ${studentName} from this course?`)) {
                fetch(routes.dropStudent.replace('__COURSE__', courseId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        student_id: studentId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }


        // Search functionality
        document.getElementById('searchStudents').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            
            // Search through all student rows
            document.querySelectorAll('.student-row').forEach(row => {
                const studentName = (row.getAttribute('data-name') || '').toLowerCase();
                const studentNumber = (row.getAttribute('data-student-number') || '').toLowerCase();
                const studentEmail = (row.getAttribute('data-email') || '').toLowerCase();
                const courseTitle = (row.getAttribute('data-course') || '').toLowerCase();
                const sectionName = (row.getAttribute('data-section') || '').toLowerCase();
                
                if (studentName.includes(searchTerm) || 
                    studentNumber.includes(searchTerm) || 
                    studentEmail.includes(searchTerm) || 
                    courseTitle.includes(searchTerm) ||
                    sectionName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Hide/show section containers based on whether they have visible students
            document.querySelectorAll('.section-container').forEach(section => {
                const visibleRows = section.querySelectorAll('.student-row:not([style*="display: none"])');
                if (visibleRows.length === 0) {
                    section.style.display = 'none';
                } else {
                    section.style.display = 'block';
                }
            });
        });

        // Section filter functionality
        document.getElementById('sectionFilter').addEventListener('change', function(e) {
            const selectedSection = e.target.value;
            
            document.querySelectorAll('.section-container').forEach(section => {
                const sectionName = section.getAttribute('data-section');
                
                if (selectedSection === '' || sectionName === selectedSection) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
            
            // Re-apply search filter after section filter
            const searchTerm = document.getElementById('searchStudents').value.toLowerCase();
            if (searchTerm) {
                document.querySelectorAll('.student-row').forEach(row => {
                    const studentName = row.getAttribute('data-name') || '';
                    const studentNumber = row.getAttribute('data-student-number') || '';
                    const studentEmail = row.getAttribute('data-email') || '';
                    const courseTitle = row.getAttribute('data-course') || '';
                    const sectionName = row.getAttribute('data-section') || '';
                    
                    if (studentName.includes(searchTerm) || 
                        studentNumber.includes(searchTerm) || 
                        studentEmail.includes(searchTerm) || 
                        courseTitle.includes(searchTerm) ||
                        sectionName.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Hide/show section containers based on visible students
                document.querySelectorAll('.section-container').forEach(section => {
                    const visibleRows = section.querySelectorAll('.student-row:not([style*="display: none"])');
                    if (visibleRows.length === 0) {
                        section.style.display = 'none';
                    } else {
                        section.style.display = 'block';
                    }
                });
            }
        });

        // Function to check if date has changed and update calendar
        function checkDateChange() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const todayStr = `${year}-${month}-${day}`;
            
            // If selected date is not today, update it
            if (selectedDate !== todayStr) {
                console.log('Date changed, updating selected date from', selectedDate, 'to', todayStr);
                selectedDate = todayStr;
                initCalendar();
                loadAttendanceForDate(selectedDate);
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initCalendar();
            loadAttendanceForDate(selectedDate);
            
            // Check for date changes every minute
            setInterval(checkDateChange, 60000);
        });
    </script>

    <style>
        .banner-header {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 120px;
            transition: all 0.3s ease;
        }
        
        .banner-header:hover {
            transform: scale(1.02);
        }
        
        .banner-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.6) 100%);
            z-index: 1;
        }
        
        .banner-content {
            position: relative;
            z-index: 2;
        }
        
    </style>
</x-teacher-layout>
