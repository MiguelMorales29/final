<x-teacher-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ route('teacher.upload-materials.select') }}" 
               class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Upload Materials</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $course->title }}</p>
            </div>
        </div>
        <a href="{{ route('teacher.courses.show', $course) }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
            View Course
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Upload Form -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 sticky top-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Upload New Material</h3>
                </div>
                
                <form method="POST" action="{{ route('teacher.materials.store', $course) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-200 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if (session('success'))
                        <div class="bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-200 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="course_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Course
                            </label>
                            <select name="course_id" 
                                    id="course_id"
                                    class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="{{ $course->id }}" selected>{{ $course->title }}</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="term_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Term
                            </label>
                            <select name="term_id" 
                                    id="term_id"
                                    class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="">Choose a term...</option>
                                @foreach($course->terms as $term)
                                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="course_week_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Week
                            </label>
                            <select name="course_week_id" 
                                    id="course_week_id"
                                    class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="">Choose a week...</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Material Title
                            <span class="text-xs text-gray-500 dark:text-gray-400">(max 500 characters)</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   maxlength="500"
                                   class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 pr-16 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                   placeholder="Enter material title"
                                   required>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                <span id="title-counter" class="text-xs text-gray-400 dark:text-gray-500">0/500</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description
                            <span class="text-xs text-gray-500 dark:text-gray-400">(max 5,000 characters)</span>
                        </label>
                        <div class="relative">
                            <!-- Quill Editor Container -->
                            <div id="description-editor" 
                                 class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition duration-150 ease-in-out"
                                 style="height: 200px;">
                            </div>
                            <!-- Hidden input to store HTML content -->
                            <input type="hidden" id="description" name="description">
                            <div class="absolute bottom-2 right-3">
                                <span id="description-counter" class="text-xs text-gray-400 dark:text-gray-500">0/5,000</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Material Type
                        </label>
                        <select name="type" 
                                id="type"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Select type...</option>
                            <option value="video">Video (YouTube/Vimeo)</option>
                            <option value="file">File Upload</option>
                            <option value="link">External Link</option>
                            <option value="text">Text Instructions</option>
                        </select>
                    </div>

                    <!-- Video URL Field -->
                    <div id="video-url-field" class="hidden">
                        <label for="youtube_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Video URL
                        </label>
                        <input type="url" 
                               id="youtube_url" 
                               name="youtube_url" 
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="https://www.youtube.com/watch?v=...">
                    </div>

                    <!-- File Upload Field -->
                    <div id="file-upload-field" class="hidden">
                        <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Upload File
                        </label>
                        <input type="file" 
                               id="file" 
                               name="file" 
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Max file size: 10MB</p>
                    </div>

                    <!-- External URL Field -->
                    <div id="external-url-field" class="hidden">
                        <label for="external_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            External URL
                        </label>
                        <input type="url" 
                               id="external_url" 
                               name="external_url" 
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="https://example.com">
                    </div>

                    <!-- Text Content Field -->
                    <div id="text-content-field" class="hidden">
                        <label for="text_content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Text Instructions
                            <span class="text-xs text-gray-500 dark:text-gray-400">(max 10,000 characters)</span>
                        </label>
                        <div class="relative">
                            <!-- Quill Editor Container for Text Content -->
                            <div id="text-content-editor" 
                                 class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition duration-150 ease-in-out"
                                 style="height: 300px;">
                            </div>
                            <!-- Hidden input to store HTML content -->
                            <input type="hidden" id="text_content" name="text_content">
                            <div class="absolute bottom-2 right-3">
                                <span id="text-content-counter" class="text-xs text-gray-400 dark:text-gray-500">0/10,000</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_required" 
                               name="is_required" 
                               value="1"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_required" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Required material
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 ease-in-out transform hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Upload Material
                    </button>
                </form>
            </div>
        </div>

        <!-- Course Structure -->
        <div class="lg:col-span-2" x-data="collapsibleManager()">
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Course Structure & Materials</h3>
                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        <span>Total Materials: <span class="font-semibold text-gray-900 dark:text-white" x-text="totalItems"></span></span>
                        <span>•</span>
                        <span>Total Weeks: <span class="font-semibold text-gray-900 dark:text-white" x-text="totalWeeks"></span></span>
                    </div>
                </div>
                
                @forelse($course->terms as $index => $term)
                    @php
                        $totalWeeks = $term->subTerms->sum(function($subTerm) {
                            return $subTerm->weeks->count();
                        });
                        $totalMaterials = $term->subTerms->sum(function($subTerm) {
                            return $subTerm->weeks->sum(function($week) {
                                return $week->materials->count();
                            });
                        });
                    @endphp
                    
                    <x-collapsible-section 
                        :id="$term->id"
                        :title="$term->name"
                        :subtitle="$term->description"
                        :count="$totalMaterials"
                        count-label="materials"
                        :badges="[
                            ['class' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200', 'text' => $term->total_weeks . ' planned'],
                            ['class' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200', 'text' => $term->weeks->count() . ' created']
                        ]"
                        level="term"
                        :clickable="true">
                        
                            @if($term->subTerms->count() > 0)
                                <div class="space-y-4">
                                    @foreach($term->subTerms as $subTerm)
                                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                        <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                            <h5 class="text-lg font-medium text-gray-900 dark:text-white">{{ $subTerm->title }}</h5>
                                            @if($subTerm->description)
                                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ $subTerm->description }}</p>
                                            @endif
                                        </div>
                                            
                                            @if($subTerm->weeks->count() > 0)
                                            <div class="divide-y divide-gray-200 dark:divide-gray-600">
                                                    @foreach($subTerm->weeks as $week)
                                                    @php
                                                        $weekMaterialsCount = $week->materials->count();
                                                    @endphp
                                                    <div class="p-4">
                                                        <!-- Week Header (Collapsible) -->
                                                        <div class="flex items-center justify-between cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-2 -m-2 transition-colors duration-200"
                                                             @click="toggleWeek({{ $week->id }})">
                                                            <div class="flex items-center gap-3">
                                                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                                <div>
                                                                <h6 class="font-medium text-gray-900 dark:text-white">{{ $week->title }}</h6>
                                                                    @if($week->description)
                                                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $week->description }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center gap-3">
                                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                                    {{ $weekMaterialsCount }} material{{ $weekMaterialsCount !== 1 ? 's' : '' }}
                                                                </span>
                                                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" 
                                                                     :class="{ 'rotate-180': openWeeks[{{ $week->id }}] }" 
                                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                </svg>
                                                            </div>
                                                        </div>

                                                        <!-- Week Content (Collapsible) -->
                                                        <div x-show="openWeeks[{{ $week->id }}]" 
                                                             x-transition:enter="transition ease-out duration-200"
                                                             x-transition:enter-start="opacity-0 transform scale-95"
                                                             x-transition:enter-end="opacity-100 transform scale-100"
                                                             x-transition:leave="transition ease-in duration-150"
                                                             x-transition:leave-start="opacity-100 transform scale-100"
                                                             x-transition:leave-end="opacity-0 transform scale-95"
                                                             class="mt-4">
                                                            <div class="flex items-center justify-between mb-4">
                                                                <h6 class="text-sm font-medium text-gray-700 dark:text-gray-300">Materials</h6>
                                                                <button type="button"
                                                                   class="add-material-btn inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium"
                                                                   data-term-id="{{ $term->id }}"
                                                                   data-week-id="{{ $week->id }}">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                    </svg>
                                                                    Add Material
                                                                </button>
                                                            </div>
                                                            
                                                            @if($week->materials->count() > 0)
                                                                <div class="space-y-3">
                                                                    @foreach($week->materials as $material)
                                                                        <div class="item-card bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:shadow-md hover:scale-[1.02] transition-all duration-200">
                                                                            <div class="flex items-start justify-between">
                                                                                <div class="flex-1">
                                                                                    <div class="flex items-center gap-2 mb-2">
                                                                                @if($material->type === 'video')
                                                                                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                                                                <path d="M8 5v14l11-7z"/>
                                                                                    </svg>
                                                                                        @elseif($material->type === 'file')
                                                                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                    </svg>
                                                                                        @elseif($material->type === 'link')
                                                                                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                                                    </svg>
                                                                                @else
                                                                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                    </svg>
                                                                                @endif
                                                                                        <span class="font-medium text-gray-900 dark:text-white">{{ $material->title }}</span>
                                                                                        <span class="text-xs text-gray-500 dark:text-gray-400 capitalize bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                                                                            {{ $material->type }}
                                                                                        </span>
                                                                                @if($material->is_required)
                                                                                            <span class="text-xs bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-2 py-1 rounded font-medium">
                                                                                                Required
                                                                                            </span>
                                                                                        @endif
                                                                                    </div>
                                                                                    @if($material->description)
                                                                                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2 prose prose-sm max-w-none">
                                                                                            {!! Str::limit(strip_tags($material->description), 100) !!}
                                                                                        </div>
                                                                                    @endif
                                                                                    <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                                                                        <span>Uploaded {{ $material->created_at->format('M j, Y') }}</span>
                                                                                        @if($material->created_at->diffInDays() < 7)
                                                                                            <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded">
                                                                                                New
                                                                                            </span>
                                                                                @endif
                                                                            </div>
                                                                                </div>
                                                                                <div class="flex items-center gap-2 ml-4">
                                                                                    <button type="button" 
                                                                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-2 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20 transition duration-150 ease-in-out"
                                                                                            onclick="openEditModal({{ $material->id }})"
                                                                                            title="Edit Material">
                                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                                        </svg>
                                                                                    </button>
                                                                            <form method="POST" action="{{ route('teacher.materials.delete', [$course, $material]) }}" class="inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" 
                                                                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 p-2 rounded hover:bg-red-50 dark:hover:bg-red-900/20 transition duration-150 ease-in-out"
                                                                                                onclick="return confirm('Are you sure you want to delete this material?')"
                                                                                                title="Delete Material">
                                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                                    </svg>
                                                                                </button>
                                                                            </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                    <p class="text-sm">No materials uploaded for this week.</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                            <div class="p-4 text-center text-gray-500 dark:text-gray-400 text-sm">
                                                No weeks created for this section.
                                            </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm">No sections created for this term.</p>
                            </div>
                            @endif
                    </x-collapsible-section>
                @empty
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Course Structure</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">This course doesn't have any terms or weeks defined yet.</p>
                        <a href="{{ route('teacher.courses.edit', $course) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                            Edit Course Structure
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Include collapsible script -->
    <x-collapsible-script />

    <script>
        // Course structure data - make it globally available
        const courseStructure = @json($courseStructure);
        
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const videoUrlField = document.getElementById('video-url-field');
            const fileUploadField = document.getElementById('file-upload-field');
            const externalUrlField = document.getElementById('external-url-field');
            const textContentField = document.getElementById('text-content-field');
            const termSelect = document.getElementById('term_id');
            const weekSelect = document.getElementById('course_week_id');
            const titleInput = document.getElementById('title');
            const titleCounter = document.getElementById('title-counter');

            function toggleFields() {
                // Hide all fields
                videoUrlField.classList.add('hidden');
                fileUploadField.classList.add('hidden');
                externalUrlField.classList.add('hidden');
                textContentField.classList.add('hidden');

                // Show relevant field based on type
                const type = typeSelect.value;
                if (type === 'video') {
                    videoUrlField.classList.remove('hidden');
                } else if (type === 'file') {
                    fileUploadField.classList.remove('hidden');
                } else if (type === 'link') {
                    externalUrlField.classList.remove('hidden');
                } else if (type === 'text') {
                    textContentField.classList.remove('hidden');
                }
            }

            function updateWeeks(preselectWeekId = null) {
                const selectedTermId = termSelect.value;
                weekSelect.innerHTML = '<option value="">Choose a week...</option>';
                
                if (selectedTermId) {
                    const selectedTerm = courseStructure.find(term => term.id == selectedTermId);
                    if (selectedTerm) {
                        selectedTerm.weeks.forEach(week => {
                            const option = document.createElement('option');
                            option.value = week.id;
                            option.textContent = `${week.sub_term} - ${week.title}`;
                            if (preselectWeekId && String(week.id) === String(preselectWeekId)) {
                                option.selected = true;
                            }
                            weekSelect.appendChild(option);
                        });

                        if (preselectWeekId && !selectedTerm.weeks.some(week => String(week.id) === String(preselectWeekId))) {
                            weekSelect.selectedIndex = 0;
                        }
                    }
                }
            }

            // Character counters
            function updateCharacterCounter(input, counter, maxLength) {
                const currentLength = input.value.length;
                counter.textContent = `${currentLength.toLocaleString()}/${maxLength.toLocaleString()}`;
                
                // Change color based on usage
                if (currentLength > maxLength * 0.9) {
                    counter.classList.add('text-red-500');
                    counter.classList.remove('text-gray-400', 'text-yellow-500');
                } else if (currentLength > maxLength * 0.7) {
                    counter.classList.add('text-yellow-500');
                    counter.classList.remove('text-gray-400', 'text-red-500');
                } else {
                    counter.classList.add('text-gray-400');
                    counter.classList.remove('text-yellow-500', 'text-red-500');
                }
            }

            // Initialize character counters
            if (titleInput && titleCounter) {
                titleInput.addEventListener('input', () => updateCharacterCounter(titleInput, titleCounter, 500));
            }

            typeSelect.addEventListener('change', toggleFields);
            termSelect.addEventListener('change', () => {
                updateWeeks();
                sessionStorage.removeItem('upload_materials_selected_week');
            });
            
            // Initialize on page load
            toggleFields();
            
            function findWeek(termId, weekId) {
                const term = courseStructure.find(term => String(term.id) === String(termId));
                if (!term) return { term: null, week: null };
                const week = term.weeks.find(week => String(week.id) === String(weekId));
                return { term, week };
            }

            function findTermByWeek(weekId) {
                for (const term of courseStructure) {
                    const week = term.weeks.find(week => String(week.id) === String(weekId));
                    if (week) {
                        return { termId: term.id, week };
                    }
                }
                return { termId: null, week: null };
            }

            function persistSelection(termId, weekId) {
                if (termId) {
                    sessionStorage.setItem('upload_materials_selected_term', termId);
                }
                if (weekId) {
                    sessionStorage.setItem('upload_materials_selected_week', weekId);
                }
            }

            function selectTermAndWeek(termId, weekId) {
                if (!termId) return;
                termSelect.value = termId;
                updateWeeks(weekId);
                if (weekId) {
                    weekSelect.value = weekId;
                    weekSelect.dispatchEvent(new Event('change'));
                }
                persistSelection(termId, weekId);
                const savedTerms = JSON.parse(sessionStorage.getItem('collapsible_terms') || '{}');
                savedTerms[termId] = true;
                sessionStorage.setItem('collapsible_terms', JSON.stringify(savedTerms));
                if (weekId) {
                    const savedWeeks = JSON.parse(sessionStorage.getItem('collapsible_weeks') || '{}');
                    savedWeeks[weekId] = true;
                    sessionStorage.setItem('collapsible_weeks', JSON.stringify(savedWeeks));
                }
                const titleInput = document.getElementById('title');
                if (titleInput) {
                    titleInput.focus();
                }
            }

            const addMaterialButtons = document.querySelectorAll('.add-material-btn');
            addMaterialButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const termId = button.dataset.termId;
                    const weekId = button.dataset.weekId;
                    selectTermAndWeek(termId, weekId);
                });
            });

            const urlParams = new URL(window.location.href).searchParams;
            const urlWeekId = urlParams.get('week');
            const storedTermId = sessionStorage.getItem('upload_materials_selected_term');
            const storedWeekId = sessionStorage.getItem('upload_materials_selected_week');
            let initialWeekId = urlWeekId || storedWeekId || null;
            let initialTermId = storedTermId || null;

            if (initialWeekId && !initialTermId) {
                const { termId } = findTermByWeek(initialWeekId);
                initialTermId = termId;
            }

            if (initialTermId) {
                selectTermAndWeek(initialTermId, initialWeekId);
            } else {
                updateWeeks();
            }

            if (urlWeekId) {
                urlParams.delete('week');
                const newUrl = `${window.location.pathname}${urlParams.toString() ? '?' + urlParams.toString() : ''}`;
                window.history.replaceState({}, document.title, newUrl);
            }

            // Initialize counters
            if (titleInput && titleCounter) {
                updateCharacterCounter(titleInput, titleCounter, 500);
            }
        });
    </script>

    <!-- Quill.js CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    
    <!-- Quill.js JavaScript -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    
    <script>
        // Initialize Quill editor
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill editor
            const quill = new Quill('#description-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['link'],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                },
                placeholder: 'Describe the material in detail...'
            });

            // Update hidden input with HTML content
            quill.on('text-change', function() {
                const html = quill.root.innerHTML;
                document.getElementById('description').value = html;
                
                // Update character counter (count text content, not HTML)
                const text = quill.getText();
                const counter = document.getElementById('description-counter');
                const currentLength = text.length;
                counter.textContent = `${currentLength.toLocaleString()}/5,000`;
                
                // Change color based on usage
                if (currentLength > 5000 * 0.9) {
                    counter.classList.add('text-red-500');
                    counter.classList.remove('text-gray-400', 'text-yellow-500');
                } else if (currentLength > 5000 * 0.7) {
                    counter.classList.add('text-yellow-500');
                    counter.classList.remove('text-gray-400', 'text-red-500');
                } else {
                    counter.classList.add('text-gray-400');
                    counter.classList.remove('text-yellow-500', 'text-red-500');
                }
            });

            // Initialize text content editor
            const textContentQuill = new Quill('#text-content-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['link'],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                },
                placeholder: 'Enter detailed instructions or content...'
            });

            // Update hidden input with HTML content for text content
            textContentQuill.on('text-change', function() {
                const html = textContentQuill.root.innerHTML;
                document.getElementById('text_content').value = html;
                
                // Update character counter (count text content, not HTML)
                const text = textContentQuill.getText();
                const counter = document.getElementById('text-content-counter');
                const currentLength = text.length;
                counter.textContent = `${currentLength.toLocaleString()}/10,000`;
                
                // Change color based on usage
                if (currentLength > 10000 * 0.9) {
                    counter.classList.add('text-red-500');
                    counter.classList.remove('text-gray-400', 'text-yellow-500');
                } else if (currentLength > 10000 * 0.7) {
                    counter.classList.add('text-yellow-500');
                    counter.classList.remove('text-gray-400', 'text-red-500');
                } else {
                    counter.classList.add('text-gray-400');
                    counter.classList.remove('text-yellow-500', 'text-red-500');
                }
            });

            // Update the existing character counter initialization
            const descriptionCounter = document.getElementById('description-counter');
            const text = quill.getText();
            const currentLength = text.length;
            descriptionCounter.textContent = `${currentLength.toLocaleString()}/5,000`;

            // Initialize text content counter
            const textContentCounter = document.getElementById('text-content-counter');
            const textContentText = textContentQuill.getText();
            const textContentLength = textContentText.length;
            textContentCounter.textContent = `${textContentLength.toLocaleString()}/10,000`;
        });

        // Global variables for edit modal
        let editQuill = null;
        let editTextContentQuill = null;
        let currentMaterialId = null;

        // Initialize edit modal Quill editor
        function initializeEditQuill() {
            if (editQuill) {
                editQuill.destroy();
            }
            
            editQuill = new Quill('#edit-description-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['link'],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                },
                placeholder: 'Enter material description...'
            });

            // Update hidden input with HTML content
            editQuill.on('text-change', function() {
                const html = editQuill.root.innerHTML;
                document.getElementById('edit_description').value = html;
            });
        }

        // Initialize edit modal text content Quill editor
        function initializeEditTextContentQuill() {
            if (editTextContentQuill) {
                editTextContentQuill.destroy();
            }
            
            editTextContentQuill = new Quill('#edit-text-content-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['link'],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                },
                placeholder: 'Enter detailed instructions or content...'
            });

            // Update hidden input with HTML content
            editTextContentQuill.on('text-change', function() {
                const html = editTextContentQuill.root.innerHTML;
                document.getElementById('edit_text_content').value = html;
            });
        }

        // Toggle content fields based on material type
        function toggleEditContentFields() {
            const type = document.getElementById('edit_type').value;
            
            // Hide all content fields
            document.getElementById('edit-video-url-field').classList.add('hidden');
            document.getElementById('edit-file-upload-field').classList.add('hidden');
            document.getElementById('edit-external-url-field').classList.add('hidden');
            document.getElementById('edit-text-content-field').classList.add('hidden');
            
            // Show relevant field based on type
            if (type === 'video') {
                document.getElementById('edit-video-url-field').classList.remove('hidden');
            } else if (type === 'file') {
                document.getElementById('edit-file-upload-field').classList.remove('hidden');
            } else if (type === 'link') {
                document.getElementById('edit-external-url-field').classList.remove('hidden');
            } else if (type === 'text') {
                document.getElementById('edit-text-content-field').classList.remove('hidden');
                // Initialize text content editor when showing
                setTimeout(() => {
                    initializeEditTextContentQuill();
                }, 100);
            }
        }

        // Open edit modal
        function openEditModal(materialId) {
            console.log('Opening edit modal for material ID:', materialId);
            currentMaterialId = materialId;
            
            // Show modal
            document.getElementById('editMaterialModal').classList.remove('hidden');
            
            // Initialize Quill editor
            setTimeout(() => {
                initializeEditQuill();
            }, 100);
            
            // Fetch material data
            const url = `/teacher/materials/${materialId}/edit-data`;
            console.log('Fetching from URL:', url);
            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Material data received:', data);
                    
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    
                    // Populate form fields
                    document.getElementById('edit_course_title').value = data.course_title || '';
                    document.getElementById('edit_term_id').value = data.term_id || '';
                    document.getElementById('edit_title').value = data.title || '';
                    document.getElementById('edit_type').value = data.type || '';
                    document.getElementById('edit_is_required').checked = data.is_required || false;
                    
                    // Set description in Quill editor
                    if (data.description) {
                        editQuill.root.innerHTML = data.description;
                        document.getElementById('edit_description').value = data.description;
                    }
                    
                    // Populate content fields based on type
                    if (data.type === 'video' && data.youtube_url) {
                        document.getElementById('edit_youtube_url').value = data.youtube_url;
                    } else if (data.type === 'link' && data.external_url) {
                        document.getElementById('edit_external_url').value = data.external_url;
                    } else if (data.type === 'file' && data.file_name) {
                        document.getElementById('edit-current-file-name').textContent = data.file_name;
                        document.getElementById('edit-current-file-info').classList.remove('hidden');
                    } else if (data.type === 'text' && data.content) {
                        // Set text content in Quill editor
                        setTimeout(() => {
                            if (editTextContentQuill) {
                                editTextContentQuill.root.innerHTML = data.content;
                                document.getElementById('edit_text_content').value = data.content;
                            }
                        }, 200);
                    }
                    
                    // Toggle content fields based on type
                    toggleEditContentFields();
                    
                    // Update weeks dropdown
                    if (data.term_id) {
                        updateEditWeeks(data.term_id, data.week_id);
                    }
                })
                .catch(error => {
                    console.error('Error fetching material data:', error);
                    showToast('Error loading material data: ' + error.message, 'error');
                });
        }

        // Close edit modal
        function closeEditModal() {
            document.getElementById('editMaterialModal').classList.add('hidden');
            currentMaterialId = null;
            
            // Reset form
            document.getElementById('editMaterialForm').reset();
            if (editQuill) {
                editQuill.setContents([]);
            }
            if (editTextContentQuill) {
                editTextContentQuill.setContents([]);
            }
            
            // Hide all content fields
            document.getElementById('edit-video-url-field').classList.add('hidden');
            document.getElementById('edit-file-upload-field').classList.add('hidden');
            document.getElementById('edit-external-url-field').classList.add('hidden');
            document.getElementById('edit-text-content-field').classList.add('hidden');
            document.getElementById('edit-current-file-info').classList.add('hidden');
        }

        // Update weeks dropdown in edit modal
        function updateEditWeeks(termId, selectedWeekId = null) {
            const weekSelect = document.getElementById('edit_course_week_id');
            weekSelect.innerHTML = '<option value="">Choose a week...</option>';
            
            if (termId) {
                // Try to use courseStructure first if available
                if (typeof courseStructure !== 'undefined') {
                    const selectedTerm = courseStructure.find(term => term.id == termId);
                    if (selectedTerm && selectedTerm.weeks) {
                        selectedTerm.weeks.forEach(week => {
                            const option = document.createElement('option');
                            option.value = week.id;
                            option.textContent = `${week.sub_term || 'Week'} - ${week.title}`;
                            if (selectedWeekId && week.id == selectedWeekId) {
                                option.selected = true;
                            }
                            weekSelect.appendChild(option);
                        });
                        return;
                    }
                }
                
                // Fallback: Fetch weeks for the selected term via API
                fetch(`/teacher/terms/${termId}/weeks`)
                    .then(response => response.json())
                    .then(weeks => {
                        weeks.forEach(week => {
                            const option = document.createElement('option');
                            option.value = week.id;
                            option.textContent = `${week.sub_term || 'Week'} - ${week.title}`;
                            if (selectedWeekId && week.id == selectedWeekId) {
                                option.selected = true;
                            }
                            weekSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching weeks:', error);
                        showToast('Error loading weeks for selected term', 'error');
                    });
            }
        }

        // Handle edit form submission
        document.getElementById('editMaterialForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!currentMaterialId) return;
            
            const formData = new FormData(this);
            formData.append('_method', 'PATCH');
            
            fetch(`/teacher/materials/${currentMaterialId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Material updated successfully!', 'success');
                    closeEditModal();
                    // Reload the page to show updated data
                    window.location.reload();
                } else {
                    showToast(data.message || 'Error updating material', 'error');
                }
            })
            .catch(error => {
                console.error('Error updating material:', error);
                showToast('Error updating material', 'error');
            });
        });

        // Handle term change in edit modal
        document.getElementById('edit_term_id').addEventListener('change', function() {
            updateEditWeeks(this.value);
        });

        // Handle type change in edit modal
        document.getElementById('edit_type').addEventListener('change', function() {
            toggleEditContentFields();
        });

        // Show toast notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            const successIcon = document.getElementById('toast-success-icon');
            const errorIcon = document.getElementById('toast-error-icon');
            
            toastMessage.textContent = message;
            
            // Show appropriate icon
            if (type === 'success') {
                successIcon.classList.remove('hidden');
                errorIcon.classList.add('hidden');
            } else {
                successIcon.classList.add('hidden');
                errorIcon.classList.remove('hidden');
            }
            
            // Show toast
            toast.classList.remove('hidden');
            
            // Hide toast after 3 seconds
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        // Close modal when clicking outside
        document.getElementById('editMaterialModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>

    <!-- Edit Material Modal -->
    <div id="editMaterialModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full md:w-[600px] shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Material</h3>
                    <button type="button" 
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            onclick="closeEditModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="editMaterialForm" class="mt-6 space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Course (disabled) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Course
                        </label>
                        <input type="text" 
                               id="edit_course_title" 
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 bg-gray-100 dark:bg-gray-600 cursor-not-allowed"
                               disabled>
                    </div>

                    <!-- Term -->
                    <div>
                        <label for="edit_term_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Term *
                        </label>
                        <select name="term_id" 
                                id="edit_term_id"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Choose a term...</option>
                            @foreach($course->terms as $term)
                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Week -->
                    <div>
                        <label for="edit_course_week_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Week *
                        </label>
                        <select name="course_week_id" 
                                id="edit_course_week_id"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Choose a week...</option>
                        </select>
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="edit_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Material Title *
                        </label>
                        <input type="text" 
                               id="edit_title" 
                               name="title"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description
                        </label>
                        <div class="relative">
                            <!-- Quill Editor Container for Edit Modal -->
                            <div id="edit-description-editor" 
                                 class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition duration-150 ease-in-out"
                                 style="height: 150px;">
                            </div>
                            <!-- Hidden input to store HTML content -->
                            <input type="hidden" id="edit_description" name="description">
                        </div>
                    </div>

                    <!-- Material Type -->
                    <div>
                        <label for="edit_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Material Type *
                        </label>
                        <select name="type" 
                                id="edit_type"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Select type...</option>
                            <option value="video">Video (YouTube/Vimeo)</option>
                            <option value="file">File Upload</option>
                            <option value="link">External Link</option>
                            <option value="text">Text Instructions</option>
                        </select>
                    </div>

                    <!-- Video URL Field -->
                    <div id="edit-video-url-field" class="hidden">
                        <label for="edit_youtube_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Video URL
                        </label>
                        <input type="url" 
                               id="edit_youtube_url" 
                               name="youtube_url" 
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="https://www.youtube.com/watch?v=...">
                    </div>

                    <!-- File Upload Field -->
                    <div id="edit-file-upload-field" class="hidden">
                        <label for="edit_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Upload New File (optional)
                        </label>
                        <input type="file" 
                               id="edit_file" 
                               name="file" 
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Leave empty to keep current file. Max file size: 10MB</p>
                        <!-- Current file info -->
                        <div id="edit-current-file-info" class="mt-2 p-2 bg-gray-100 dark:bg-gray-700 rounded text-sm text-gray-600 dark:text-gray-400 hidden">
                            <strong>Current file:</strong> <span id="edit-current-file-name"></span>
                        </div>
                    </div>

                    <!-- External URL Field -->
                    <div id="edit-external-url-field" class="hidden">
                        <label for="edit_external_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            External URL
                        </label>
                        <input type="url" 
                               id="edit_external_url" 
                               name="external_url" 
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="https://example.com">
                    </div>

                    <!-- Text Content Field -->
                    <div id="edit-text-content-field" class="hidden">
                        <label for="edit_text_content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Text Instructions
                        </label>
                        <div class="relative">
                            <!-- Quill Editor Container for Text Content -->
                            <div id="edit-text-content-editor" 
                                 class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition duration-150 ease-in-out"
                                 style="height: 200px;">
                            </div>
                            <!-- Hidden input to store HTML content -->
                            <input type="hidden" id="edit_text_content" name="text_content">
                        </div>
                    </div>

                    <!-- Required Checkbox -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="edit_is_required" 
                               name="is_required" 
                               value="1"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="edit_is_required" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Required material
                        </label>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out"
                                onclick="closeEditModal()">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 max-w-sm">
            <div class="flex items-center">
                <div id="toast-icon" class="flex-shrink-0">
                    <!-- Success Icon -->
                    <svg id="toast-success-icon" class="w-5 h-5 text-green-500 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <!-- Error Icon -->
                    <svg id="toast-error-icon" class="w-5 h-5 text-red-500 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p id="toast-message" class="text-sm font-medium text-gray-900 dark:text-white"></p>
                </div>
            </div>
        </div>
    </div>
</x-teacher-layout>
