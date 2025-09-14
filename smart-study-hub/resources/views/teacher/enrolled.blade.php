<x-teacher-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Top Bar -->
        <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('teacher.courses.index') }}" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $course->title }}</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Section: {{ $course->section ?? 'Default' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Search Bar -->
                        <div class="relative">
                            <input type="text" id="searchStudents" placeholder="Search by name or student number..." 
                                   class="w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Calendar Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Attendance Calendar</h3>
                    <div id="attendanceCalendar" class="grid grid-cols-7 gap-1 text-center">
                        <!-- Calendar will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Enrolled Students ({{ $enrolledStudents->count() }})</h3>
                </div>

                @if($enrolledStudents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Section</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Attendance Today</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($enrolledStudents as $enrolled)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 student-row" data-section="{{ $enrolled['section'] }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($enrolled['student']->profile_picture)
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $enrolled['student']->profile_picture) }}" alt="{{ $enrolled['student']->name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-white">{{ substr($enrolled['student']->name, 0, 1) }}</span>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $enrolled['section'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <button onclick="markAttendance({{ $enrolled['student']->id }}, 'present')" 
                                                        class="attendance-btn present-btn p-1 rounded-full hover:bg-green-100 dark:hover:bg-green-900/30 {{ $enrolled['attendance_today'] === 'present' ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'text-gray-400 dark:text-gray-500' }}">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                                <button onclick="markAttendance({{ $enrolled['student']->id }}, 'absent')" 
                                                        class="attendance-btn absent-btn p-1 rounded-full hover:bg-red-100 dark:hover:bg-red-900/30 {{ $enrolled['attendance_today'] === 'absent' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : 'text-gray-400 dark:text-gray-500' }}">
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
                                                <button onclick="dropStudent({{ $enrolled['student']->id }}, '{{ $enrolled['student']->name }}')" 
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
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No students enrolled yet</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Students will appear here once they are approved for this course.</p>
                    </div>
                @endif
            </div>
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
        let selectedDate = new Date().toISOString().split('T')[0];
        let courseId = {{ $course->id }};

        // Initialize calendar
        function initCalendar() {
            const calendar = document.getElementById('attendanceCalendar');
            const today = new Date();
            const currentMonth = today.getMonth();
            const currentYear = today.getFullYear();
            
            // Get first day of month and number of days
            const firstDay = new Date(currentYear, currentMonth, 1);
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDayOfWeek = firstDay.getDay();
            
            // Calendar header
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];
            
            calendar.innerHTML = `
                <div class="col-span-7 text-center font-medium text-gray-900 dark:text-white mb-2">
                    ${monthNames[currentMonth]} ${currentYear}
                </div>
                <div class="col-span-7 grid grid-cols-7 gap-1 mb-2">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Sun</div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Mon</div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Tue</div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Wed</div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Thu</div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Fri</div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Sat</div>
                </div>
            `;
            
            // Add empty cells for days before month starts
            for (let i = 0; i < startingDayOfWeek; i++) {
                calendar.innerHTML += '<div class="h-8"></div>';
            }
            
            // Add days of month
            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isToday = dateStr === selectedDate;
                const isSelected = dateStr === selectedDate;
                
                calendar.innerHTML += `
                    <button onclick="selectDate('${dateStr}')" 
                            class="h-8 w-8 rounded-full text-sm hover:bg-indigo-100 dark:hover:bg-indigo-900/30 
                                   ${isToday ? 'bg-indigo-600 text-white' : ''} 
                                   ${isSelected && !isToday ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300'}">
                        ${day}
                    </button>
                `;
            }
        }

        function selectDate(date) {
            selectedDate = date;
            initCalendar();
            loadAttendanceForDate(date);
        }

        function loadAttendanceForDate(date) {
            fetch(`/courses/${courseId}/attendance/date?date=${date}`)
                .then(response => response.json())
                .then(data => {
                    // Update attendance buttons based on data
                    updateAttendanceButtons(data);
                })
                .catch(error => console.error('Error:', error));
        }

        function updateAttendanceButtons(attendanceData) {
            // Reset all buttons
            document.querySelectorAll('.attendance-btn').forEach(btn => {
                btn.classList.remove('bg-green-100', 'dark:bg-green-900/30', 'text-green-600', 'dark:text-green-400');
                btn.classList.remove('bg-red-100', 'dark:bg-red-900/30', 'text-red-600', 'dark:text-red-400');
                btn.classList.add('text-gray-400', 'dark:text-gray-500');
            });

            // Update buttons based on attendance data
            attendanceData.forEach(attendance => {
                const presentBtn = document.querySelector(`[onclick*="markAttendance(${attendance.student_id}, 'present')"]`);
                const absentBtn = document.querySelector(`[onclick*="markAttendance(${attendance.student_id}, 'absent')"]`);
                
                if (attendance.status === 'present' && presentBtn) {
                    presentBtn.classList.remove('text-gray-400', 'dark:text-gray-500');
                    presentBtn.classList.add('bg-green-100', 'dark:bg-green-900/30', 'text-green-600', 'dark:text-green-400');
                } else if (attendance.status === 'absent' && absentBtn) {
                    absentBtn.classList.remove('text-gray-400', 'dark:text-gray-500');
                    absentBtn.classList.add('bg-red-100', 'dark:bg-red-900/30', 'text-red-600', 'dark:text-red-400');
                }
            });
        }

        function markAttendance(studentId, status) {
            fetch(`/courses/${courseId}/attendance`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    student_id: studentId,
                    date: selectedDate,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    updateAttendanceButtons([data.attendance]);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function viewProfile(studentId) {
            fetch(`/students/${studentId}/profile`)
                .then(response => response.json())
                .then(data => {
                    const student = data.student;
                    
                    document.getElementById('profileContent').innerHTML = `
                        <div class="text-center mb-4">
                            ${student.profile_picture ? 
                                `<img class="h-20 w-20 rounded-full mx-auto object-cover" src="/storage/${student.profile_picture}" alt="${student.name}">` :
                                `<div class="h-20 w-20 rounded-full bg-indigo-500 flex items-center justify-center mx-auto">
                                    <span class="text-2xl font-medium text-white">${student.name.charAt(0)}</span>
                                </div>`
                            }
                            <h4 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">${student.name}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">${student.email}</p>
                            ${student.student_number ? `<p class="text-sm text-blue-600 dark:text-blue-400 font-mono font-semibold">Student #: ${student.student_number}</p>` : ''}
                        </div>
                        <div class="space-y-3">
                            <div><span class="font-medium text-gray-700 dark:text-gray-300">Bio:</span> <span class="text-gray-600 dark:text-gray-400">${student.bio || 'Not provided'}</span></div>
                            <div><span class="font-medium text-gray-700 dark:text-gray-300">Gender:</span> <span class="text-gray-600 dark:text-gray-400">${student.gender || 'Not provided'}</span></div>
                            <div><span class="font-medium text-gray-700 dark:text-gray-300">Birthdate:</span> <span class="text-gray-600 dark:text-gray-400">${student.birth_date || 'Not provided'}</span></div>
                        </div>
                    `;
                    
                    document.getElementById('profileModal').classList.remove('hidden');
                })
                .catch(error => console.error('Error:', error));
        }

        function closeProfileModal() {
            document.getElementById('profileModal').classList.add('hidden');
        }

        function dropStudent(studentId, studentName) {
            if (confirm(`Are you sure you want to drop ${studentName} from this course?`)) {
                fetch(`/courses/${courseId}/drop-student`, {
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
            document.querySelectorAll('.student-row').forEach(row => {
                const studentName = row.querySelector('td:first-child').textContent.toLowerCase();
                const studentNumber = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const studentEmail = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (studentName.includes(searchTerm) || studentNumber.includes(searchTerm) || studentEmail.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });


        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initCalendar();
            loadAttendanceForDate(selectedDate);
        });
    </script>
</x-teacher-layout>
