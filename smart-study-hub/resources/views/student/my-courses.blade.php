<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Enrolled Courses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if($enrollments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrollments as $enrollment)
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 hover:shadow-xl transition-shadow duration-200">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $enrollment->course->title }}</h3>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    by {{ $enrollment->course->teacher->name }}
                                </span>
                            </div>
                            
                            <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                                {{ Str::limit($enrollment->course->description, 120) }}
                            </p>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Enrolled:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $enrollment->enrolled_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Students:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $enrollment->course->enrollments()->count() }}</span>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out flex-1">
                                    View Course
                                </button>
                                <form method="POST" action="{{ route('unenroll', $enrollment->course->id) }}" class="flex-1">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out"
                                            onclick="return confirm('Are you sure you want to unenroll from this course?')">
                                        Unenroll
                                    </button>
                                </form>
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
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">You are not enrolled in any courses yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Browse available courses and enroll in the ones that interest you.</p>
                    <a href="{{ route('student.courses') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                        Browse Courses
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
