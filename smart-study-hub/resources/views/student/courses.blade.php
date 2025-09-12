<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Available Courses') }}
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

            @if($courses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-200">
                            <!-- Course Image -->
                            <div class="relative h-48 w-full">
                                <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/default-course.png') }}" 
                                     alt="{{ $course->title }}" 
                                     class="h-full w-full object-cover">
                                <div class="absolute top-4 right-4">
                                    <span class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-full text-xs font-medium shadow-sm">
                                        {{ $course->enrollments()->count() }} students
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Course Content -->
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white line-clamp-1">{{ $course->title }}</h3>
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3 text-sm">
                                    {{ Str::limit($course->description, 100) }}
                                </p>
                                
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span>by {{ $course->teacher->name }}</span>
                                    </div>
                                </div>
                                
                                <!-- Enroll/Unenroll Button -->
                                <div class="w-full">
                                    @if($enrolledCourses->contains($course->id))
                                        <form method="POST" action="{{ route('unenroll', $course->id) }}">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition duration-150 ease-in-out"
                                                    onclick="return confirm('Are you sure you want to unenroll from this course?')">
                                                Unenroll
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('enroll', $course->id) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition duration-150 ease-in-out">
                                                Enroll Now
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
</x-app-layout>
