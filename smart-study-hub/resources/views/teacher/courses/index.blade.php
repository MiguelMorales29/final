<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Courses') }}
            </h2>
            <a href="{{ route('teacher.courses.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">
                + Create Course
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
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
                                <div class="absolute bottom-4 left-4">
                                    <span class="bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                                        {{ $course->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Course Content -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-1">{{ $course->title }}</h3>
                                
                                <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3 text-sm">
                                    {{ Str::limit($course->description, 100) }}
                                </p>
                                
                                <div class="flex gap-2 flex-wrap">
                                    <a href="{{ route('teacher.course.students', $course->id) }}" 
                                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out flex-1 text-center">
                                        Students ({{ $course->enrollments()->count() }})
                                    </a>
                                    <a href="{{ route('teacher.courses.edit', $course) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out flex-1 text-center">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('teacher.courses.destroy', $course) }}" 
                                          class="flex-1"
                                          onsubmit="return confirm('Are you sure you want to delete this course?')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                            Delete
                                        </button>
                                    </form>
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
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No courses yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Get started by creating your first course.</p>
                    <a href="{{ route('teacher.courses.create') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                        Create Your First Course
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Floating Create Button for Mobile -->
    <div class="fixed bottom-6 right-6 md:hidden">
        <a href="{{ route('teacher.courses.create') }}" 
           class="bg-green-600 hover:bg-green-700 text-white w-14 h-14 rounded-full shadow-lg flex items-center justify-center transition duration-150 ease-in-out">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </a>
    </div>
</x-app-layout>
