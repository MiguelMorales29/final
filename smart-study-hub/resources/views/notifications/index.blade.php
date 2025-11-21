<x-student-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h2>
                        <form method="POST" action="{{ route('notifications.read-all') }}" class="inline">
                            @csrf
                        <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Mark all as read</button>
                        </form>
                </div>
                
                @if(($notifications ?? collect())->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($notifications as $notification)
                            @php
                                $data = is_string($notification->data) ? json_decode($notification->data, true) : ($notification->data ?? []);
                                $clickUrl = null;
                                if (in_array($notification->type, ['material_video','material_file','material_link','material_text']) && !empty($data['material_id'] ?? null)) {
                                    $clickUrl = route('student.materials.show', $data['material_id']);
                                } elseif (in_array($notification->type, ['assignment','assignment_graded','assignment_regraded']) && !empty($data['assignment_id'] ?? null)) {
                                    $clickUrl = route('student.assignments.show', $data['assignment_id']);
                                } elseif (in_array($notification->type, ['quiz','exam','announcement']) && !empty($data['announcement_id'] ?? null)) {
                                    $clickUrl = route('student.announcements.show', $data['announcement_id']);
                                }
                                $fileExt = $data['file_extension'] ?? null;
                                $fileBg = 'bg-blue-100 dark:bg-blue-900/30';
                                $fileText = 'text-blue-600 dark:text-blue-400';
                                if ($fileExt === 'pdf') { $fileBg='bg-red-100 dark:bg-red-900/30'; $fileText='text-red-600 dark:text-red-400'; }
                                if (in_array($fileExt, ['ppt','pptx'])) { $fileBg='bg-orange-100 dark:bg-orange-900/30'; $fileText='text-orange-600 dark:text-orange-400'; }
                            @endphp
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200 {{ !$notification->read ? 'bg-blue-50 dark:bg-blue-900/20' : '' }} {{ $clickUrl ? 'cursor-pointer' : '' }}" @if($clickUrl) onclick="window.location.href='{{ $clickUrl }}'" @endif>
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        @if($notification->type === 'course_approved')
                                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        @elseif($notification->type === 'course_rejected')
                                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </div>
                                        @elseif($notification->type === 'material_video')
                                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        @elseif($notification->type === 'material_file')
                                            <div class="w-10 h-10 {{ $fileBg }} rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 {{ $fileText }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                        @elseif($notification->type === 'material_link')
                                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </div>
                                        @elseif($notification->type === 'material_text')
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                        @elseif($notification->type === 'assignment')
                                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                            </div>
                                        @elseif(in_array($notification->type, ['quiz','exam','announcement']))
                                            <div class="w-10 h-10 {{ $notification->type === 'quiz' ? 'bg-cyan-100 dark:bg-cyan-900/30' : ($notification->type === 'exam' ? 'bg-red-100 dark:bg-red-900/30' : 'bg-blue-100 dark:bg-blue-900/30') }} rounded-full flex items-center justify-center">
                                                @if($notification->type === 'quiz')
                                                    <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                                @elseif($notification->type === 'exam')
                                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7m0 0v-7m0 7H9m3 0h3"/></svg>
                                                @else
                                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                @endif
                                            </div>
                                        @elseif($notification->type === 'assignment_graded')
                                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m2-4h-3.586a1 1 0 01-.707-.293l-1.414-1.414a1 1 0 00-.707-.293h-2a1 1 0 00-.707.293L7.707 5.293A1 1 0 017 5H4a2 2 0 00-2 2v12a2 2 0 002 2h13a2 2 0 002-2V7a2 2 0 00-2-2z"/></svg>
                                            </div>
                                        @elseif($notification->type === 'assignment_regraded')
                                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582a9 9 0 0115.356-2.532L22 5m-2 15v-5h-.582a9 9 0 01-15.356 2.532L2 19"/></svg>
                                            </div>
                                        @elseif($notification->type === 'attendance_roll_call')
                                            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            </div>
                                        @elseif($notification->type === 'attendance_update')
                                            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a3 3 0 015.356-1.857M12 11c1.657 0 3-1.567 3-3.5S13.657 4 12 4 9 5.567 9 7.5 10.343 11 12 11z"/></svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $notification->title }}</h3>
                                            <div class="flex items-center space-x-2">
                                                @if(!$notification->read)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">New</span>
                                                @endif
                                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $notification->message }}</p>
                                        @if(!empty($data))
                                            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Additional Information:</p>
                                                @if(isset($data['course_title']))
                                                    <div class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Course:</span> {{ $data['course_title'] }}</div>
                                                @endif
                                                @if(isset($data['teacher_name']))
                                                    <div class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Teacher:</span> {{ $data['teacher_name'] }}</div>
                                                @endif
                                                @if(isset($data['points_earned']))
                                                    <div class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Grade:</span> {{ $data['points_earned'] }}/{{ $data['max_points'] ?? '-' }}</div>
                                                    @if(array_key_exists('previous_points', $data) && $data['previous_points'] !== null)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">Previous grade: {{ $data['previous_points'] }}/{{ $data['max_points'] ?? '-' }}</div>
                                                    @endif
                                                @endif
                                                @if(isset($data['status_label']))
                                                    <div class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Status:</span> {{ $data['status_label'] }}</div>
                                                @endif
                                                @if(isset($data['date']) && in_array($notification->type, ['attendance_update','attendance_roll_call']))
                                                    <div class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Date:</span> {{ \Carbon\Carbon::parse($data['date'])->format('M d, Y') }}</div>
                                                @endif
                                                @php
                                                    $description = $data['material_description'] ?? ($data['assignment_description'] ?? ($data['announcement_content'] ?? null));
                                                @endphp
                                                @if($description)
                                                    <div class="text-sm text-gray-700 dark:text-gray-300 mt-2">
                                                        <span class="font-medium">Description:</span>
                                                        <div class="mt-1 text-gray-600 dark:text-gray-400 prose prose-sm max-w-none">{!! $description !!}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                        @if($notification->read_at)
                                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Read on {{ $notification->read_at->format('M j, Y \\a\\t g:i A') }}</p>
                                        @endif
                                    </div>
                                    @if(!$notification->read)
                                        <div class="flex-shrink-0">
                                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Mark as read</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No new updates yet</h3>
                        <p class="text-gray-600 dark:text-gray-400">You're all caught up! No unread notifications.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-student-layout>
