<x-student-layout>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Materials</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ $course->title }}@if($week && $week->subTerm && $week->subTerm->term) - {{ $week->subTerm->term->name }} - {{ $week->subTerm->title }} - {{ $week->title }}@endif
            </p>
        </div>
        <a href="{{ route('student.modules.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Modules
        </a>
    </div>

    @if($week->materials->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($week->materials->sortByDesc('created_at') as $material)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-4">
                            @if($material->type === 'video')
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                            @elseif($material->type === 'file')
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            @elseif($material->type === 'link')
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </div>
                                </div>
                            @else
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $material->title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ $material->type }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $material->created_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        @if($material->description)
                            <div class="text-gray-700 dark:text-gray-300 text-sm mb-4 prose prose-sm max-w-none">
                                {!! $material->description !!}
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                @if($material->type === 'file')
                                    {{ $material->file_size_formatted }}
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                @if($material->type === 'video')
                                    <a href="{{ route('student.materials.show', $material) }}" 
                                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                        <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                        View Material
                                    </a>
                                @elseif($material->type === 'file')
                                    <a href="{{ route('student.materials.show', $material) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Material
                                    </a>
                                @elseif($material->type === 'link')
                                    <a href="{{ $material->content }}" target="_blank" 
                                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Open Link
                                    </a>
                                @else
                                    <a href="{{ route('student.materials.show', $material) }}" 
                                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Material
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Materials Available</h3>
            <p class="text-gray-600 dark:text-gray-400">No materials have been uploaded for this week yet.</p>
        </div>
    @endif

</x-student-layout>


