<x-student-layout>
    <!-- Include Quill.js -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <style>
        .ql-editor { word-break: break-word; white-space: pre-wrap; }
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
        .rich-text.ql-editor a { color: #2563eb; text-decoration: underline; }
        .dark .rich-text.ql-editor a { color: #60a5fa; }
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
    <div class="flex items-center mb-8">
        <a href="{{ route('student.assignments.index') }}" 
           class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="min-w-0 space-y-1">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white break-words">{{ $assignment->title }}</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400 truncate" title="{{ $assignment->course->title }}">{{ $assignment->course->title }}</p>
            @if($assignment->week)
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate" title="{{ $assignment->week->subTerm->term->name }} - {{ $assignment->week->subTerm->title }} - {{ $assignment->week->title }}">{{ $assignment->week->subTerm->term->name }} - {{ $assignment->week->subTerm->title }} - {{ $assignment->week->title }}</p>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Assignment Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Assignment Info -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Assignment Details</h2>

                <div class="space-y-4">
                    @if($assignment->description)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Description</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600 overflow-auto">
                                <div class="ql-editor rich-text">
                                    {!! $assignment->description !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($assignment->instructions)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Instructions</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600 overflow-auto">
                                <div class="ql-editor rich-text">
                                    {!! $assignment->instructions !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">Submission Type</h4>
                            <p class="text-gray-600 dark:text-gray-400 capitalize">{{ $assignment->submission_type }}</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">Points</h4>
                            <p class="text-gray-600 dark:text-gray-400">{{ $assignment->points }} points</p>
                        </div>
                        @if($assignment->due_date)
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white mb-1">Due Date</h4>
                                <p class="text-gray-600 dark:text-gray-400">{{ $assignment->due_date->format('M j, Y g:i A') }}</p>
                            </div>
                        @endif
                    </div>

                    @if($assignment->submission_type !== 'text' && $assignment->allowed_file_types)
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">File Requirements</h4>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm">
                                    Max {{ $assignment->max_file_size }}MB per file
                                </span>
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm">
                                    Max {{ $assignment->max_files }} file(s)
                                </span>
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm">
                                    {{ implode(', ', array_map('strtoupper', $assignment->allowed_file_types)) }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submission Form -->
            @if($submission->status !== 'graded' && $canSubmit)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Your Submission</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        You have {{ $remainingAttempts }} attempt(s) remaining.
                    </p>

                    <form method="POST" action="{{ route('student.assignments.submit', $assignment) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        @if($assignment->submission_type === 'text' || $assignment->submission_type === 'both')
                            <div>
                                <label for="text_submission" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Text Submission {{ $assignment->submission_type === 'text' ? '*' : '' }}
                                </label>
                                <div class="relative">
                                    <!-- Quill Editor Container -->
                                    <div id="text-submission-editor" 
                                         class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition duration-150 ease-in-out"
                                         style="height: 300px;">
                                    </div>
                                    <!-- Hidden input to store HTML content -->
                                    <input type="hidden" id="text_submission" name="text_submission" value="{{ old('text_submission', $submission->text_submission) }}">
                                </div>
                                @error('text_submission')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        @if($assignment->submission_type === 'file' || $assignment->submission_type === 'both')
                            <div>
                                <label for="files" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    File Upload {{ $assignment->submission_type === 'file' ? '*' : '' }}
                                </label>
                                <input type="file" 
                                       id="files" 
                                       name="files[]" 
                                       multiple
                                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Max {{ $assignment->max_files }} file(s), {{ $assignment->max_file_size }}MB each. 
                                    Allowed types: {{ implode(', ', array_map('strtoupper', $assignment->allowed_file_types)) }}
                                </p>
                                @error('files')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- Current Files Display -->
                        @if($submission->file_submissions && count($submission->file_submissions) > 0)
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Current Files</h4>
                                <div class="space-y-2">
                                    @foreach($submission->file_submissions as $file)
                                        <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $file['file_name'] }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($file['file_size'] / 1024, 1) }} KB</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($file['file_path']) }}" 
                                               download="{{ $file['file_name'] }}"
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Submit Buttons -->
                        <div class="flex items-center gap-4">
                            <button type="submit" 
                                    name="status" 
                                    value="draft"
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition duration-150 ease-in-out">
                                Save as Draft
                            </button>
                            <button type="submit" 
                                    name="status" 
                                    value="submitted"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-150 ease-in-out">
                                Submit Assignment
                            </button>
                        </div>
                    </form>
                </div>
            @elseif($submission->status !== 'graded' && !$canSubmit)
                <!-- Max Attempts Reached -->
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-red-900 dark:text-red-100 mb-2">Submission Limit Reached</h2>
                    <p class="text-red-700 dark:text-red-300">
                        You have reached the maximum number of attempts ({{ $assignment->max_attempts }}) for this assignment.
                    </p>
                </div>
            @else
                <!-- Graded Submission Display -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Your Submission</h2>

                    @if($submission->text_submission)
                        <div class="mb-4">
                            <h3 class="font-medium text-gray-900 dark:text-white mb-2">Text Submission</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="text-gray-700 dark:text-gray-300 prose prose-sm dark:prose-invert max-w-none">
                                    {!! $submission->text_submission !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($submission->file_submissions && count($submission->file_submissions) > 0)
                        <div class="mb-4">
                            <h3 class="font-medium text-gray-900 dark:text-white mb-2">File Submissions</h3>
                            <div class="space-y-2">
                                @foreach($submission->file_submissions as $file)
                                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $file['file_name'] }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($file['file_size'] / 1024, 1) }} KB</p>
                                            </div>
                                        </div>
                                        <a href="{{ Storage::url($file['file_path']) }}" 
                                           download="{{ $file['file_name'] }}"
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Assignment Info Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assignment Info</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Status</span>
                        <span class="font-semibold text-gray-900 dark:text-white capitalize">
                            @if($submission->status === 'graded')
                                Graded
                            @elseif($submission->status === 'submitted')
                                Submitted
                            @elseif($submission->status === 'draft')
                                Draft
                            @else
                                Not Started
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Points</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $assignment->points }}</span>
                    </div>

                    @if($submission->status === 'graded')
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Your Grade</span>
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ $submission->points_earned }} / {{ $assignment->points }}
                                </span>
                            </div>
                            @if($submission->feedback)
                                <div class="mt-2">
                                    <span class="text-gray-600 dark:text-gray-400 text-sm">Feedback:</span>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $submission->feedback }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($assignment->due_date)
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Due Date</span>
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ $assignment->due_date->format('M j, Y') }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill editor for text submission
            const textSubmissionQuill = new Quill('#text-submission-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['blockquote', 'code-block'],
                        ['link'],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                },
                placeholder: 'Enter your response here...'
            });

            // Set initial content if editing existing submission
            const existingContent = document.getElementById('text_submission').value;
            if (existingContent) {
                textSubmissionQuill.root.innerHTML = existingContent;
            }

            // Update hidden input with HTML content
            textSubmissionQuill.on('text-change', function() {
                const html = textSubmissionQuill.root.innerHTML;
                document.getElementById('text_submission').value = html;
            });
        });
    </script>
</x-student-layout>



