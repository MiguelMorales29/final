<x-teacher-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Archived Applications</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">View and manage archived student applications</p>
                    </div>
                    <a href="{{ route('teacher.applications.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Applications
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Bulk Actions -->
            @if($applications->count() > 0)
                <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div class="flex flex-wrap gap-2">
                            <button id="selectAll" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 rounded-lg transition-colors duration-200">
                                Select All
                            </button>
                            <button id="selectNone" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                                Select None
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button id="bulkUnarchiveBtn" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                Unarchive Selected
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Applications List -->
            @if($applications->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($applications as $application)
                                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600" data-application="{{ $application->id }}">
                                            <!-- Application Header -->
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex items-center space-x-3">
                                                    <!-- Checkbox -->
                                                    <input type="checkbox" 
                                                           class="application-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                                           value="{{ $application->id }}">
                                                    
                                                    <!-- Student Avatar -->
                                                    <div class="relative">
                                                        <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800" 
                                                             src="{{ $application->student->profile_picture ? asset('storage/' . $application->student->profile_picture) : asset('images/default-avatar.png') }}" 
                                                             alt="{{ $application->student->name }}">
                                                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-gray-400 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                                    </div>
                                                    
                                                    <!-- Student Info -->
                                                    <div class="min-w-0 flex-1">
                                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                            {{ $application->student->name }}
                                                        </h3>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                            {{ $application->student->email }}
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Archived Badge -->
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l4 4-4 4m5-4h6"></path>
                                                    </svg>
                                                    Archived
                                                </span>
                                            </div>
                                            
                                            <!-- Course Information -->
                                            <div class="mb-3">
                                                <p class="text-sm text-gray-600 dark:text-gray-300 font-medium mb-1">
                                                    {{ $application->course->title }}
                                                </p>
                                                <div class="flex items-center space-x-3 text-xs text-gray-500 dark:text-gray-400">
                                                    <span>Applied {{ $application->created_at->format('M j, Y') }}</span>
                                                    @if($application->reviewed_at)
                                                        <span>• Reviewed {{ $application->reviewed_at->format('M j, Y') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Teacher's Note -->
                                            @if($application->message)
                                                <div class="mb-3 p-2 bg-blue-50 dark:bg-blue-900/20 rounded border-l-2 border-blue-400">
                                                    <p class="text-xs text-blue-800 dark:text-blue-200">
                                                        <span class="font-medium">Note:</span> {{ $application->message }}
                                                    </p>
                                                </div>
                                            @endif
                                            
                                            <!-- Actions -->
                                            <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        Archived on {{ $application->updated_at->format('M j, Y \a\t g:i A') }}
                                                    </p>
                                                    <form method="POST" action="{{ route('applications.unarchive', $application->id) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition duration-150 ease-in-out"
                                                                onclick="return confirm('Unarchive this application?')">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                            </svg>
                                                            Unarchive
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8l4 4-4 4m5-4h6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">No Archived Applications</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        No applications have been archived yet.
                    </p>
                    <a href="{{ route('teacher.applications.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Applications
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Bulk Unarchive Form -->
    <form id="bulkUnarchiveForm" method="POST" action="{{ route('applications.bulk-unarchive') }}" style="display: none;">
        @csrf
        <div id="bulkUnarchiveInputs"></div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllBtn = document.getElementById('selectAll');
            const selectNoneBtn = document.getElementById('selectNone');
            const bulkUnarchiveBtn = document.getElementById('bulkUnarchiveBtn');
            const checkboxes = document.querySelectorAll('.application-checkbox');

            function updateBulkUnarchiveButton() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                bulkUnarchiveBtn.disabled = checkedBoxes.length === 0;
            }

            function updateSelectAllButton() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                
                if (allChecked) {
                    selectAllBtn.textContent = 'Select None';
                } else {
                    selectAllBtn.textContent = 'Select All';
                }
            }

            // Event listeners
            selectAllBtn.addEventListener('click', function() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                
                checkboxes.forEach(cb => {
                    cb.checked = !allChecked;
                });
                
                updateBulkUnarchiveButton();
                updateSelectAllButton();
            });

            selectNoneBtn.addEventListener('click', function() {
                checkboxes.forEach(cb => {
                    cb.checked = false;
                });
                updateBulkUnarchiveButton();
                updateSelectAllButton();
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateBulkUnarchiveButton();
                    updateSelectAllButton();
                });
            });

            bulkUnarchiveBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                if (checkedBoxes.length === 0) return;

                if (confirm(`Unarchive ${checkedBoxes.length} selected application(s)?`)) {
                    const form = document.getElementById('bulkUnarchiveForm');
                    const inputsContainer = document.getElementById('bulkUnarchiveInputs');
                    inputsContainer.innerHTML = '';

                    checkedBoxes.forEach(checkbox => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'application_ids[]';
                        input.value = checkbox.value;
                        inputsContainer.appendChild(input);
                    });

                    form.submit();
                }
            });

            // Initialize
            updateBulkUnarchiveButton();
            updateSelectAllButton();
        });
    </script>
</x-teacher-layout>
