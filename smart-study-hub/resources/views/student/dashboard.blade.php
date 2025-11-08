<x-student-layout>
                {{-- Welcome Section --}}
                <div class="mt-6 px-5">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Good morning, {{ $user->name ?? 'Student' }}! 👋</h1>
                    <p class="text-gray-600 dark:text-gray-300">Ready to continue your learning journey? You have {{ $pendingTasks }} pending tasks.</p>
                </div>

                {{-- ===== Styles (optional, for nicer thin scrollbars) ===== --}}
                <style>
                  .thin-scrollbar::-webkit-scrollbar{width:8px;height:8px}
                  .thin-scrollbar::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:8px}
                  .thin-scrollbar::-webkit-scrollbar-track{background:transparent}
                  .dark .thin-scrollbar::-webkit-scrollbar-thumb{background:#4b5563}
                </style>

                {{-- ===== 3-Widget Row ===== --}}
                <section class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
                  {{-- -------------------- Recent Activity -------------------- --}}
                  @php
                    // $activityStream is provided by the controller with both real notifications and demo activities
                    
                    // Icon and color mapping per Figma spec
                    $activityConfig = [
                      'graded' => [
                        'icon' => 'check-circle',
                        'iconColor' => 'text-green-600 dark:text-green-400',
                        'dotColor' => 'bg-green-500',
                        'ariaLabel' => 'Assignment graded'
                      ],
                      'assignment_graded' => [
                        'icon' => 'clipboard-check',
                        'iconColor' => 'text-emerald-600 dark:text-emerald-400',
                        'dotColor' => 'bg-emerald-500',
                        'ariaLabel' => 'Assignment graded'
                      ],
                      'assignment_regraded' => [
                        'icon' => 'arrow-path',
                        'iconColor' => 'text-purple-600 dark:text-purple-400',
                        'dotColor' => 'bg-purple-500',
                        'ariaLabel' => 'Assignment regraded'
                      ],
                      'quiz' => [
                        'icon' => 'quiz',
                        'iconColor' => 'text-cyan-600 dark:text-cyan-400',
                        'dotColor' => 'bg-cyan-500',
                        'ariaLabel' => 'Quiz announcement'
                      ],
                      'exam' => [
                        'icon' => 'exam',
                        'iconColor' => 'text-red-600 dark:text-red-400',
                        'dotColor' => 'bg-red-500',
                        'ariaLabel' => 'Exam announcement'
                      ],
                      'announcement' => [
                        'icon' => 'calendar',
                        'iconColor' => 'text-blue-600 dark:text-blue-400',
                        'dotColor' => 'bg-blue-500',
                        'ariaLabel' => 'Announcement'
                      ],
                      // Material types
                      'material_video' => [
                        'icon' => 'play',
                        'iconColor' => 'text-red-600 dark:text-red-400',
                        'dotColor' => 'bg-red-500',
                        'ariaLabel' => 'New video material'
                      ],
                      'material_file' => [
                        'icon' => 'document-text',
                        'iconColor' => 'text-blue-600 dark:text-blue-400',
                        'dotColor' => 'bg-blue-500',
                        'ariaLabel' => 'New file material'
                      ],
                      'material_link' => [
                        'icon' => 'external-link',
                        'iconColor' => 'text-green-600 dark:text-green-400',
                        'dotColor' => 'bg-green-500',
                        'ariaLabel' => 'New link material'
                      ],
                      'material_text' => [
                        'icon' => 'document-text',
                        'iconColor' => 'text-blue-600 dark:text-blue-400',
                        'dotColor' => 'bg-blue-500',
                        'ariaLabel' => 'New text material'
                      ],
                      'assignment' => [
                        'icon' => 'clipboard-document-list',
                        'iconColor' => 'text-purple-600 dark:text-purple-400',
                        'dotColor' => 'bg-purple-500',
                        'ariaLabel' => 'New assignment'
                      ],
                      'milestone' => [
                        'icon' => 'trophy',
                        'iconColor' => 'text-amber-600 dark:text-amber-400',
                        'dotColor' => 'bg-amber-500',
                        'ariaLabel' => 'Course milestone'
                      ],
                      'deadline' => [
                        'icon' => 'exclamation-circle',
                        'iconColor' => 'text-red-600 dark:text-red-400',
                        'dotColor' => 'bg-red-500',
                        'ariaLabel' => 'Deadline reminder'
                      ],
                      'course_approved' => [
                        'icon' => 'check-circle',
                        'iconColor' => 'text-green-600 dark:text-green-400',
                        'dotColor' => 'bg-green-500',
                        'ariaLabel' => 'Course application approved'
                      ],
                      'course_rejected' => [
                        'icon' => 'exclamation-circle',
                        'iconColor' => 'text-red-600 dark:text-red-400',
                        'dotColor' => 'bg-red-500',
                        'ariaLabel' => 'Course application rejected'
                      ],
                      'course_application' => [
                        'icon' => 'document-text',
                        'iconColor' => 'text-blue-600 dark:text-blue-400',
                        'dotColor' => 'bg-blue-500',
                        'ariaLabel' => 'Course application submitted'
                      ],
                      'course_dropped' => [
                        'icon' => 'trash',
                        'iconColor' => 'text-orange-600 dark:text-orange-400',
                        'dotColor' => 'bg-orange-500',
                        'ariaLabel' => 'Removed from course'
                      ],
                      'attendance_roll_call' => [
                        'icon' => 'user-group',
                        'iconColor' => 'text-indigo-600 dark:text-indigo-400',
                        'dotColor' => 'bg-indigo-500',
                        'ariaLabel' => 'Attendance roll call'
                      ],
                      'attendance_update' => [
                        'icon' => 'user-group',
                        'iconColor' => 'text-amber-600 dark:text-amber-400',
                        'dotColor' => 'bg-amber-500',
                        'ariaLabel' => 'Attendance updated'
                      ]
                    ];
                    
                    // Subject chip styling
                    $subjectChips = [
                      'English' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-200 dark:ring-emerald-900/50',
                      'Mathematics' => 'bg-indigo-50 text-indigo-700 ring-indigo-200 dark:bg-indigo-900/40 dark:text-indigo-200 dark:ring-indigo-900/50',
                      'Science' => 'bg-sky-50 text-sky-700 ring-sky-200 dark:bg-sky-900/40 dark:text-sky-200 dark:ring-sky-900/50',
                      'Programming Fundamentals' => 'bg-violet-50 text-violet-700 ring-violet-200 dark:bg-violet-900/40 dark:text-violet-200 dark:ring-violet-900/50',
                      'History' => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-900/40 dark:text-amber-200 dark:ring-amber-900/50'
                    ];
                  @endphp

                  <div class="h-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm hover:shadow-md transition">
                    {{-- Header --}}
                    <div class="p-5 border-b border-gray-200 dark:border-gray-800">
                      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                      <p class="text-sm text-gray-500 dark:text-gray-400">Latest updates from your courses</p>
                    </div>

                    {{-- Scrollable Body with fade effect --}}
                    <div class="relative flex-1 overflow-hidden">
                      <div class="p-5 overflow-y-auto thin-scrollbar space-y-3" style="max-height:20rem;mask-image:linear-gradient(to_bottom,transparent,black_16px,black_calc(100%-16px),transparent)">
                        @foreach($activityStream as $i => $a)
                          @php $config = $activityConfig[$a['type']] ?? [
                            'icon' => 'information-circle',
                            'iconColor' => 'text-gray-600 dark:text-gray-400',
                            'dotColor' => 'bg-gray-500',
                            'ariaLabel' => 'Activity'
                          ]; @endphp
                          @php
                            // Click URL detection
                            $clickUrl = null;
                            if (in_array($a['type'], ['material_video','material_file','material_link','material_text']) && !empty($a['material_id'] ?? null)) {
                                $clickUrl = route('student.materials.show', $a['material_id']);
                            } elseif (in_array($a['type'], ['assignment','assignment_graded','assignment_regraded']) && !empty($a['notification_data']['assignment_id'] ?? null)) {
                                $clickUrl = route('student.assignments.show', $a['notification_data']['assignment_id']);
                            } elseif (in_array($a['type'], ['quiz','exam','announcement']) && !empty($a['notification_data']['announcement_id'] ?? null)) {
                                // requires student.announcements.show
                                $clickUrl = route('student.announcements.show', $a['notification_data']['announcement_id']);
                            }
                            
                            // Dynamic material file color override
                            if ($a['type'] === 'material_file') {
                                $fileExt = $a['notification_data']['file_extension'] ?? null;
                                if ($fileExt === 'pdf') {
                                  $activityConfig['material_file']['iconColor'] = 'text-red-600 dark:text-red-400';
                                  $activityConfig['material_file']['dotColor'] = 'bg-red-500';
                                } elseif (in_array($fileExt, ['ppt','pptx'])) {
                                  $activityConfig['material_file']['iconColor'] = 'text-orange-600 dark:text-orange-400';
                                  $activityConfig['material_file']['dotColor'] = 'bg-orange-500';
                                } else {
                                  $activityConfig['material_file']['iconColor'] = 'text-blue-600 dark:text-blue-400';
                                  $activityConfig['material_file']['dotColor'] = 'bg-blue-500';
                                }
                            } elseif ($a['type'] === 'material_text') {
                                $activityConfig['material_text']['iconColor'] = 'text-blue-600 dark:text-blue-400';
                                $activityConfig['material_text']['dotColor'] = 'bg-blue-500';
                            }
                            $config = $activityConfig[$a['type']] ?? [
                              'icon' => 'information-circle',
                              'iconColor' => 'text-gray-600 dark:text-gray-400',
                              'dotColor' => 'bg-gray-500',
                              'ariaLabel' => 'Activity'
                            ];
                          @endphp
                          @php
                            $accent = [
                              'English' => 'border-l-4 border-emerald-500',
                              'Mathematics' => 'border-l-4 border-indigo-500',
                              'Science' => 'border-l-4 border-sky-500',
                              'Programming Fundamentals' => 'border-l-4 border-violet-500',
                              'History' => 'border-l-4 border-amber-500',
                            ][$a['subject']] ?? 'border-l-4 border-gray-300 dark:border-gray-700';
                          @endphp
                          <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-4 hover:bg-gray-50 dark:hover:bg-gray-800/60 transition {{ $i===0 ? 'bg-gray-50 dark:bg-gray-800/40' : '' }} {{ $clickUrl ? 'cursor-pointer' : '' }} {{ $accent }}" @if($clickUrl) onclick="window.location.href='{{ $clickUrl }}'" @endif>
                            <div class="flex items-start gap-3">
                              {{-- Heroicon (16px) --}}
                              <div class="mt-0.5">
                                @if($config['icon'] === 'check-circle')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                  </svg>
                                @elseif($config['icon'] === 'clipboard-check')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m2-4h-3.586a1 1 0 01-.707-.293l-1.414-1.414a1 1 0 00-.707-.293h-2a1 1 0 00-.707.293L7.707 5.293A1 1 0 017 5H4a2 2 0 00-2 2v12a2 2 0 002 2h13a2 2 0 002-2V7a2 2 0 00-2-2z"/>
                                  </svg>
                                @elseif($config['icon'] === 'arrow-path')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582a9 9 0 0115.356-2.532L22 5m-2 15v-5h-.582a9 9 0 01-15.356 2.532L2 19"/>
                                  </svg>
                                @elseif($config['icon'] === 'document-text')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                  </svg>
                                @elseif($config['icon'] === 'play')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M8 5v14l11-7z"/>
                                  </svg>
                                @elseif($config['icon'] === 'external-link')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                  </svg>
                                @elseif($config['icon'] === 'clipboard-document-list')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                  </svg>
                                @elseif($config['icon'] === 'trophy')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                  </svg>
                                @elseif($config['icon'] === 'exclamation-circle')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                  </svg>
                                @elseif($config['icon'] === 'trash')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                  </svg>
                                @elseif($config['icon'] === 'user-group')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                  </svg>
                                @elseif($config['icon'] === 'information-circle')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                  </svg>
                                @elseif($config['icon'] === 'quiz')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                  </svg>
                                @elseif($config['icon'] === 'exam')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7m0 0v-7m0 7H9m3 0h3"></path>
                                  </svg>
                                @elseif($config['icon'] === 'calendar')
                                  <svg class="h-4 w-4 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                  </svg>
                                @endif
                              </div>

                              <div class="flex-1">
                                {{-- First line: bold title + optional "New" badge --}}
                                <div class="flex items-center gap-2">
                                  <span class="font-semibold text-gray-900 dark:text-white">{{ $a['title'] }}</span>
                                  @if(!empty($a['new']))
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium bg-blue-600 dark:bg-blue-500 text-white">
                                      New
                                    </span>
                                  @endif
                                </div>
                                
                                {{-- Second line: supporting text (14px, muted gray) --}}
                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 leading-5">{{ $a['text'] }}</p>

                                {{-- Meta row: subject chip + timestamp --}}
                                <div class="mt-2 flex flex-wrap items-center gap-3 text-xs">
                                  <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs ring-1 ring-inset {{ $subjectChips[$a['subject']] ?? 'bg-gray-50 text-gray-700 ring-gray-200 dark:bg-gray-900/30 dark:text-gray-300 dark:ring-gray-900/40' }}">
                                    {{ $a['subject'] }}
                                  </span>
                                  <span class="text-gray-500 dark:text-gray-400">{{ $a['time'] }}</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>

                    {{-- Footer with inset divider --}}
                    <div class="border-t border-gray-200 dark:border-gray-800 px-5 py-3">
                      <a href="#" class="text-indigo-600 dark:text-indigo-400 text-sm inline-flex items-center gap-1 hover:underline">
                        View all activity
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                      </a>
                    </div>
                  </div>

                  {{-- -------------------- Upcoming Tasks -------------------- --}}
                  @php
                    // Use controller-provided upcoming tasks
                    $priorityStyle = [
                      'high'   => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-200',
                      'medium' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-200',
                      'low'    => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                    ];
                  @endphp

                  <div class="h-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm hover:shadow-md transition">
                    {{-- Header --}}
                    <div class="p-5 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                      <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upcoming Tasks</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Your pending assignments</p>
                      </div>
                      <span class="text-xs px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        {{ (is_countable($upcomingTasks ?? []) ? count($upcomingTasks) : 0) }} pending
                      </span>
                    </div>

                    {{-- Scrollable Body with fade effect --}}
                    <div class="relative flex-1 overflow-hidden">
                      <div class="p-5 overflow-y-auto thin-scrollbar space-y-3" style="max-height:20rem;mask-image:linear-gradient(to_bottom,transparent,black_16px,black_calc(100%-16px),transparent)">
                        @foreach(($upcomingTasks ?? []) as $t)
                          @php
                            $clockColor = [
                              'high' => 'text-red-500 dark:text-red-400',
                              'medium' => 'text-yellow-500 dark:text-yellow-400', 
                              'low' => 'text-gray-500 dark:text-gray-400'
                            ][$t['priority']] ?? 'text-gray-500 dark:text-gray-400';
                          @endphp
                          @php $assignmentUrl = isset($t['assignment_id']) ? route('student.assignments.show', $t['assignment_id']) : null; @endphp
                          <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-4 hover:bg-gray-50 dark:hover:bg-gray-800/60 transition {{ $assignmentUrl ? 'cursor-pointer' : '' }}" @if($assignmentUrl) onclick="window.location.href='{{ $assignmentUrl }}'" @endif>
                            <div class="flex items-start justify-between gap-3">
                              <div class="flex items-start gap-2">
                                {{-- color-coded clock icon --}}
                                <svg class="h-4 w-4 mt-0.5 {{ $clockColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M12 22a10 10 0 110-20 10 10 0 010 20z"/>
                                </svg>
                                <div>
                                  <div class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    {{ $t['title'] }}
                                    @php $badgeType = $t['type'] ?? (isset($t['assignment_id']) ? 'assignment' : null); @endphp
                                    @if($badgeType)
                                      <span class="inline-flex items-center gap-1 text-[11px] px-2 py-0.5 rounded-full
                                        {{ $badgeType==='assignment' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-200' : ($badgeType==='exam' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200' : ($badgeType==='quiz' ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-200' : 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-200')) }}">
                                        @if($badgeType==='assignment') 📘 @elseif($badgeType==='exam') 🎓 @elseif($badgeType==='quiz') ✅ @else 🗓️ @endif
                                        <span class="capitalize">{{ $badgeType }}</span>
                                      </span>
                                    @endif
                                  </div>
                                  <div class="text-[12px] text-gray-500 dark:text-gray-400">{{ $t['course'] }} • Due: {{ $t['due'] }}</div>
                                </div>
                              </div>
                              <span class="text-[11px] px-2 py-0.5 rounded-full {{ $priorityStyle[$t['priority']] }}">{{ $t['priority'] }}</span>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>

                    {{-- Footer with inset divider --}}
                    <div class="border-t border-gray-200 dark:border-gray-800 px-5 py-3">
                      <a href="#" class="text-indigo-600 dark:text-indigo-400 text-sm inline-flex items-center gap-1 hover:underline">
                        View all tasks
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                      </a>
                    </div>
                  </div>

                  {{-- -------------------- Calendar -------------------- --}}
                  @php
                    $today = now();
                    $start = $today->copy()->startOfMonth();
                    $daysInMonth = $start->daysInMonth;
                    $startDow = $start->dayOfWeek; // 0=Sun
                    $markDot = ['exam'=>'bg-blue-500','assignment'=>'bg-orange-500','quiz'=>'bg-red-500','custom'=>'bg-purple-500'];
                  @endphp

                  <div class="h-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm hover:shadow-md transition flex flex-col">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-800">
                      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $start->format('F Y') }}</h3>
                      <p class="text-sm text-gray-500 dark:text-gray-400">Your schedule overview</p>
                    </div>

                    <div class="p-5 flex-1">
                      <div class="grid grid-cols-7 text-center text-[11px] text-gray-500 dark:text-gray-400 mb-2">
                        <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                      </div>

                      <div class="grid grid-cols-7 gap-1 text-sm">
                        {{-- leading blanks --}}
                        @for($i=0;$i<$startDow;$i++)
                          <div class="h-10"></div>
                        @endfor

                        {{-- days --}}
                        @for($d=1;$d<=$daysInMonth;$d++)
                          @php
                            $dateKey = $start->copy()->day($d)->format('Y-m-d');
                            $isToday = ($today->format('Y-m-d') === $dateKey);
                            $mark = $calendarMarks[$dateKey] ?? null;
                          @endphp
                          <div class="relative h-10 rounded border border-gray-200 dark:border-gray-800 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800/60 transition group"
                               x-data="{
                                   openState: false,
                                   style: '',
                                   open(el) {
                                       this.openState = true;
                                       this.$nextTick(() => {
                                           try {
                                               const rect = el.getBoundingClientRect();
                                               const tip = this.$refs.tip;
                                               const vw = window.innerWidth;
                                               const vh = window.innerHeight;
                                               const width = 224; // ~w-56
                                               const margin = 8;
                                               let tipH = tip ? Math.min(tip.scrollHeight, 320) : 160;
                                               // Prefer below
                                               let top = rect.bottom + margin;
                                               // If out of bottom, place above
                                               if (top + tipH > vh - margin) top = rect.top - tipH - margin;
                                               // Clamp to viewport
                                               if (top < margin) top = margin;
                                               let left = rect.left + (rect.width / 2) - (width / 2);
                                               if (left < margin) left = margin;
                                               if (left + width > vw - margin) left = vw - width - margin;
                                               this.style = `position: fixed; top: ${top}px; left: ${left}px; width: ${width}px;`;
                                           } catch (e) {}
                                       });
                                   },
                                   close() { this.openState = false; }
                               }"
                               @mouseenter="open($el)" @mouseleave="close()">
                            <span class="text-gray-900 dark:text-gray-100 {{ $isToday ? 'inline-flex items-center justify-center h-7 w-7 rounded-full bg-indigo-600 text-white' : '' }}">
                              {{ $d }}
                            </span>
                            @if($mark)
                              <span class="absolute -bottom-1 h-1.5 w-1.5 rounded-full {{ $markDot[$mark] }}"></span>
                              @php $events = $calendarEventsByDate[$dateKey] ?? []; @endphp
                              @if(!empty($events))
                                <div x-show="openState" x-ref="tip" x-cloak class="fixed z-50 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-3 w-56 text-left" :style="style">
                                  <div class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Events ({{ count($events) }})</div>
                                  <div class="space-y-2 max-h-48 overflow-y-auto">
                                    @foreach($events as $ev)
                                      <a href="{{ $ev['url'] }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded p-2">
                                        <span class="inline-flex items-center gap-2 text-xs">
                                          <span class="h-2 w-2 rounded-full {{ $ev['badge_color']==='blue'?'bg-blue-500':($ev['badge_color']==='orange'?'bg-orange-500':($ev['badge_color']==='red'?'bg-red-500':'bg-purple-500')) }}"></span>
                                          <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $ev['type_display'] }}</span>
                                          </span>
                                        <div class="text-sm text-gray-900 dark:text-gray-100 leading-snug">{{ $ev['title'] }}</div>
                                      </a>
                                    @endforeach
                                  </div>
                                </div>
                              @endif
                            @endif
                          </div>
                        @endfor
                      </div>

                      {{-- Horizontal legend with "Upcoming" text --}}
                      <div class="mt-6">
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Upcoming</div>
                        <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-300">
                          <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-blue-500"></span> Exams</span>
                          <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-orange-500"></span> Assignments</span>
                          <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-red-500"></span> Quizzes</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>

                {{-- My Courses using homepage images --}}
                <section class="mt-10">
                  <div class="flex items-center justify-between px-5">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">My Courses</h2>
                    <a href="{{ url('/my-courses') }}" class="text-sm text-indigo-600 hover:text-blue-800 dark:text-indigo-400 dark:hover:text-indigo-300 hover:underline">View all courses →</a>
                  </div>

                  {{-- Container with overflow cut effect --}}
                  <div class="mt-6 relative">
                    {{-- Main grid with 3x3 layout --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 items-stretch">
                      @php $allCoursesSlice = collect($allCourses ?? [])->take(9)->values()->all(); @endphp
                      @forelse($allCoursesSlice as $index => $c)
                      @if(isset($c['locked']) && $c['locked'])
                        {{-- Pending/Rejected/Locked Course Card --}}
                        <div class="group relative rounded-2xl overflow-hidden bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 ease-out">
                          <div class="relative">
                            <img src="{{ $c['cover'] }}" alt="{{ $c['title'] }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black/60 group-hover:bg-black/50 transition-colors duration-300"></div>
                            <div class="absolute inset-0 flex items-center justify-center z-10">
                              <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm mb-2">
                                  @if(isset($c['status']) && $c['status'] === 'pending')
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                  @elseif(isset($c['status']) && $c['status'] === 'rejected')
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                  @elseif(isset($c['status']) && $c['status'] === 'dropped')
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                  @else
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                  @endif
                                </div>
                                <p class="text-white font-medium text-sm">
                                  @if(isset($c['status']) && $c['status'] === 'pending')
                                    Pending
                                  @elseif(isset($c['status']) && $c['status'] === 'rejected')
                                    Rejected
                                  @elseif(isset($c['status']) && $c['status'] === 'dropped')
                                    Dropped
                                  @else
                                    Locked
                                  @endif
                                </p>
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
                              @if(isset($c['status']) && $c['status'] === 'pending')
                                <div class="h-2 bg-gradient-to-r from-yellow-400 to-amber-500 dark:from-yellow-500 dark:to-amber-600 rounded"></div>
                                <div class="text-xs mt-1 text-gray-500 dark:text-gray-400">Pending • Awaiting Approval</div>
                                <button disabled class="mt-3 w-full bg-gradient-to-r from-yellow-500 to-amber-600 dark:from-yellow-600 dark:to-amber-700 text-white py-2 px-4 rounded-lg font-medium flex items-center justify-center gap-2 opacity-75 cursor-not-allowed">
                                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                  </svg>
                                  Pending Approval
                                </button>
                              @elseif(isset($c['status']) && $c['status'] === 'rejected')
                                <div class="h-2 bg-gradient-to-r from-red-400 to-red-500 dark:from-red-500 dark:to-red-600 rounded"></div>
                                <div class="text-xs mt-1 text-gray-500 dark:text-gray-400">Rejected • Application Denied</div>
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
                              @elseif(isset($c['status']) && $c['status'] === 'dropped')
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
                              @else
                                <div class="h-2 bg-gradient-to-r from-gray-400 to-gray-500 dark:from-gray-500 dark:to-gray-600 rounded"></div>
                                <div class="text-xs mt-1 text-gray-500 dark:text-gray-400">Locked • Coming Soon</div>
                                <button disabled class="mt-3 w-full bg-gradient-to-r from-gray-500 to-gray-600 dark:from-gray-600 dark:to-gray-700 text-white py-2 px-4 rounded-lg font-medium flex items-center justify-center gap-2 opacity-75 cursor-not-allowed">
                                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                  </svg>
                                  Coming Soon
                                </button>
                              @endif
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
                              <div class="h-2 bg-gray-200 dark:bg-gray-800 rounded" data-dashboard-course-id="{{ $c['id'] }}">
                                <div class="h-2 bg-indigo-600 rounded dashboard-course-progress" style="width: 0%"></div>
                              </div>
                              <div class="text-xs mt-1 text-gray-500 dark:text-gray-400 dashboard-progress-text">Loading progress... @if($c['next_due']) • Next due: {{ $c['next_due'] }} @endif</div>
                              <a href="{{ route('student.modules.index') }}" class="mt-3 inline-flex justify-center items-center w-full rounded-lg py-2 font-medium bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors duration-200">
                                Continue Learning
                              </a>
                            </div>
                          </div>
                        </div>
                      @endif
                      @empty
                        <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
                          <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                          </svg>
                          <p class="text-lg font-medium mb-2 text-gray-900 dark:text-white">No courses yet</p>
                          <p class="text-sm text-gray-600 dark:text-gray-300">Start your learning journey by enrolling in courses.</p>
                          <a href="{{ url('/courses') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50 transition-colors duration-200">
                            Browse Courses
                          </a>
                        </div>
                      @endforelse
                    </div>
                    
                    {{-- Cut overflow effect for third row --}}
                    @if(count($allCourses) > 6)
                      <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-white dark:from-gray-900 via-white/80 dark:via-gray-900/80 to-transparent pointer-events-none"></div>
                    @endif
                    
                    {{-- View More button - always show if there are courses --}}
                    @if(count($allCourses) > 0)
                      <div class="mt-8 text-center">
                        <a href="{{ url('/my-courses') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                          </svg>
                          View All My Courses
                          @if(count($allCourses) > 9)
                            <span class="ml-1 px-2 py-0.5 bg-white/20 rounded-full text-xs font-semibold">
                              +{{ count($allCourses) - 9 }}
                            </span>
                          @endif
                        </a>
                      </div>
                    @endif
                  </div>
                </section>

                {{-- Stats Section --}}
                <section class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Courses</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $approvedCoursesCount }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg. Progress</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $avgProgress }}%</p>
                            </div>
                            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Tasks</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingTasks }}</p>
                            </div>
                            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-full">
                                <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">This Week</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">+12%</p>
                            </div>
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                                <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script>
// Load course progress dynamically for dashboard
document.addEventListener("DOMContentLoaded", function() {
    const progressBars = document.querySelectorAll('[data-dashboard-course-id]');
    progressBars.forEach(bar => {
        const courseId = bar.getAttribute('data-dashboard-course-id');
        fetch(`/student/courses/${courseId}/progress`)
            .then(r => r.json())
            .then(d => {
                const fill = bar.querySelector('.dashboard-course-progress');
                const text = bar.nextElementSibling;
                if (fill && text) {
                    fill.style.width = (d.progress_percentage || 0) + '%';
                    // Keep the next due text if it exists
                    const nextDueMatch = text.textContent.match(/• Next due:.*$/);
                    const nextDueText = nextDueMatch ? ' ' + nextDueMatch[0] : '';
                    text.textContent = `${d.progress_percentage || 0}% complete${nextDueText}`;
                }
            })
            .catch(() => {
                const fill = bar.querySelector('.dashboard-course-progress');
                const text = bar.nextElementSibling;
                if (fill && text) {
                    fill.style.width = '0%';
                    text.textContent = '0% complete';
                }
            });
    });
});
</script>

</x-student-layout>