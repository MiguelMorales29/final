<x-teacher-layout>
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ route('teacher.courses.show', $course) }}" 
               class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Material</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $course->title }} - {{ $material->title }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <form method="POST" action="{{ route('teacher.materials.update', [$course, $material]) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Material Title *
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title"
                           value="{{ old('title', $material->title) }}"
                           class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description
                    </label>
                    <div class="relative">
                        <!-- Quill Editor Container -->
                        <div id="description-editor" 
                             class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition duration-150 ease-in-out"
                             style="height: 150px;">
                        </div>
                        <!-- Hidden input to store HTML content -->
                        <input type="hidden" name="description" id="description" value="{{ old('description', $material->description) }}">
                    </div>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="course_week_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Week *
                    </label>
                    <select name="course_week_id" 
                            id="course_week_id"
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Select a week</option>
                        @foreach($course->terms as $term)
                            @foreach($term->subTerms as $subTerm)
                                @foreach($subTerm->weeks as $week)
                                    <option value="{{ $week->id }}" 
                                            {{ old('course_week_id', $material->course_week_id) == $week->id ? 'selected' : '' }}>
                                        {{ $term->name }} - {{ $subTerm->title }} - {{ $week->title }}
                                    </option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>
                    @error('course_week_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Material Type *
                    </label>
                    <select name="type" 
                            id="type"
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="video" {{ old('type', $material->type) == 'video' ? 'selected' : '' }}>Video (YouTube/Vimeo URL)</option>
                        <option value="file" {{ old('type', $material->type) == 'file' ? 'selected' : '' }}>File Upload</option>
                        <option value="link" {{ old('type', $material->type) == 'link' ? 'selected' : '' }}>External Link</option>
                        <option value="text" {{ old('type', $material->type) == 'text' ? 'selected' : '' }}>Text Instructions</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Video URL Input -->
                <div id="video-input" class="content-input" style="display: {{ old('type', $material->type) == 'video' ? 'block' : 'none' }};">
                    <label for="video_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Video URL *
                    </label>
                    <input type="url" 
                           name="content" 
                           id="video_url"
                           value="{{ old('type', $material->type) == 'video' ? old('content', $material->content) : '' }}"
                           placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/..."
                           class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('content')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload Input -->
                <div id="file-input" class="content-input" style="display: {{ old('type', $material->type) == 'file' ? 'block' : 'none' }};">
                    <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Upload File *
                    </label>
                    <input type="file" 
                           name="file" 
                           id="file"
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif"
                           class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @if($material->type == 'file' && $material->content)
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Current file: <a href="{{ Storage::url($material->content) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($material->content) }}</a>
                        </p>
                    @endif
                    @error('file')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- External Link Input -->
                <div id="link-input" class="content-input" style="display: {{ old('type', $material->type) == 'link' ? 'block' : 'none' }};">
                    <label for="link_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        External Link *
                    </label>
                    <input type="url" 
                           name="content" 
                           id="link_url"
                           value="{{ old('type', $material->type) == 'link' ? old('content', $material->content) : '' }}"
                           placeholder="https://example.com"
                           class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('content')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Text Instructions Input -->
                <div id="text-input" class="content-input" style="display: {{ old('type', $material->type) == 'text' ? 'block' : 'none' }};">
                    <label for="text_content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Text Instructions *
                    </label>
                    <div class="relative">
                        <!-- Quill Editor Container for Text Content -->
                        <div id="text-content-editor" 
                             class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition duration-150 ease-in-out"
                             style="height: 300px;">
                        </div>
                        <!-- Hidden input to store HTML content -->
                        <input type="hidden" name="content" id="text_content" value="{{ old('type', $material->type) == 'text' ? old('content', $material->content) : '' }}">
                    </div>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('teacher.courses.show', $course) }}" 
                       class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                        Update Material
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quill.js CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    
    <!-- Quill.js JavaScript -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
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
                placeholder: 'Enter material description...'
            });

            // Set initial content if editing existing material
            const existingContent = document.getElementById('description').value;
            if (existingContent) {
                quill.root.innerHTML = existingContent;
            }

            // Update hidden input with HTML content
            quill.on('text-change', function() {
                const html = quill.root.innerHTML;
                document.getElementById('description').value = html;
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

            // Set initial content for text content if editing existing material
            const existingTextContent = document.getElementById('text_content').value;
            if (existingTextContent) {
                textContentQuill.root.innerHTML = existingTextContent;
            }

            // Update hidden input with HTML content for text content
            textContentQuill.on('text-change', function() {
                const html = textContentQuill.root.innerHTML;
                document.getElementById('text_content').value = html;
            });

            const typeSelect = document.getElementById('type');
            const contentInputs = document.querySelectorAll('.content-input');
            
            function toggleContentInputs() {
                const selectedType = typeSelect.value;
                
                contentInputs.forEach(input => {
                    input.style.display = 'none';
                });
                
                switch(selectedType) {
                    case 'video':
                        document.getElementById('video-input').style.display = 'block';
                        break;
                    case 'file':
                        document.getElementById('file-input').style.display = 'block';
                        break;
                    case 'link':
                        document.getElementById('link-input').style.display = 'block';
                        break;
                    case 'text':
                        document.getElementById('text-input').style.display = 'block';
                        break;
                }
            }
            
            typeSelect.addEventListener('change', toggleContentInputs);
        });
    </script>
</x-teacher-layout>


