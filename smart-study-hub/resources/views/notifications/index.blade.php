<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">All Notifications</h2>
                        <form method="POST" action="{{ route('notifications.read-all') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                Mark all as read
                            </button>
                        </form>
                    </div>
                </div>
                
                @if($notifications->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($notifications as $notification)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200 {{ !$notification->read ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                <div class="flex items-start space-x-4">
                                    <!-- Notification Icon -->
                                    <div class="flex-shrink-0">
                                        @if($notification->type === 'course_approved')
                                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'course_rejected')
                                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'course_application')
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'assignment')
                                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'graded')
                                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'material_video')
                                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'material_file')
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'material_link')
                                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'material_text')
                                            <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 直奔.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Notification Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $notification->title }}</h3>
                                            <div class="flex items-center space-x-2">
                                                @if(!$notification->read)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                                        New
                                                    </span>
                                                @endif
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $notification->message }}</p>
                                        
                                        @if($notification->data)
                                            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Additional Information:</p>
                                                @if(isset($notification->data['course_id']))
                                                    <div class="text-sm text-gray-700 dark:text-gray-300">
                                                        <span class="font-medium">Course ID:</span> {{ $notification->data['course_id'] }}
                                                    </div>
                                                @endif
                                                @if(isset($notification->data['course_title']))
                                                    <div class="text-sm text-gray-700 dark:text-gray-300">
                                                        <span class="font-medium">Course:</span> {{ $notification->data['course_title'] }}
                                                    </div>
                                                @endif
                                                @if(isset($notification->data['student_name']))
                                                    <div class="text-sm text-gray-700 dark:text-gray-300">
                                                        <span class="font-medium">Student:</span> {{ $notification->data['student_name'] }}
                                                    </div>
                                                @endif
                                                @if(isset($notification->data['message']))
                                                    <div class="text-sm text-gray-700 dark:text-gray-300">
                                                        <span class="font-medium">Message:</span> {{ $notification->data['message'] }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @if($notification->read_at)
                                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                Read on {{ $notification->read_at->format('M j, Y \a\t g:i A') }}
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <!-- Mark as Read Button -->
                                    @if(!$notification->read)
                                        <div class="flex-shrink-0">
                                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    Mark as read
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No notifications yet</h3>
                        <p class="text-gray-600 dark:text-gray-400">You'll see notifications about course applications, approvals, and other updates here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
