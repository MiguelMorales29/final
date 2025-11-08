<x-student-layout>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .rich-text.ql-editor {
            padding: 0;
            background: transparent;
            color: inherit;
            font-size: 1rem;
            line-height: 1.7;
            word-break: break-word;
            white-space: pre-wrap;
            font-family: inherit;
        }
        .rich-text.ql-editor p { margin-bottom: 0.85rem; }
        .rich-text.ql-editor h1,
        .rich-text.ql-editor h2,
        .rich-text.ql-editor h3,
        .rich-text.ql-editor h4 {
            font-weight: 700;
            line-height: 1.3;
            margin: 1.25rem 0 0.75rem;
        }
        .rich-text.ql-editor h1 { font-size: 2rem; }
        .rich-text.ql-editor h2 { font-size: 1.75rem; }
        .rich-text.ql-editor h3 { font-size: 1.5rem; }
        .rich-text.ql-editor h4 { font-size: 1.25rem; }
        .rich-text.ql-editor ul,
        .rich-text.ql-editor ol {
            padding-left: 1.5rem;
            margin-bottom: 0.85rem;
        }
        .rich-text.ql-editor li { margin-bottom: 0.4rem; }
        .rich-text.ql-editor a {
            color: #2563eb;
            text-decoration: underline;
        }
        .dark .rich-text.ql-editor a {
            color: #60a5fa;
        }
        .rich-text.ql-editor strong { font-weight: 700; }
        .rich-text.ql-editor em { font-style: italic; }
        .rich-text.ql-editor blockquote {
            border-left: 4px solid rgba(59,130,246,0.4);
            padding-left: 1rem;
            color: #4b5563;
            font-style: italic;
            margin: 1rem 0;
        }
        .dark .rich-text.ql-editor blockquote {
            color: #cbd5f5;
            border-left-color: rgba(96,165,250,0.5);
        }
        .rich-text.ql-editor code {
            background: rgba(148,163,184,0.15);
            padding: 0.15rem 0.4rem;
            border-radius: 0.35rem;
            font-size: 0.95rem;
        }
        .dark .rich-text.ql-editor code {
            background: rgba(148,163,184,0.25);
        }
    </style>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Breadcrumb Navigation -->
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-4xl mx-auto px-6 py-4">
                <nav class="flex items-center space-x-2 text-sm" aria-label="Breadcrumb">
                    <a href="{{ route('student.modules.index') }}" 
                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition-colors duration-150">
                        Modules
                    </a>
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('student.course.show', $material->course) }}" 
                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition-colors duration-150">
                        {{ $material->course->title }}
                    </a>
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $material->title }}</span>
                </nav>
            </div>
        </div>

        <!-- Main Content Container -->
        <div class="max-w-4xl mx-auto px-6 py-8">
            <!-- Document Card -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <!-- Header Section -->
                <div class="px-8 py-8 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Title -->
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 leading-tight">
                                {{ $material->title }}
                            </h1>
                            
                            <!-- Material Type Badge -->
                            <div class="flex items-center gap-4 mb-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($material->type === 'video') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @elseif($material->type === 'file') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($material->type === 'link') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                    @if($material->type === 'video')
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    @elseif($material->type === 'file')
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    @elseif($material->type === 'link')
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    @endif
                                    {{ ucfirst($material->type) }}
                                </span>
                                
                                @if($material->week && $material->week->subTerm && $material->week->subTerm->term)
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $material->week->subTerm->term->name }} • {{ $material->week->subTerm->title }} • {{ $material->week->title }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Meta Information -->
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <p>Uploaded by <span class="font-medium text-gray-900 dark:text-white">{{ $material->course->teacher->name }}</span> • {{ $material->created_at->format('M j, Y \a\t g:i A') }}</p>
                                @if($material->type === 'file' && $material->file_size)
                                    <p class="mt-1">File size: {{ $material->file_size_formatted }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-3 ml-8">
                            <a href="{{ route('student.modules.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back to Modules
                            </a>
                            
                            <!-- Mark as Done button - Only show for REQUIRED materials -->
                            @if($material->is_required)
                                <button id="markDoneBtn" onclick="toggleMarkAsDone({{ $material->id }})" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-green-100 dark:bg-gray-700 dark:hover:bg-green-900 text-gray-700 hover:text-green-700 dark:text-gray-300 dark:hover:text-green-300 text-sm font-medium rounded-lg transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span id="markDoneText">Mark as Done</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="px-8 py-8">
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Description</h2>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600 overflow-auto">
                            <div class="ql-editor rich-text">
                                {!! $material->description ?: '<em>No description provided.</em>' !!}
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 mb-8"></div>

                    <!-- Media Content Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Content</h2>
                        
                        @if($material->type === 'video')
                            <!-- Video Player -->
                            @php
                                $embedUrl = '';
                                if (str_contains($material->content, 'youtube.com') || str_contains($material->content, 'youtu.be')) {
                                    $videoId = '';
                                    if (str_contains($material->content, 'youtu.be/')) {
                                        $videoId = explode('youtu.be/', $material->content)[1];
                                        $videoId = explode('?', $videoId)[0];
                                    } else {
                                        parse_str(parse_url($material->content, PHP_URL_QUERY), $params);
                                        $videoId = $params['v'] ?? '';
                                    }
                                    $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                                } elseif (str_contains($material->content, 'vimeo.com')) {
                                    $videoId = explode('vimeo.com/', $material->content)[1];
                                    $videoId = explode('?', $videoId)[0];
                                    $embedUrl = "https://player.vimeo.com/video/{$videoId}";
                                }
                            @endphp

                            @if($embedUrl)
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                                    <div class="aspect-w-16 aspect-h-9">
                                        <iframe src="{{ $embedUrl }}"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen
                                                class="w-full h-96">
                                        </iframe>
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-8 text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">Video format not supported for inline viewing.</p>
                                    <a href="{{ $material->content }}" target="_blank" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Open Video
                                    </a>
                                </div>
                            @endif
                            
                        @elseif($material->type === 'file')
                            <!-- File Viewer -->
                            @php
                                $fileExtension = strtolower(pathinfo($material->content, PATHINFO_EXTENSION));
                            @endphp
                            
                            @if($fileExtension === 'pdf')
                                <!-- PDF Viewer -->
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                                    <div class="p-4 bg-gray-200 dark:bg-gray-800 flex items-center justify-between border-b border-gray-300 dark:border-gray-600">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">PDF Document Viewer</span>
                                        <a href="{{ route('student.materials.view', $material) }}" target="_blank" 
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors duration-150">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Open in New Tab
                                        </a>
                                    </div>
                                    <iframe src="{{ Storage::url($material->content) }}#toolbar=1&navpanes=1&scrollbar=0" 
                                            class="w-full h-[80vh]"
                                            frameborder="0">
                                    </iframe>
                                </div>
                                
                            @elseif(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <!-- Image Viewer -->
                                <div class="text-center">
                                    <img src="{{ Storage::url($material->content) }}" 
                                         alt="{{ $material->title }}" 
                                         class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg">
                                </div>
                                
                            @elseif(in_array($fileExtension, ['doc', 'docx']))
                                <!-- Google Docs Viewer for Word Documents -->
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                                    <div class="p-4 bg-gray-200 dark:bg-gray-800 flex items-center justify-between border-b border-gray-300 dark:border-gray-600">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Word Document Viewer</span>
                                        <a href="{{ route('student.materials.view', $material) }}" target="_blank" 
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors duration-150">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Open in New Tab
                                        </a>
                                    </div>
                                    <iframe src="https://docs.google.com/gview?url={{ urlencode(Storage::url($material->content)) }}&embedded=true" 
                                            class="w-full h-[80vh]"
                                            frameborder="0">
                                    </iframe>
                                </div>
                                
                            @elseif(in_array($fileExtension, ['ppt', 'pptx']))
                                <!-- Google Docs Viewer for PowerPoint -->
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                                    <div class="p-4 bg-gray-200 dark:bg-gray-800 flex items-center justify-between border-b border-gray-300 dark:border-gray-600">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">PowerPoint Presentation Viewer</span>
                                        <a href="{{ route('student.materials.view', $material) }}" target="_blank" 
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors duration-150">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Open in New Tab
                                        </a>
                                    </div>
                                    <iframe src="https://docs.google.com/gview?url={{ urlencode(Storage::url($material->content)) }}&embedded=true" 
                                            class="w-full h-[80vh]"
                                            frameborder="0">
                                    </iframe>
                                </div>
                                
                            @else
                                <!-- Unsupported file type -->
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-8 text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">File format not supported for inline viewing.</p>
                                    <a href="{{ Storage::url($material->content) }}" download 
                                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download File
                                    </a>
                                </div>
                            @endif
                            
                        @elseif($material->type === 'link')
                            <!-- External Link -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-8 text-center">
                                <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">This is an external link.</p>
                                <a href="{{ $material->content }}" target="_blank" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Open Link
                                </a>
                            </div>
                            
                        @else
                            <!-- Text Content (default) -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600 overflow-auto">
                                <div class="ql-editor rich-text">
                                    {!! $material->content ?: '<em>No additional content provided.</em>' !!}
                                </div>
                            </div>
                            
                            <!-- Smart Buddy (only for text-based materials with extracted text) -->
                            @if(!empty($textContent ?? ''))
                                <x-smart-buddy :textContent="$textContent" :materialId="$material->id" />
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    let isCompleted = false;

    document.addEventListener('DOMContentLoaded', function() {
        checkCompletionStatus({{ $material->id }});
    });

    function checkCompletionStatus(materialId) {
        fetch(`/student/materials/${materialId}/completion-status`)
            .then(response => response.json())
            .then(data => {
                isCompleted = data.is_completed;
                updateButtonState();
            })
            .catch(error => console.error('Error:', error));
    }

    function toggleMarkAsDone(materialId) {
        const btn = document.getElementById('markDoneBtn');
        const text = document.getElementById('markDoneText');
        
        btn.disabled = true;
        text.textContent = isCompleted ? 'Unmarking...' : 'Marking...';
        
        const endpoint = isCompleted 
            ? `/student/materials/${materialId}/unmark-done`
            : `/student/materials/${materialId}/mark-done`;
        const method = isCompleted ? 'DELETE' : 'POST';
        
        fetch(endpoint, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                isCompleted = !isCompleted;
                updateButtonState();
                const message = isCompleted ? 'Material marked as done!' : 'Material unmarked!';
                showToast(message, isCompleted ? 'success' : 'info');
                
                // Dispatch event for modules page to update badge
                window.dispatchEvent(new CustomEvent('materialCompletionUpdated', {
                    detail: { materialId: materialId, isCompleted: isCompleted }
                }));
            } else if (data.error) {
                showToast(data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred. Please try again.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
        });
    }

    function updateButtonState() {
        const btn = document.getElementById('markDoneBtn');
        const text = document.getElementById('markDoneText');
        
        if (isCompleted) {
            btn.classList.remove('bg-gray-100','hover:bg-green-100','text-gray-700','hover:text-green-700','dark:bg-gray-700','dark:hover:bg-green-900','dark:text-gray-300','dark:hover:text-green-300');
            btn.classList.add('bg-green-100','hover:bg-green-200','dark:bg-green-900','dark:hover:bg-green-800','text-green-700','dark:text-green-300');
            text.textContent = 'Marked as Done';
        } else {
            btn.classList.remove('bg-green-100','hover:bg-green-200','dark:bg-green-900','dark:hover:bg-green-800','text-green-700','dark:text-green-300');
            btn.classList.add('bg-gray-100','hover:bg-green-100','dark:bg-gray-700','dark:hover:bg-green-900','text-gray-700','hover:text-green-700','dark:text-gray-300','dark:hover:text-green-300');
            text.textContent = 'Mark as Done';
        }
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        const colors = type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-blue-600');
        toast.className = `fixed top-4 right-4 px-4 py-2 rounded text-white shadow-lg z-50 ${colors}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }
    </script>
</x-student-layout>